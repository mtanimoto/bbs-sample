<?php

namespace App\Controllers;

class PhpInfoController extends BaseController
{
    public function index()
    {
        return view('phpinfo');
    }
}
