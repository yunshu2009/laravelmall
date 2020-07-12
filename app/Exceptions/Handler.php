<?php

namespace App\Exceptions;

use App\Constants\ResultCode;
use App\Helper\CommonResult;
use App\Helper\ResponseUtil;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;;

class Handler extends ExceptionHandler
{
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
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof AppRuntimeException) {
            return AppRuntimeException::render(request(), $exception);
        } else {
            if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                return AppRuntimeException::render(request(), new AppRuntimeException(ResultCode::NOT_FOUND, '请求非法'));
            } elseif ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return AppRuntimeException::render(request(), new AppRuntimeException(ResultCode::NOT_FOUND, '指定 ' . class_basename($exception->getModel()) . ' 记录不存在'));
            } elseif ($exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
                return AppRuntimeException::render(request(), new AppRuntimeException(ResultCode::BAD_REQUEST, '非法请求'));
            }

            $message = '未知系统错误,异常类型：'.gettype($exception).','.$exception->getMessage().','.get_class($exception).','.$exception->getLine().','.$exception->getTraceAsString();

            Log::channel('syserror')->error(TRACE_ID, ['message'=>$message]);

            $data = CommonResult::formatError(ResultCode::SYSERROR, 'Service Unavailable.');
            return ResponseUtil::json($data);
        }
    }
}
