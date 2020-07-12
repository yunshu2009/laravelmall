<?php

namespace App\Helper;

use App\Constants\ResultCode;
use Illuminate\Support\Str;

class CommonResult
{
    public static function formatPaged($page, $size, $total)
    {
        $page = (int)$page;
        $size = (int)$size;
        $total = (int)$total;

        return [
            'total' => $total,   // 总条数
            'page' => $page,
            'limit' => $size,
            'pages' => ceil($total/$size),
        ];
    }

    public static function formatBody($data=[], $msg='')
    {
        $res['errno'] = 0;
        $res['errmsg'] = $msg ? $msg : '成功';
        $res['data'] = self::transform($data);
        clear_null($res['data']);

        return $res;
    }

    // 将字段从蛇形命名方式改成驼峰命名方式
    protected static function transform($data)
    {
        if (! is_array($data)) {
            return $data;
        }

        $newArr = [];
        foreach ($data as  $k=>$v){
            if (is_string($k)) {
                $k = Str::camel($k);
            }
            if(is_array($v)){
                $v = self::transform($v);
            }

            $newArr[$k] = $v;
        }

        return $newArr;
    }

    public static function formatError($code, $message = null)
    {
        $message = $message ? $message : ResultCode::getMessage($code);

        $res['errno'] = $code;
        $res['errmsg'] = $message;

        return $res;
    }
}
