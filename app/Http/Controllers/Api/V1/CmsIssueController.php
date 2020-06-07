<?php

namespace App\Http\Controllers\Api\V1;

use App\Business\CmsIssueBussiness;
use App\Helper\ResponseUtil;
use App\Http\Controllers\Api\ApiController;

class CmsIssueController extends ApiController
{
    public function index()
    {
        $rules = [
            'page'            => 'required|integer|min:1',
            'limit'           => 'required|integer|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $validated = $this->validated;
        $validated['userId'] = $this->uid;

        $res = CmsIssueBussiness::getList($validated);

        return ResponseUtil::json($res);
    }
}
