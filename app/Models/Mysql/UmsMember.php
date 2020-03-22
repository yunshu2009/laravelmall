<?php

namespace App\Models\Mysql;

class UmsMember extends BaseModel
{
    protected $table = 'ums_member';

    public $fillable = [
        'id',
        'username',
        'password',
        'gender',
        'last_login_time',
        'last_login_ip',
        'member_level',
        'nickname',
        'mobile',
        'avatar',
        'weixin_openid',
        'session_key',
        'status',
        'deleted'
    ];
}
