<?php

namespace App\Exceptions;

use App\Constants\ResultCode;

class InternelException extends AppRuntimeException
{
    public function __construct($code = ResultCode::SYSERROR, $message = null, $statusCode=500, $errors='')
    {
        parent::__construct($code, $message, $errors, $statusCode, $headers = []);
    }
}
