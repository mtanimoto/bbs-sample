<?php

namespace App\Controllers;

use App\Libraries\TwitterOAuth;
use App\Models\UsersModel;
use App\Models\BoardsModel;

class LoginController extends BaseController
{
    public function index()
    {
        $session = session();
        $errorMessage = $session->get('failed_to_login');
        $session->remove('failed_to_login');

        return view('frame', [
            'title' => 'ログイン',
            'section' => 'login',
            'errorMessage' => $errorMessage,
        ]);
    }

    public function authentication()
    {
        $boardId = $this->request->getPost('board_id');
        $session = session();
        $session->set('boardId', $boardId);

        $twitter = new TwitterOAuth();
        return $twitter->redirectAuthPage(base_url() . '/login/twitter');
    }

    public function callback()
    {
        $twitter = new TwitterOAuth();

        try {
            $parameters = $this->request->getGet();

            if (isset($parameters['denied'])) {
                log_message('info', "Twitter連携キャンセル: {$parameters['denied']}");
                $session = session();
                $session->set('failed_to_login', 'ログインできませんでした。');
                return redirect()->to("/login");
            }

            $oauthToken = $parameters['oauth_token'];
            $oauthVerfier = $parameters['oauth_verifier'];

            $twitter = new TwitterOAuth();
            $user = $twitter->callback($oauthToken, $oauthVerfier);

            if (!isset($user['user_id'])) {
                log_message('info', "Twitter連携失敗");
                $session = session();
                $session->set('failed_to_login', 'Twitter連携に失敗しました。');
                return redirect()->to("/login");
            }

            // ユーザー確認
            $usersModel = new UsersModel();
            $record = $usersModel->find($user['user_id']);
            if (empty($record)) {
                log_message('info', "ユーザー未登録: {$user['user_id']}");
                $session = session();
                $session->set('failed_to_login', 'ユーザー情報が見つかりません。新規登録をしてください。');
                return redirect()->to("/login");
            }

            //  掲示板確認
            $session = session();
            $boardId = $session->get('boardId');

            $boardsModel = new BoardsModel();
            $record = $boardsModel->where([
                'user_id' => $user['user_id'],
                'id' => $boardId
            ])->first();
            if (empty($record)) {
                log_message('info', "掲示板未登録: {$user['user_id']}, {$boardId}");
                $session = session();
                $session->set('failed_to_login', 'BBSが見つかりません。BBSID / 掲示板IDが正しいか確認してください。');
                return redirect()->to("/login");
            }

            $session = session();
            $session->set('user_id', $user['user_id']);

            return redirect()->to("/{$boardId}/admin");
        } finally {
            $twitter->removeSession();
        }
    }
}
