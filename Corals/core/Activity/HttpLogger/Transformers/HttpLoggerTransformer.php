<?php

namespace Corals\Activity\HttpLogger\Transformers;

use Corals\Activity\HttpLogger\Models\HttpLog;
use Corals\Foundation\Transformers\BaseTransformer;

class HttpLoggerTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('http_logger.models.http_log.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param HttpLog $httpLog
     * @return array
     * @throws \Throwable
     */
    public function transform(HttpLog $httpLog)
    {
        $transformedArray = [
            'id' => $httpLog->id,
            'uri' => '<a href="' . $httpLog->getShowURL() . '">' . $httpLog->uri . '</a>',
            'method' => $httpLog->method,
            'response_code' => data_get($httpLog, 'response_code', '-'),
            'user_id' => $httpLog->user ? $httpLog->user->present('full_name') : '',
            'email' => $httpLog->email,
            'ip' => $httpLog->ip ?? '-',
            'headers' => generatePopover($httpLog->headers),
            'body' => generatePopover($httpLog->body),
            'response' => generatePopover($httpLog->response),
            'files' => generatePopover($httpLog->files),
            'created_at' => format_date_time($httpLog->created_at) ?? '-',
        ];

        return parent::transformResponse($transformedArray);
    }
}
