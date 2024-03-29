<?php

namespace Mawuekom\SecurityFeatures\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Cache\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ThrottleRequests
 * 
 * @package Mawuekom\SecurityFeatures\Http\Middleware
 */
class ThrottleRequests
{
    /**
     * The rate limiter instance.
     *
     * @var \Illuminate\Cache\RateLimiter
     */
    protected $limiter;

    /**
     * Create a new request throttler.
     *
     * @param \Illuminate\Cache\RateLimiter $limiter
     *
     * @return void
     */
    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param int                      $maxAttempts
     * @param float|int                $decayMinutes
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1)
    {
        $key = $this->resolveRequestSignature($request);

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            return $this->buildResponse($key);
        }

        if (!in_array(env('APP_ENV'), ['testing', 'local']) && !env('SKIP_THROTTLE')) {
            $this->limiter->hit($key, $decayMinutes);
        }

        if (getenv('THROTTLE_TEST')) {
            $this->limiter->hit($key, $decayMinutes);
        }

        $response = $next($request);

        return $this->addHeaders(
            $response,
            $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts)
        );
    }

    /**
     * Resolve request signature.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    protected function resolveRequestSignature($request)
    {
        return sha1(
            $request->method().
            '|'.$request->server('SERVER_NAME').
            '|'.$request->path().
            '|'.$request->ip()
        );
    }

    /**
     * Create a 'too many attempts' response.
     *
     * @param string $key
     * @param int    $maxAttempts
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function buildResponse($key)
    {
        new Response('Too Many Attempts.', 429);
        $retryAfter = $this->limiter->availableIn($key);

        return response(
            sprintf('Too many consecutive attempts. Try again in %ss', $retryAfter),
            429
        );
    }

    /**
     * Add the limit header information to the given response.
     *
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param int                                        $maxAttempts
     * @param int                                        $remainingAttempts
     * @param int|null                                   $retryAfter
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function addHeaders(Response $response, $maxAttempts, $remainingAttempts, $retryAfter = null)
    {
        $headers = [
            'X-RateLimit-Limit'     => $maxAttempts,
            'X-RateLimit-Remaining' => $remainingAttempts,
        ];

        if (!is_null($retryAfter)) {
            $headers['Retry-After'] = $retryAfter;
            $headers['X-RateLimit-Reset'] = Carbon::now()->getTimestamp() + $retryAfter;
        }

        $response->headers->add($headers);

        return $response;
    }

    /**
     * Calculate the number of remaining attempts.
     *
     * @param string   $key
     * @param int      $maxAttempts
     * @param int|null $retryAfter
     *
     * @return int
     */
    protected function calculateRemainingAttempts($key, $maxAttempts, $retryAfter = null)
    {
        if (is_null($retryAfter)) {
            return $this->limiter->retriesLeft($key, $maxAttempts);
        }

        return 0;
    }
}