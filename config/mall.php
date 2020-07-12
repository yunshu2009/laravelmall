<?php

// 应用默认配置,system_config表中的配置项都必须在这里配置
return [
    // 小程序相关配置默认值
    'wx_index_new'  =>  6,
    'wx_index_hot'  =>  6,
    'wx_index_brand'    =>  4,
    'wx_index_topic'    =>  4,
    'wx_index_catlog_list'  => 4,
    'wx_index_catlog_goods'  => 4,
    'wx_share'  =>  "false",
    'wx_catlog_list'     =>  4,   // 首页显示分类个数
    'wx_catlog_goods'    =>  4,
    // 运费相关配置默认值


    // 订单相关配置默认值

    // 商城相关配置默认值

    // 小程序appid和appsecret配置
    'wechat_minprogram'   =>    [
        'appid' =>  env('MINI_PROGRAM_APPID'),
        'appsecret' =>  env('MINI_PROGRAM_SECRET'),
    ],
];
