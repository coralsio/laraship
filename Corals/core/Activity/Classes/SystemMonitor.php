<?php

namespace Corals\Activity\Classes;

use Corals\Activity\Http\Middleware\SystemMonitorMiddleware;
use Corals\Activity\HttpLogger\Contracts\LogWriter;
use Corals\Foundation\Notifications\ExceptionsNotification;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpFoundation\Response;

class SystemMonitor
{
    /**
     * @var int
     */
    protected $maxAttemptLimits = 4;

    /**
     *
     * reset the key each X seconds
     *
     * @var int
     */
    protected $limitForSeconds = 30;

    /**
     * The rate limiter instance.
     *
     * @var RateLimiter
     */
    protected $limiter;

    /**
     * SystemMonitor constructor.
     * @param RateLimiter $rateLimiter
     */
    public function __construct(RateLimiter $rateLimiter)
    {
        $this->limiter = $rateLimiter;
    }

    /**
     *
     */
    public function configure()
    {
        if (!config('activity.system_monitor_enabled')) {
            return;
        }

        app(Kernel::class)->prependMiddleware(SystemMonitorMiddleware::class);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return int
     */
    public function monitor(Request $request, Response $response)
    {
        $urlPathKey = $this->urlPathKey();

        $executedAttempts = $this->limiter->tooManyAttempts($urlPathKey, $this->maxAttemptLimits);

        if (!$executedAttempts) {
            return $this->limiter->hit($urlPathKey, $this->limitForSeconds);
        }

        $this->attemptsExecutedFor($request, $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     */
    protected function attemptsExecutedFor(Request $request, Response $response)
    {
        $channels = config('corals.slack.exception_channels');

        if (!$channels) return;

        $messages = [
            'IP Address: ' . $request->ip(),
            'Endpoint: ' . $request->getPathInfo(),
            'Method: ' . $request->getMethod(),
            'Response Status: ' . $response->getStatusCode(),
            'Request Time: ' . format_date_time(now()),
            'Request Payload: ' . app(LogWriter::class)->getRequestBody($request)
        ];

        if (user()) {
            array_unshift($messages, 'User Email: ' . user()->email);
            array_unshift($messages, 'User ID: ' . user()->id );
        }

        array_unshift($messages, "*Unusual Behaviour Detected*");

        $exception = new \Exception(join("\n", $messages));

        foreach ($channels as $channel) {
            Notification::route('slack', $channel)->notify(
                new ExceptionsNotification($exception, false)
            );
        }
    }

    /**
     * @return string
     */
    protected function urlPathKey()
    {
        return sprintf("%s_%s", request()->ip(), request()->getPathInfo());
    }
}