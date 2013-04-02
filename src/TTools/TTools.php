<?php

namespace TTools;

class TTools
{
    private $consumer_key;
    private $consumer_secret;
    private $access_token;
    private $access_token_secret;
	private $state;
	
	private $last_req_info;

	const VERSION = '1.0.1-dev';
    const API_VERSION  = '1.1';
    const REQ_BASE = 'https://api.twitter.com';
    const AUTH_PATH    = '/oauth/authorize';
    const REQUEST_PATH = '/oauth/request_token';
    const ACCESS_PATH  = '/oauth/access_token';
    
    public function __construct(array $config)
    {
        $this->consumer_key = $config['consumer_key'];
        $this->consumer_secret = $config[ 'consumer_secret'];
        $this->access_token = null;
        $this->access_token_secret = null;
        $this->state = 0;
        $this->last_req_info = array();
        
        if (isset($config['access_token']) && isset($config['access_token_secret'])) {
            $this->access_token = $config['access_token'];
            $this->access_token_secret = $config['access_token_secret'];
            $this->state = 2;
           
            return;
        }
         
    }
	
    public function getState()
    {         
        return $this->state;
    }
	
    public function setState($state)
    {
        $this->state = $state;
    }
    
    public function setUserTokens($at, $ats)
    {
        $this->access_token = $at;
        $this->access_token_secret = $ats;
    }

    public function getAuthorizeUrl()
    {
    
        $result = $this->OAuthRequest(self::REQUEST_PATH);
           
        if ($result['code'] == 200) {
 
            $tokens = $this->parseResponse($result['response']);
           
            return array(
                'auth_url' => self::REQ_BASE . self::AUTH_PATH . '?oauth_token=' . $tokens['oauth_token'],
                'secret'   => $tokens['oauth_token_secret'],
            );
            
         } else {
            return 0;
         }

    }
    
    public function getAccessTokens($request_token,$request_secret)
    {
        $this->setUserTokens($request_token, $request_secret);
        
        $result = $this->OAuthRequest(self::ACCESS_PATH);
       
        if ($result['code'] == 200) {
            
            $tokens = $this->parseResponse($result['response']);       
            $this->setUserTokens($tokens['oauth_token'], $tokens['oauth_token_secret']);                    
            $this->setState(1);
            
            return array(
                'access_token'        => $this->access_token,
                'access_token_secret' => $this->access_token_secret,
                'screen_name'         => $tokens['screen_name'],
                'user_id'             => $tokens['user_id'],
            );
        }
    }
    
    private function parseResponse($string)
    {
        $r = array();
        foreach (explode('&', $string) as $param) {
            $pair = explode('=', $param, 2);
            if (count($pair) != 2) 
                continue;
            $r[urldecode($pair[0])] = urldecode($pair[1]);
        }
        return $r;
    }
    
    private function OAuthRequest($url, $params = array(), $method = 'GET', $callback = null)
    {
        $config = array(
            'consumer_key' => $this->consumer_key,
            'consumer_secret' => $this->consumer_secret,
            'user_token' => $this->access_token,
            'user_secret' => $this->access_token_secret,
            'user_agent'=> 'ttools ' . self::VERSION . ' - github.com/erikaheidi/ttools',
          );
        
        $oauth = new \tmhOAuth\tmhOAuth($config);
        
        $req = $oauth->request($method, $oauth->url($url), $params);

        if (!$req)
            return array('code' => "666");

        $this->last_req_info = array (
            'path' => $url,
            'response_code' => $oauth->response['code'],
        );
        
        if ($callback != null)
            $callback($oauth->response['code'],$oauth->response);
        else
            return $oauth->response;
    }
    
    public function makeRequest($url, $params = array(), $method = 'GET')
    {       
        $result = $this->OAuthRequest($url, $params, $method);
        if ($result['code'] == 200) {
        
        	return json_decode($result['response'],1);
        
        } else {
            $response = json_decode($result['response'],1);
            return array(
        	    'error' => $result['code'],
        	    'error_message' => $response['errors'][0]['message']
              
        	);
        }
    }
    
    public function getLastReqInfo()
    {
        return $this->last_req_info; 
    }
    
    public function getTimeline($limit = 10)
    {
    	return $this->makeRequest('/' . self::API_VERSION .'/statuses/home_timeline.json',array("count"=>$limit));
    }
    
    public function getUserTimeline($user_id = null, $screen_name = null, $limit = 10)
    {
        return $this->makeRequest(
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
    	return $this->makeRequest('/' . self::API_VERSION .'/statuses/mentions_timeline.json',array("count"=>$limit));
    }
    
    public function getFavorites($limit = 10)
    {
        return $this->makeRequest('/' . self::API_VERSION .'/favorites/list.json',array("count"=>$limit));
    }

}