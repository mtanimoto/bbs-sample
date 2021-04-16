<?php

namespace App\Controllers;

use App\Models\BoardsModel;
use App\Models\ThreadsModel;
use App\Models\ResponsesModel;

class ThreadsController extends BaseController
{
    public function index(string $boardId)
    {
        $boardsModel = new BoardsModel();
        $board = $boardsModel->where(['id' => $boardId])->first();

        $threads = new ThreadsModel();
        $rows = $threads->getThraeds($boardId);
        return view('frame', [
            'title' => 'スレッド一覧',
            'section' => 'threads',
            'board' => $board,
            'rows' => $rows
        ]);
    }

    public function write()
    {
        if (!$this->validate([
            'bbs' => 'required',
            'title' => 'required',
            'name' => 'required',
            'comment' => 'required',
        ])) {
            return view('errors/html/error_general', [
                'message' => \Config\Services::validation()->listErrors(),
            ]);
        }

        $post = $this->request->getPost();
        $boardId = $post['bbs'];
        $threadId = strtotime('now');

        $threadModel = new ThreadsModel();
        $data = [
            'board_id' => $boardId,
            'id' => $threadId,
            'title' => $post['title'],
            'aged_at' => date('Y-m-d H:i:s'),
        ];
        $threadModel->insert($data);

        $resModel = new ResponsesModel();
        $data = [
            'thread_id' => $threadId,
            'id' => 1,
            'name' => $post['name'],
            'email' => $post['email'],
            'comment' => $post['comment'],
        ];
        $resModel->insert($data);

        return redirect()->to("/bbs/read.cgi/{$boardId}/{$threadId}");
    }
}
