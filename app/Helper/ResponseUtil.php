<?php

namespace App\Helper;

use App\Constants\ResultCode;
use Illuminate\Support\Facades\Log;

class ResponseUtil
{
    public static function error($code, $msg='')
    {
        $body = CommonResult::formatError($code, $msg);

        return self::json($body);
    }

    public static function ok($data=[], $msg='')
    {
        $body = CommonResult::formatBody($data, $msg);

        return self::json($body);
    }

    public static function json($body = [])
    {
        $request = request();

        if (config('app.debug')) {
            Log::debug(TRACE_ID, [
                'LOG_ID'         => TRACE_ID,
                'IP_ADDRESS'     => $request->ip(),
                'REQUEST_URL'    => $request->fullUrl(),
                'AUTHORIZATION'  => $request->header('X-'.config('app.name').'-Authorization'),
                'REQUEST_METHOD' => $request->method(),
                'PARAMETERS'     => $request->validated,
                'RESPONSES'      => $body
            ]);

            $body['traceid'] = TRACE_ID;
        }

        if ($body['errno'] != ResultCode::SUCCESS) { // 有错误时
            $response = response()->json($body)->setEncodingOptions(JSON_UNESCAPED_SLASHES);
            $response->header('X-'.config('app.name').'-ErrorNo', $body['errno']);
            $response->header('X-'.config('app.name').'-ErrorMsg', urlencode($body['errmsg']));
        } else {
            $response = response()->json($body)->setEncodingOptions(JSON_UNESCAPED_SLASHES);
            $response->header('X-'.config('app.name').'-ErrorNo', 0);
        }

        if (config('api.token.refresh')) {
            if ($new_token = Token::refresh()) {
                // 生成新token
                $response->header('X-'.config('app.name').'-New-Authorization', $new_token);
            }
        }

        return $response;
    }
}
