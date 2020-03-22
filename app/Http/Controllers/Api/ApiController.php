<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{
//    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $uid;

    public function __construct()
    {
        parent::__construct();
        $this->uid = $this->request['uid'] ?? 0;
    }
}
