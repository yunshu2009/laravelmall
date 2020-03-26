<?php

namespace App\Http\Middleware;

use App\Constants\ResultCode;
use App\Helper\CommonResult;
use App\Helper\ResponseUtil;
use Closure;
use App\Helper\Token;

class TokenAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = Token::authorization();

        if ($token === false) {
            $body = CommonResult::formatError(ResultCode::UNAUTHORIZED, 'Token invalid.');
            return ResponseUtil::json($body);
        }

        if ($token ===  'token-expired') {
            $body = CommonResult::formatError(ResultCode::UNAUTHORIZED, 'Token expired.');
            return ResponseUtil::json($body);
        }

        $request->offsetSet('userId', $token);

        return $next($request);
    }
}
