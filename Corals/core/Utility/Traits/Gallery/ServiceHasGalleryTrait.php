<?php


namespace Corals\Utility\Traits\Gallery;


trait ServiceHasGalleryTrait
{
    public function handleGalleryInputs($request, $galleryModel)
    {
        $galleryInputs = $request->only(['gallery_new', 'gallery_deleted', 'gallery_favorite']);

        $deleted = [];

        foreach (explode(',', data_get($galleryInputs, 'gallery_deleted')) as $fileHash) {
            if (!$fileHash) {
                continue;
            }
            $files = glob(public_path("tmp/{$fileHash}_tmp_*"));
            foreach ($files as $file) {
                $deleted[] = $fileHash;
                @unlink($file);
            }
        }

        foreach (explode(',', data_get($galleryInputs, 'gallery_new')) as $fileHash) {
            if (!$fileHash || in_array($fileHash, $deleted)) {
                continue;
            }

            $files = glob(public_path("tmp/{$fileHash}_tmp_*"));

            foreach ($files as $file) {
                $properties = [
                    'root' => 'user_' . user()->hashed_id,
                ];

                if ($galleryInputs['gallery_favorite'] == $fileHash) {
                    $properties['featured'] = true;
                }

                $galleryModel->addMedia($file)
                    ->withCustomProperties($properties)
                    ->toMediaCollection($galleryModel->galleryMediaCollection);
            }
        }
    }
}
