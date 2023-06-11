<?php

namespace Corals\Foundation\Traits;

use Illuminate\Http\Request;

trait FileUploadTrait
{
    /**
     * @param Request $request
     * @param string $path
     * @param bool $public_path
     * @return Request
     */
    public function saveFiles(Request $request, $path = 'uploads', $public_path = true)
    {
        \Actions::do_action('pre_save_file', $request, $path, $public_path);

        if ($public_path) {
            $path = public_path($path);
        }

        if (!file_exists($path)) {
            mkdir($path, 0755);
        }

        $finalRequest = $request;

        foreach ($request->all() as $key => $value) {
            if ($request->hasFile($key)) {
                $filename = time() . '-' . $request->file($key)->getClientOriginalName();
                $request->file($key)->move($path, $filename);
                $finalRequest = new Request(array_merge($finalRequest->all(), [$key => $filename]));
            }
        }
        \Actions::do_action('post_save_file', $request, $path, $public_path);

        return $finalRequest;
    }

    /**
     * @param $file_path
     */
    public function deleteFile($file_path)
    {
        \Actions::do_action('pre_delete_file', $file_path);
        @unlink($file_path);
        \Actions::do_action('post_delete_file', $file_path);

    }


}
