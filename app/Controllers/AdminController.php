<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsersModel;
use App\Models\BoardsModel;

class AdminController extends BaseController
{
    public function index(string $boardId)
    {
        $session = session();
        if (!$session->has('user_id')) {
            return view('errors/html/error_general', [
                'message' => 'not authenticated',
            ]);
        }

        $userId = $session->get('user_id');

        $usersModel = new UsersModel();
        $userInfo = $usersModel->find($userId);

        $boardsModel = new BoardsModel();
        $boardInfo = $boardsModel->where('id', $boardId)->first();
        return view('frame', [
            'title' => $userInfo['screen_name'] . ' - ' . $boardInfo['title'] . 'のメニュー',
            'section' => 'admin',
            'user' => $userInfo,
            'board' => $boardInfo,
        ]);
    }

    public function update(string $boardId)
    {
        if (!$this->validate([
            'title' => 'required|max_length[128]',
            'name' => 'max_length[128]',
        ])) {
            return view('errors/html/error_general', [
                'message' => \Config\Services::validation()->listErrors(),
            ]);
        }

        $session = session();
        if (!$session->has('user_id')) {
            return view('errors/html/error_general', [
                'message' => 'not authenticated',
            ]);
        }
        $userId = $session->get('user_id');

        $post = $this->request->getPost();

        $boardsModel = new BoardsModel();
        $keys = [
            'user_id' => $userId,
            'id' => $boardId,
        ];
        $data = [
            'title' => $post['title'],
            'name' => $post['name'],
            'description' => $post['description'],
        ];
        $boardsModel->update($keys, $data);

        return redirect()->to("/{$boardId}/admin");
    }
}
