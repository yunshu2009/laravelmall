<?php

namespace App\Http\Validators;

use App\Helper\RegexUtil;

class UtilValidator
{
    public function validateMobile($attribute, $value, $parameters, $validator)
    {
        return RegexUtil::isMobile($value) ? true : false;
    }
}
