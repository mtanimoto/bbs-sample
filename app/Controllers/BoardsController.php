<?php

namespace App\Controllers;

use App\Models\BoardsModel;

class BoardsController extends BaseController
{
    public function index()
    {
        $boards = new BoardsModel();
        $rows = $boards->findAll();
        return view('frame', [
            'title' => '板一覧',
            'section' => 'boards',
            'rows' => $rows
        ]);
    }
}
