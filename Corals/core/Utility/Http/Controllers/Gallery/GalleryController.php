<?php

namespace Corals\Utility\Http\Controllers\Gallery;

use Corals\Foundation\Facades\Actions;
use Corals\Foundation\Http\Controllers\BaseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class GalleryController extends BaseController
{
    /**
     * @param Request $request
     * @param $galleryModelHashedId
     * @return string
     * @throws \Throwable
     */
    public function gallery(Request $request, $galleryModelHashedId)
    {
        try {
            $modelClass = $request->get('model_class');

            $galleryModel = $this->getGalleryModelByHash($modelClass, $galleryModelHashedId);

            $editable = true;

            return view('Utility::gallery.gallery', compact('galleryModel', 'editable'))->render();
        } catch (\Exception $exception) {
            return '';
        }
    }

    protected function getGalleryModelByHash($modelClass, $galleryModelHashedId)
    {
        $galleryModel = null;

        if (class_exists($modelClass)) {
            $galleryModel = $modelClass::findByHash($galleryModelHashedId);
        }

        if (!$galleryModel) {
            throw new ModelNotFoundException();
        }

        return $galleryModel;
    }

    /**
     * @param Request $request
     * @param $galleryModelHashedId
     * @return \Illuminate\Http\JsonResponse
     */
    public function galleryUpload(Request $request, $galleryModelHashedId = null)
    {
        try {
            if ($request->has('file')) {
                $rules = [
                    'file' => 'image|max:' . maxUploadFileSize(),
                    'model_class' => 'required'
                ];

                if (!$galleryModelHashedId) {
                    unset($rules['model_class']);
                }

                $this->validate($request, $rules);

                if (!$galleryModelHashedId) {
                    if (!file_exists(public_path('tmp'))) {
                        mkdir(public_path('tmp'), 0755);
                    }

                    $file = $request->file('file');

                    // Get filename with the extension
                    $filenameWithExt = $file->getClientOriginalName();

                    // Filename to store
                    $fileHash = hrtime(true);
                    $fileNameToStore = $fileHash . '_tmp_' . $filenameWithExt;

                    // Upload Image
                    $file->move(public_path('tmp'), $fileNameToStore);
                    $filePath = asset('tmp/' . $fileNameToStore);
                } else {
                    $modelClass = $request->get('model_class');
                    Actions::do_action('pre_update_gallery', $modelClass);

                    $galleryModel = $this->getGalleryModelByHash($modelClass, $galleryModelHashedId);

                    $galleryModel->addMedia($request->file('file'))
                        ->withCustomProperties(['root' => 'user_' . user()->hashed_id])
                        ->toMediaCollection($galleryModel->galleryMediaCollection);
                }

                $message = [
                    'level' => 'success',
                    'message' => trans('Utility::messages.gallery.success.upload'),
                    'file_link' => $filePath ?? '',
                    'file_hash' => $fileHash ?? '',
                ];
            }
        } catch (\Exception $exception) {
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
            log_exception($exception, 'Gallery', 'destroy');
        }

        return response()->json($message);
    }

    /**
     * @param Request $request
     * @param $media
     * @return \Illuminate\Http\JsonResponse
     */
    public function galleryItemDelete(Request $request, $media)
    {
        try {
            $media = Media::findOrFail($media);

            Actions::do_action('pre_update_gallery', $media);

            $media->delete();

            $message = [
                'level' => 'success',
                'message' => trans('Corals::messages.success.deleted',
                    ['item' => trans('Utility::module.gallery.media.title')])
            ];
        } catch (\Exception $exception) {
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
            log_exception($exception, Media::class, 'destroy');
        }

        return response()->json($message);
    }

    public function galleryItemFeatured(Request $request, $media)
    {
        try {
            $media = Media::findOrFail($media);

            Actions::do_action('pre_update_gallery', $media);

            $galleryModel = $media->model()->first();

            $gallery = $galleryModel->getMedia($galleryModel->galleryMediaCollection);

            foreach ($gallery as $item) {
                $item->forgetCustomProperty('featured');
                $item->save();
            }

            $media->setCustomProperty('featured', true);

            $media->save();

            $message = [
                'level' => 'success',
                'message' => trans('Corals::messages.success.saved',
                    ['item' => trans('Utility::module.gallery.media.title')])
            ];
        } catch (\Exception $exception) {
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
            log_exception($exception, Media::class, 'destroy');
        }

        return response()->json($message);
    }
}
