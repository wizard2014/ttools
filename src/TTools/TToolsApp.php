<?php
namespace TTools;

use \TTools\TTools;

abstract class TToolsApp {
    
    protected $ttools;
    protected $default_storage;
    protected $current_user;

    const TT_STORAGE_SESSION = 1;
    const TT_STORAGE_FILE    = 2;
    const TT_STORAGE_DB      = 3;

    public function __construct(array $config, int $storage = null) 
    {
        $this->ttools = new TTools($config);
        if ($storage !== null)
            $this->default_storage = $storage;
        else
            $this->default_storage = self::TT_STORAGE_SESSION;

        $this->current_user = $this->getUser();
    }

    public function isLogged()
    {
        return count($this->current_user);
    }

    public function getLoginUrl()
    {
        $result = $this->ttools->getAuthorizeUrl();
        print_r($result);
        $this->storeRequestSecret($result['token'], $result['secret']);
       
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
        if ($this->ttools->getState()) {
            return $this->ttools->makeRequest('/' . TTools::API_VERSION .'/account/verify_credentials.json');
        } else {
                /* check if there is a user comming from auth page on twitter */
            $user = array();
            if (!empty($_REQUEST['oauth_token'])) {

                $secret = $this->getRequestSecret();
                $user = $this->ttools->getAccessTokens($_REQUEST['oauth_token'], $secret);
              
                if (!empty($user['access_token'])) {
                    /* congratulations, you have successfully logged in */
                    $this->current_user = $user;
                }
            }
        }
        return $user;
    }

    public function getTimeline($limit = 10)
    {
        return $this->ttools->makeRequest('/' . self::API_VERSION .'/statuses/home_timeline.json',array("count"=>$limit));
    }
    
    public function getUserTimeline($user_id = null, $screen_name = null, $limit = 10)
    {
        return $this->ttools->makeRequest(
            '/' . self::API_VERSION .'/statuses/user_timeline.json',
            array(
                "count"=>$limit,
                "user_id"  => $user_id,
                "screen_name" => $screen_name,
            )
        );
    }
    
    public function getMentions($limit = 10)
    {
        return $this->ttools->makeRequest('/' . self::API_VERSION .'/statuses/mentions_timeline.json',array("count"=>$limit));
    }
    
    public function getFavorites($limit = 10)
    {
        return $this->ttools->makeRequest('/' . self::API_VERSION .'/favorites/list.json',array("count"=>$limit));
    }

    /* MUST EXTEND AND IMPLEMENT */	
    abstract protected function storeRequestSecret($request_token, $request_secret);

    abstract protected function getRequestSecret();
}