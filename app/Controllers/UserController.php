<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\TwitterOAuth;
use App\Models\UsersModel;
use App\Models\BoardsModel;

class UserController extends BaseController
{
    public function index()
    {
        $session = session();
        $errorMessage = $session->get('failed_to_signup');
        $session->remove('failed_to_signup');

        return view('frame', [
            'title' => '新規登録',
            'section' => 'signup',
            'errorMessage' => $errorMessage,
        ]);
    }

    public function register()
    {
        $boardId = $this->request->getPost('board_id');
        $session = session();
        $session->set('boardId', $boardId);

        $twitter = new TwitterOAuth();
        return $twitter->redirectAuthPage(base_url() . '/signup/twitter');
    }

    public function callback()
    {
        $twitter = new TwitterOAuth();

        try {
            $parameters = $this->request->getGet();

            if (isset($parameters['denied'])) {
                log_message('info', "Twitter連携キャンセル: {$parameters['denied']}");
                $session = session();
                $session->set('failed_to_signup', 'ログインできませんでした。');
                return redirect()->to("/signup");
            }

            $oauthToken = $parameters['oauth_token'];
            $oauthVerfier = $parameters['oauth_verifier'];

            $user = $twitter->callback($oauthToken, $oauthVerfier);

            if (!isset($user['user_id'])) {
                log_message('info', "Twitter連携失敗");
                $session = session();
                $session->set('failed_to_signup', 'Twitter連携に失敗しました。');
                return redirect()->to("/signup");
            }

            //  掲示板確認
            $session = session();
            $boardId = $session->get('boardId');

            $boardsModel = new BoardsModel();
            $record = $boardsModel->where([
                'id' => $boardId
            ])->first();
            if (!empty($record)) {
                log_message('info', "掲示板登録済み: {$boardId}");
                $session = session();
                $session->set('failed_to_signup', 'BBSはすでに利用されています。別のBBSIDを入力してください。');
                return redirect()->to("/signup");
            }

            // ユーザー確認
            $usersModel = new UsersModel();
            $record = $usersModel->find($user['user_id']);
            if (empty($record)) {
                // ユーザー登録
                $data = [
                'id' => $user['user_id'],
                'screen_name' => $user['screen_name'],
            ];
                $usersModel->insert($data);
            }

            // 掲示板作成
            $data = [
                'user_id' => $user['user_id'],
                'id' => $boardId,
                'title' => $user['screen_name'],
            ];
            $boardsModel->insert($data);

            $session = session();
            $session->set('user_id', $user['user_id']);

            return redirect()->to("/{$boardId}/admin");
        } finally {
            $twitter->removeSession();
        }
    }
}
