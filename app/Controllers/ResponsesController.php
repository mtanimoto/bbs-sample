<?php

namespace App\Controllers;

use App\Models\BoardsModel;
use App\Models\ThreadsModel;
use App\Models\ResponsesModel;
use App\Libraries\AzureStorageBlob;

class ResponsesController extends BaseController
{
    public function read(string $boardId, string $threadId, int $reponseId = null)
    {
        $boardsModel = new BoardsModel();
        $board = $boardsModel->where(['id' => $boardId])->first();

        $threadsModel = new ThreadsModel();
        $threadTitle = $threadsModel->getTitle($boardId, $threadId);

        $resModel = new ResponsesModel();
        $rows = $resModel->getResList($boardId, $threadId, $reponseId);

        $azureStorage = new AzureStorageBlob();
        foreach ($rows as &$row) {
            if (!empty($row['blob_path'])) {
                $row['blob_path'] = $azureStorage->getBlobUrl($row['blob_path']);
            }
        }

        return view('frame', [
            'title' => $threadTitle,
            'section' => 'responses',
            'board' => $board,
            'boardId' => $boardId,
            'threadId' => $threadId,
            'threadTitle' => $threadTitle,
            'rows' => $rows
        ]);
    }

    public function write()
    {
        if (!$this->validate([
            'bbs' => 'required',
            'key' => 'required',
            'name' => 'required',
            'comment' => 'required',
        ])) {
            return view('errors/html/error_general', [
                'message' => \Config\Services::validation()->listErrors(),
            ]);
        }

        $post = $this->request->getPost();
        $boardId = $post['bbs'];
        $threadId = $post['key'];

        $resModel = new ResponsesModel();
        $id = $resModel->getNextId($threadId);

        if ($id > MAX_RES) {
            return view('errors/html/error_general', [
                'message' => 'スレッドストップです。',
            ]);
        }

        $blobPath = null;
        $file = $this->request->getFile('resume');
        if ($file->isValid()) {
            $azureStorage = new AzureStorageBlob();
            if (($blobPath = $azureStorage->uploadBlob($file, $threadId)) === false) {
                return view('errors/html/error_general', [
                    'message' => 'fail to upload file.',
                ]);
            }
        }

        $data = [
            'thread_id' => $threadId,
            'id' => $id,
            'name' => $post['name'],
            'email' => $post['email'],
            'comment' => $post['comment'],
            'blob_path' => $blobPath,
        ];
        $resModel->insert($data);

        if ($id >= MAX_RES) {
            $data = [
                'thread_id' => $threadId,
                'id' => $id + 1,
                'name' => 'システム',
                'email' => 'sage',
                'comment' => "このスレッドは" . MAX_RES . "を超えました。\nもう書けないので、新しいスレッドを立ててくださいです。。。",
            ];
            $resModel->insert($data);
        }

        $threadsModel = new ThreadsModel();
        $id = [
            'board_id' => $boardId,
            'id' => $threadId,
        ];
        $data = [];
        $data['thread_age'] = $post['email'] !== 'sage';
        if ($data['thread_age']) {
            $data['aged_at'] = date('Y-m-d H:i:s');
        }
        
        $threadsModel->update($id, $data);

        return redirect()->to("/bbs/read.cgi/{$boardId}/{$threadId}");
    }
}
