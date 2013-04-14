<?php

namespace TTools;

use \TTools\TTools;
use \TTools\StorageProvider;

class App {

    private $storage;
    private $request;
    private $ttools;
    private $current_user;

    public function __construct(array $config, StorageProvider $sp = null, RequestProvider $rp = null)
    {
        if ($sp !== null)
            $this->storage = $sp;
        else
            $this->storage = new \TTools\StorageSession();

        if ($rp !== null)
            $this->request = $rp;
        else
            $this->request = new \TTools\RequestProvider();

        if (!isset($config['access_token'])) {
            /* check if theres a logged user in session */
            $user = $this->storage->getLoggedUser();
            if (!empty($user['access_token'])) {
                $config['access_token']        = $user['access_token'];
                $config['access_token_secret'] = $user['access_token_secret'];
            }
        }

        $this->ttools = new TTools($config);

        $this->current_user = $this->getUser();
    }

    public function isLogged()
    {
        return count($this->current_user);
    }

    public function getLoginUrl()
    {
        $result = $this->ttools->getAuthorizeUrl();
        $this->storage->storeRequestSecret($result['token'], $result['secret']);
       
        return $result['auth_url'];
    }

    public function getCurrentUser()
    {
        return $this->current_user;
    }

    public function getLastReqInfo()
    {
        return $this->ttools->getLastReqInfo();
    }

    public function getUser()
    {
        $user = array();
        if ($this->ttools->getState()) {
            $user = $this->getCredentials();
        } else {
            $oauth_verifier = $this->request->get('oauth_verifier');
            if ($oauth_verifier !== null) {

                $secret = $this->storage->getRequestSecret();
                $oauth_token = $this->request->get('oauth_token');
                $user = $this->ttools->getAccessTokens($oauth_token, $secret, $oauth_verifier);

                if (!empty($user['access_token'])) {
                    $this->storage->storeLoggedUser($user);
                    $user = $this->getCredentials();
                }
            }
        }

        return $user;
    }

    public function get($path, $params)
    {
        return $this->ttools->makeRequest('/' . TTools::API_VERSION . $path, $params);
    }

    public function getCredentials()
    {
        return $this->get('/account/verify_credentials.json',
            array('include_entities' => false, 'skip_status' => true)
        );
    }

    public function getTimeline($limit = 10)
    {
        return $this->get('/statuses/home_timeline.json',array("count"=>$limit));
    }
    
    public function getUserTimeline($user_id = null, $screen_name = null, $limit = 10)
    {
        return $this->get(
            '/statuses/user_timeline.json',
            array(
                "count"       => $limit,
                "user_id"     => $user_id,
                "screen_name" => $screen_name,
            )
        );
    }
    
    public function getMentions($limit = 10)
    {
        return $this->get('/statuses/mentions_timeline.json',array("count"=>$limit));
    }
    
    public function getFavorites($limit = 10)
    {
        return $this->get('/favorites/list.json',array("count"=>$limit));
    }    
}