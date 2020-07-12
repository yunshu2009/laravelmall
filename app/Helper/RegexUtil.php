<?php

namespace App\Helper;

class RegexUtil
{
    // 精确验证手机号码
    const REGEX_MOBILE = '^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199)\d{8}$';

    public static function isMobile($input)
    {
        return self::isMatch(self::REGEX_MOBILE, $input);
    }

    public static function isMatch($regex, $input)
    {
        return !empty($input) &&  preg_match('/'.$regex.'/', $input);
    }
}
