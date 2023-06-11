<?php

use Illuminate\Validation\ValidationException;

if (!function_exists('apiOptionAsObject')) {
    /**
     * @param $key
     * @param $value
     * @param $keyLabel
     * @param $valueLabel
     * @return false|mixed
     */
    function apiOptionAsObject($key, $value, $keyLabel = 'key', $valueLabel = 'value')
    {
        return current(apiPluck([$key => $value], $keyLabel, $valueLabel));
    }
}

if (!function_exists('apiPluck')) {
    /**
     * @param $array
     * @param string $keyLabel
     * @param string $valueLabel
     * @return array
     */
    function apiPluck($array, $keyLabel = 'key', $valueLabel = 'value')
    {
        if ($array instanceof \Illuminate\Support\Collection) {
            $array = $array->toArray();
        } elseif (!is_array($array)) {
            return [];
        }

        $result = [];

        foreach ($array as $key => $value) {
            $result[] = [$keyLabel => $key, $valueLabel => $value];
        }

        return $result;
    }
}

if (!function_exists('apiResponse')) {
    /**
     * @param $data
     * @param string $message
     * @param string $status
     * @param int $HttpStatus
     * @param array $headers
     * @param int $options
     * @return \Illuminate\Http\JsonResponse
     */
    function apiResponse($data, $message = '', $status = 'success', $HttpStatus = 200, $headers = [], $options = 0)
    {
        return response()->json([
            'status' => $status,
            'message' => strip_tags($message),
            'data' => $data,
        ], $HttpStatus, $headers, $options);
    }
}
if (!function_exists('apiExceptionResponse')) {
    /**
     * @param $exception
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    function apiExceptionResponse($exception, $data = [])
    {
        logger(array_slice(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2), -1));

        report($exception);

        if ($exception instanceof ValidationException) {
            return apiResponse(['errors' => $exception->validator->getMessageBag()],
                trans('validation.message'), 'error', 422);
        }

        return apiResponse(array_merge(['exception_code' => $exception->getCode()], $data),
            strip_tags($exception->getMessage()), 'error', 400);
    }
}
