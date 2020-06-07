<?php

namespace App\Models\Mysql;

class Feedback extends BaseModel
{
    protected $table = 'feedback';

    protected $fillable = [
        'user_id',
        'username',
        'mobile',
        'feed_type',
        'content',
        'status',
        'has_picture',
        'pic_urls'
    ];

    protected $casts = [
        'status'    =>  'boolean',
    ];

    const FEED_TYPE_PRODUCT = 1;
    const FEED_TYPE_FUNCTION = 2;
    const FEED_TYPE_ADVISE = 3;
    const FEED_TYPE_OTHERS = 4;

    public static $types = [
        self::FEED_TYPE_PRODUCT => '商品问题',
        self::FEED_TYPE_FUNCTION => '功能异常',
        self::FEED_TYPE_ADVISE => '优化建议',
        self::FEED_TYPE_OTHERS => '其它'
    ];
}
