<?php

namespace TTools;

use \TTools\TTools;
use \TTools\StorageProvider;

class App {

    private $storage;
    private $ttools;
    private $current_user;

    public function __construct(array $config, StorageProvider $sp = null)
    {
        if ($sp !== null)
            $this->storage = $sp;
        else
            $this->storage = new \TTools\StorageSession();

        if (!isset($config['access_token'])) {
            /* check if theres a logged user in session */
            $user = $this->storage->getLoggedUser();
            if (!empty($user['access_token'])) {
                $config['access_token']        = $user['access_token'];
                $config['access_token_secret'] = $user['access_token_secret'];
            }
        }

        $this->ttools = new TTools($config);

        $this->current_user = $this->getCredentials();
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

    public function getCredentials()
    {
        $user = array();
        if ($this->ttools->getState()) {
            $user = $this->ttools->makeRequest(
                '/' . TTools::API_VERSION .'/account/verify_credentials.json',
                array('include_entities' => false, 'skip_status' => true)
            );
        } else {

            if (!empty($_REQUEST['oauth_verifier'])) {

                $secret = $this->storage->getRequestSecret();

                $user = $this->ttools->getAccessTokens($_REQUEST['oauth_token'], $secret, $_REQUEST['oauth_verifier']);

                if (!empty($user['access_token'])) {
                    $this->storage->storeLoggedUser($user);
                    $user = $this->ttools->makeRequest('/' . TTools::API_VERSION .'/account/verify_credentials.json');
                }
            }
        }

        return $user;
    }

    public function get($path, $params)
    {
        return $this->ttools->makeRequest('/' . TTools::API_VERSION . $path, $params);
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