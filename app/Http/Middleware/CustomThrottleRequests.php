<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Middleware\ThrottleRequests;
use App\Traits\ApiResponser;

class CustomThrottleRequests extends ThrottleRequests
{
    use ApiResponser;

    protected function buildException($key, $maxAttempts)
    {
        $response = $this->errorResponse('Too Many Attempts.',429);
        $retryAfter = $this->getTimeUntilNextRetry($key);

        $headers = $this->getHeaders(
            $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts, $retryAfter),
            $retryAfter
        );

        return new ThrottleRequestsException(
            'holu', null, $headers
        );
    }
}
