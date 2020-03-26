<?php

namespace App\Http\Controllers\Api;

use App\Helper\Token;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
//    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $uid;

    public function __construct()
    {
        parent::__construct();
        // todo：完善
//        $this->uid = $this->request->userId ?? 0;
        $token = Token::authorization();
        if (is_int($token)) {
            $this->uid = $token;
        }
    }
}
