<?php

namespace App\Libraries;

class TwitterOAuth
{
    private $_connection;

    public function __construct(?string $oauthToken = null, ?string $oauthTokenSecret = null)
    {
        $session = session();
        if ($oauthToken === null) {
            $oauthToken = $session->get('twitterOAuth_oauthToken');
        }
        if ($oauthTokenSecret === null) {
            $oauthTokenSecret = $session->get('twitterOAuth_oauthTokenSecret');
        }

        $consumerKey = getenv('twitter.consumerKey');
        $consumerSecret = getenv('twitter.consumerSecret');

        $this->_connection = new \Abraham\TwitterOAuth\TwitterOAuth($consumerKey, $consumerSecret, $oauthToken, $oauthTokenSecret);
    }

    public function removeSession()
    {
        $session = session();
        $session->remove('twitterOAuth_oauthToken');
        $session->remove('twitterOAuth_oauthTokenSecret');
    }

    public function redirectAuthPage(string $callbackUrl)
    {
        try {
            $resToken = $this->requestToken($callbackUrl);

            $session = session();
            $session->set('twitterOAuth_oauthToken', $resToken['oauth_token']);
            $session->set('twitterOAuth_oauthTokenSecret', $resToken['oauth_token_secret']);

            $authorizeUrl = $this->authorizeUrl($resToken['oauth_token']);
            return redirect()->to($authorizeUrl);
        } catch (\Abraham\TwitterOAuth\TwitterOAuthException $e) {
            $this->removeSession();
            throw $e;
        }
    }

    public function callback(string $oauthToken, string $oauthVerifier)
    {
        $session = session();
        $oauthTokenForSession = $session->get('twitterOAuth_oauthToken');

        $accessToken = $this->accessToken($oauthVerifier);
        log_message('debug', print_r($accessToken, true));

        return $accessToken;
    }

    public function requestToken(string $url)
    {
        return $this->_connection->oauth('oauth/request_token', ['oauth_callback' => $url]);
    }

    public function authorizeUrl(string $oauthToken)
    {
        return $this->_connection->url('oauth/authenticate', ['oauth_token' => $oauthToken]);
    }

    public function accessToken(string $oauthVerifier)
    {
        return $this->_connection->oauth("oauth/access_token", ["oauth_verifier" => $oauthVerifier]);
    }
}
