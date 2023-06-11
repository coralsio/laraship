<?php

namespace Corals\Media\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediasController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @param Media $media
     * @param null $target
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws FileNotFoundException
     */
    public function getMedia(Request $request, $media, $target = null)
    {
        $media = Media::findOrFail($media);

        $disk = $media->disk;

        if ($disk == 's3') {
            if (!\Storage::disk($disk)->exists($media->getPath())) {
                throw  new FileNotFoundException();
            }

            return redirect($media->getTemporaryUrl(now()->addMinutes(5)));
        }

        if ($target == 'download') {
            return response()->download($media->getPath(), $media->file_name);
        } else {
            return response()->file($media->getPath());
        }
    }

    /**
     * @param Request $request
     * @param Media $media
     * @return \Illuminate\Http\JsonResponse
     */
    public function mediaDelete(Request $request, $media)
    {
        try {
            $media = Media::findOrFail($media);

            abort_if(!user()->hasPermissionTo('Administrations::admin.media'), 403, 'Forbidden!!');

            $deleted_hashed_id = hashids_encode($media->id);
            $media->delete();

            $message = [
                'level' => 'success',
                'message' => trans('Corals::messages.success.deleted',
                    ['item' => trans('Media::labels.media.attachment.attachment')]),
                'deleted_hashed_id' => $deleted_hashed_id
            ];
        } catch (\Exception $exception) {
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
            log_exception($exception, Media::class, 'destroy');
            $code = 400;
        }

        return response()->json($message, $code ?? 200);
    }
}
