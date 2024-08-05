<?php

namespace Corals\Activity\HttpLogger\Classes;

use Corals\Activity\HttpLogger\Contracts\LogWriter as LogWriterContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class LogWriter implements LogWriterContract
{
    /**
     * @param Request $request
     * @param $response
     * @return mixed|void
     */
    public function logRequest(Request $request, $response)
    {
        try {
            $user = $request->user();

            DB::table('http_log')->insertGetId([
                'ip' => $request->ip(),
                'user_id' => data_get($user, 'id'),
                'email' => data_get($user, 'email'),
                'uri' => $request->getPathInfo(),
                'method' => strtoupper($request->getMethod()),
                'headers' => json_encode($request->headers->all()),
                'body' => $this->getRequestBody($request),
                'files' => json_encode($this->getRequestFiles($request)),
                'created_at' => now(),
                'response' => json_encode($this->getRequestResponseDetails($response)),
                'response_code' => $response->getStatusCode()
            ]);
        } catch (\Exception $exception) {
            report($exception);
        }
    }

    /**
     * @param Request $request
     * @return false|string
     */
    public function getRequestBody(Request $request)
    {
        $body = $request->except(config('http-logger.except'));

        if (!empty($body['card_number'])) {
            $body['card_number'] = str_repeat('X', 12) . substr($body['card_number'], -4, 4);
        }

        foreach (['cvv', 'ccv'] as $key) {
            if (!empty($body[$key])) {
                $body[$key] = str_repeat('X', strlen($body[$key]));
            }
        }

        $bodyContent = $request->getContent();

        $body = json_encode($body);

        if (!empty($bodyContent) && empty($body)) {
            $body .= $bodyContent;
        }

        return $body;
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function getRequestFiles(Request $request): array
    {
        $files = [];

        foreach (iterator_to_array($request->files) as $index => $file) {
            $files[$index] = $this->getFileNames($file);
        }

        return $files;
    }

    /**
     * @param $file
     * @return array
     */
    protected function getFileNames($file): array
    {
        if (is_array($file)) {
            $name = [];
            foreach ($file as $key => $aFile) {
                $name[$key] = $this->getFileNames($aFile);
            }
        } else {
            if ($file instanceof UploadedFile) {
                $name = [
                    'clientOriginalName' => $file->getClientOriginalName(),
                    'size' => $file->isValid() && $file->isFile() ? $file->getSize() : 0,
                    'clientOriginalExtension' => $file->getClientOriginalExtension(),
                    'clientMimeType' => $file->getClientMimeType()
                ];
            } else {
                $name = $file;
            }
        }

        return $name;
    }

    /**
     * @param $response
     * @return JsonResponse|mixed
     */
    protected function getRequestResponseDetails($response)
    {
        $exception = null;

        if (property_exists($response, 'exception')) {
            $exception = $response->exception;
        }

        if ($response->getStatusCode() >= 400 && $response->getStatusCode() < 500 || $exception) {
            $httpResponse = [
                'responseStatusCode' => $response->getStatusCode(),
            ];

            if ($response instanceof JsonResponse) {
                $httpResponse['responseData'] = $response->getData();
            }

            if ($exception) {
                $httpResponse['message'] = $exception->getMessage();
                if ($exception instanceof ValidationException) {
                    $httpResponse['status'] = $exception->status;
                    $httpResponse['errors'] = $exception->errors();
                }
            }

            $jsonResponse = $httpResponse;
        } else {
            $jsonResponse = $response;
        }

        return $jsonResponse;
    }
}
