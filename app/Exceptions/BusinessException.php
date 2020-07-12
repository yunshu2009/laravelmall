<?php

namespace App\Exceptions;

use App\Constants\ResultCode;

class BusinessException extends AppRuntimeException
{
    public function __construct($code = ResultCode::BAD_REQUEST, $message = null, $statusCode=400, $errors='')
    {
        parent::__construct($code, $message, $errors, $statusCode, $headers = []);
    }
}
