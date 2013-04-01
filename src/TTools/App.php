<?php

namespace TTools;

use \TTools\TTools;

class App implements TToolsApp {

    private $ttools;
    private $default_storage;
    private $current_user;

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

    public function getUser()
    {
        if ($this->ttools->getState()) {
            return $ttools->makeRequest('/' . TTools::API_VERSION .'/users/show.json',array("screen_name"=>$this->current_user));
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

	function storeRequestSecret($user_id, $request_secret)
	{
        switch ($this->default_storage) {
            case self::TT_STORAGE_DB:
                break;

            case self::TT_STORAGE_FILE:
                break;

            case self::TT_STORAGE_SESSION:
            default:
                /* SESSION storage is default */
                $_SESSION['last_secret'] = $request_secret; 

        }
	}

    function getRequestSecret()
    {
        switch ($this->default_storage) {
            case self::TT_STORAGE_DB:
                break;

            case self::TT_STORAGE_FILE:
                break;

            case self::TT_STORAGE_SESSION:
            default:
                /* SESSION storage is default */
                return $_SESSION['last_secret'];

        }
    }

}