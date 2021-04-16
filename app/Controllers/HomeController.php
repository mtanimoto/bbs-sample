<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\I18n\Time;

class HomeController extends BaseController
{
    public function index()
    {
        return view('frame', [
            'title' => 'bbs-sample',
            'section' => 'home'
        ]);
    }
}
