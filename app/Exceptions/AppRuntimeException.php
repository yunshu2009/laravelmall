<?php

namespace App\Exceptions;

use App\Constants\ResultCode;
use App\Helper\CommonResult;
use App\Helper\ResponseUtil;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class AppRuntimeException extends RuntimeException implements HttpExceptionInterface
{
    protected $errors;
    protected $statusCode;
    protected $headers;
    protected $data;

    public function __construct($code = 0, $message = '', $errors='', $statusCode = 400, $headers = [])
    {
        parent::__construct($message, $code);
        if (! $message) {
            $this->message = $this->getDefaultMessage($code);
        }

        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->errors = $errors;
    }

    public function getDefaultMessage($code, $message = '')
    {
        if (filled($message)) {
            return $message;
        }

        return isset(ResultCode::$errorList[$code]) ? ResultCode::$errorList[$code] : '';
    }


    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public static function render(Request $request, self $exception)
    {
        return $request->expectsJson() ? self::invalidJson($exception) : self::invalid($request, $exception);
    }


    protected static function invalid(Request $request, self $exception)
    {
        return redirect($request->getRequestUri() ?? url()->previous())
            ->withErrors(new MessageBag(['message' => $exception->getMessage()]));
    }

    public static function invalidJson(self $exception)
    {
        $data = CommonResult::formatError($exception->getCode(), $exception->getMessage());

        return ResponseUtil::json($data);
    }
}
