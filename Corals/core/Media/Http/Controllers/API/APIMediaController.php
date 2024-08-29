<?php

namespace Corals\Media\Http\Controllers\API;

use Corals\Foundation\Http\Controllers\APIBaseController;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class APIMediaController extends APIBaseController
{

    protected function getCommonValidationRules(): array
    {
        return [
            'file_name' => ['required',
                function ($attribute, $value, $fail) {
                    $extension = pathinfo($value)['extension'];
                    if (!in_array($extension, ['pdf', 'doc', 'docx', 'xlsx', 'mp4', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg', 'webp'])) {
                        $fail(sprintf('file of %s type not allowed', $extension));
                    }
                },],
            'model_id' => 'required',
            'model_type' => ['required', Rule::in(array_keys(Relation::$morphMap))],
            'collection' => 'required',
            'is_public' => 'required|boolean',
        ];
    }

    /**
     * @param $request
     * @return mixed
     * @throws \Exception
     */
    protected function getMediaModel($request): mixed
    {
        extract($request->only(['model_id', 'model_type', 'collection', 'file_name']));

        $modelClass = data_get(Relation::$morphMap, $model_type);

        $model = $modelClass::findOrFail($model_id);

        if (method_exists($model, 'allowedMediaCollections') && !in_array($collection, array_keys($model->allowedMediaCollections()))) {
            throw new \Exception("Invalid collection name [{$collection}]");
        }

        return $model;
    }

    protected function getPath($is_public, $model_type, $model_id, $collection): array
    {
        $pathArray = [];

        if ($is_public) {
            $pathArray[] = 'public';
        }

        return array_merge($pathArray, [
            $model_type,
            $model_id,
            $collection,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function getPreSignedURL(Request $request): JsonResponse
    {
        try {
            $this->validate($request, $this->getCommonValidationRules());

            $model = $this->getMediaModel($request);

            extract($request->only(['model_id', 'model_type', 'collection', 'file_name', 'is_public']));

            $isCollectionMany = true;

            if (method_exists($model, 'allowedMediaCollections')) {
                $isCollectionMany = $model->allowedMediaCollections($collection) === 'many';
            }

            $s3 = Storage::disk('s3');

            $client = $s3->getClient();

            $expiry = "+60 minutes";

            $pathArray = $this->getPath($is_public, $model_type, $model_id, $collection);

            $key = null;

            if ($isCollectionMany) {
                $key = time();
                $pathArray[] = $key;
            }

            $pathArray[] = $file_name;

            $command = $client->getCommand('PutObject', array_merge([
                'Bucket' => config('filesystems.disks.s3.bucket'),
                'Key' => join('/', $pathArray),
            ], $is_public ? ['acl' => 'public-read'] : []));

            $request = $client->createPresignedRequest($command, $expiry);

            return apiResponse(['pre_signed_url' => (string)$request->getUri(), 'key' => $key]);
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function storeMedia(Request $request): JsonResponse
    {
        try {
            $this->validate($request, array_merge($this->getCommonValidationRules(), [
                'name' => 'nullable',
                'mime_type' => 'required',
                'key' => 'nullable',
                'custom_properties' => 'nullable|array'
            ]));

            $model = $this->getMediaModel($request);

            extract($request->only(['model_id', 'model_type', 'collection', 'file_name', 'name', 'mime_type', 'size', 'is_public', 'media_id']));

            $key = $request->get('key');

            $pathArray = $this->getPath($is_public, $model_type, $model_id, $collection);

            $isCollectionSingle = false;

            if (method_exists($model, 'allowedMediaCollections')) {
                $isCollectionSingle = $model->allowedMediaCollections($collection) === 'single';
            }

            if ($isCollectionSingle || isset($media_id)) {
                if (isset($media_id)) {
                    $media = $model->getMedia($collection)->where('id', $media_id)->first();
                } else {
                    $media = $model->getFirstMedia($collection);
                }
                if ($media) {
                    DB::table($media->getTable())->where('id', $media->id)->delete();
                }
            }

            $media = $model->media()->create([
                'uuid' => $request->get('uuid', Str::uuid()),
                'collection_name' => $collection,
                'name' => $name ?? $file_name,
                'file_name' => $file_name,
                'mime_type' => $mime_type,
                'size' => $size,
                'disk' => 's3',
                'conversions_disk' => 's3',
                'custom_properties' => array_merge([
                    'root' => join('/', $pathArray)
                ], $key ? ['key' => $key] : ['key' => ''], $request->custom_properties ?? []),
            ]);

            return apiResponse([
                'media_id' => $media->id,
                'file_name' => $media->file_name
            ]);
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
