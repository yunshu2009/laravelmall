<?php

namespace App\Business;

use App\Helper\CommonResult;
use App\Models\Mysql\Feedback;
use App\Models\Mysql\UmsMember;

class FeedbackBusiness extends BaseBusiness
{
    public static function add(array $attributes)
    {
        $member = UmsMember::query()
                           ->where('id', $attributes['userId'])
                           ->first();

        $picUrls = $attributes['picUrls'] ?? [];
        $hasPic = $picUrls?true:false;
        $data = [
            'user_id'   =>  $member['id'],
            'username'  =>  $member['username'],
            'status'    =>  0,
            'content'   =>  $attributes['content'],
            'feed_type' =>  $attributes['feedType'],
            'has_picture' => $hasPic,
            'mobile'    =>  $attributes['mobile'],
            'pic_urls'  =>  json_encode($picUrls, JSON_UNESCAPED_SLASHES),
        ];

        Feedback::create($data);

        return CommonResult::formatBody();
    }
}
