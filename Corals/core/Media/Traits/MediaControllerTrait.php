<?php

namespace Corals\Media\Traits;


use Illuminate\Support\Str;

trait MediaControllerTrait
{
    /**
     * @param $request
     * @param $model
     * @param string $key
     * @return array
     */
    protected function handleAttachments($request, $model, $key = 'attachments'): array
    {
        $fileNames = [];

        foreach ($request->get($key, []) ?? [] as $index => $attachment) {
            $fileRequestPath = "$key.$index.file";
            if ($request->hasFile($fileRequestPath)) {
                $prefix = Str::slug(class_basename($model));

                $root = "$prefix/{$model->id}/$key";

                $description = $request->input("$key.$index.description", '');

                $media = $model->addMedia($request->file($fileRequestPath))
                    ->withCustomProperties(['root' => $root, 'description' => $description])
                    ->toMediaCollection($prefix . '-' . $key);

                $fileNames[] = $media->file_name;
            }
        }

        return $fileNames;
    }

    protected function attachmentsValidation($request, &$rules, &$attributes)
    {
        foreach ($request->get('attachments', []) as $index => $attachment) {
            $rules = array_merge($rules, [
                "attachments.{$index}.description" => 'required',
                "attachments.{$index}.file" => 'required|' . config('media.attachments_validation'),
            ]);

            $attributes = array_merge($attributes, [
                "attachments.{$index}.description" => 'description',
                "attachments.{$index}.file" => 'file'
            ]);
        }
    }
}
