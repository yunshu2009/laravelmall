<?php

namespace App\Http\Controllers\Api\V1;

use App\Business\FeedbackBusiness;
use App\Helper\ResponseUtil;
use App\Http\Controllers\Api\ApiController;
use App\Models\Mysql\Feedback;

class FeedbackController extends ApiController
{
    public function save()
    {
        $feedTypes = array_keys(Feedback::$types);
        $rules = [
            'content'           => 'required|string|min:1',
            'feedType'          => 'required|in:'.implode(',',$feedTypes),
            'mobile'            => 'required|mobile',
            'picUrls'           => 'array',
        ];
        $messages = [
            'picUrls.array'     =>':attribute 格式错误'
        ];
        $customAttributes = [
            'feedType'  =>'反馈类型',
            'picUrls'   =>'图片'
        ];

        if ($error = $this->validateInput($rules, $messages, $customAttributes)) {
            return $error;
        }

        $validated = $this->validated;
        $validated['userId'] = $this->uid;
        $res = FeedbackBusiness::add($validated);

        return ResponseUtil::json($res);
    }
}
