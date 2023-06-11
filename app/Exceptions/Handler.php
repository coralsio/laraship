<?php

namespace App\Exceptions;

use Corals\Foundation\Notifications\ExceptionsNotification;
use Exception;
use Illuminate\Cache\RateLimiter;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\InteractsWithTime;
use Illuminate\Validation\ValidationException;
use Throwable;


class Handler extends ExceptionHandler
{
    use InteractsWithTime;

    protected $limiter;

    public function __construct(Container $container, RateLimiter $limiter)
    {
        $this->limiter = $limiter;

        parent::__construct($container);
    }

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param Throwable $exception
     * @return void
     * @throws Exception
     */
    public function report(Throwable $exception)
    {
        if (app()->environment() === 'production') {
            $key = 'sendException';
            $decayMinutes = 0.1;
            $maxAttempts = 2;

            if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
                $timeUntilNextRetry = $this->limiter->availableIn($key);
                logger('[sendException] Too Many Attempts. TimeUntilNextRetry:' . $timeUntilNextRetry);
            } else {
                try {
                    if ($this->shouldReport($exception)) {
                        $this->limiter->hit($key, $decayMinutes * 60);
                        foreach (config('corals.slack.exception_channels', []) ?? [] as $channel) {
                            Notification::route('slack',
                                $channel)->notify(new ExceptionsNotification($exception));
                        }
                    }
                } catch (Exception $exception) {
                    logger($exception);
                }
            }
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $exception
     * @return Response
     * @throws Exception
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            if ($request->ajax()) {
                return response()->json(['message' => trans('validation.message'), 'errors' => $exception->validator->getMessageBag()], 422);
            }
        }

        return parent::render($request, $exception);
    }

    public function register()
    {
        $this->renderable(function (InvalidSignatureException $e) {
            return response()->view('errors.invalid_signature_error', [], 403);
        });
    }

}
