<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    protected $adminId;

    public function __construct()
    {
        parent::__construct();
        $this->adminId = $this->request['adminId'] ?? 0;
    }
}
