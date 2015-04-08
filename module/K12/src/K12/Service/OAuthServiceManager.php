<?php

namespace K12\Service;

use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Session\Container;

class OAuthServiceManager extends Client
{
    const SESSION_CONTAINER_NAME = 'K12';
    
    protected $accessToken;
    protected $refreshToken;
    protected $baseUrl;
    protected $clientId;
    protected $clientSecret;
    protected $expire;
    protected $sessionContainer;
    
    public function __construct($baseUrl, $clientId, $clientSecret)
    {
        $this->baseUrl = $baseUrl;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->setMethod(Request::METHOD_POST);
        $this->sessionContainer = new Container($this::SESSION_CONTAINER_NAME);
    }
    
    public function login($username, $password)
    {
        if (!$this->getExpire()) {
            $this->setUri($this->baseUrl.'/oauth');
            $this->setParameterPost(array(
                'grant_type' => 'password',
                'username'  => $username,
                'password'   => $password,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret
            ));
            $response = $this->send();
            $json = json_decode($response->getContent());
            $this->setAccessToken($json->access_token);
            $this->setRefreshToken($json->refresh_token);
            $this->setExpire(time()+$json->expires_in);
        } else {
            $this->relog();
        }
    }
    
    public function sendRequest($uri, array $params)
    {
        $params['access_token'] = $this->getAccessToken();
        $this->setUri($uri);
        $this->setParameterPost($params);
        return $this->send();
    }
    
    public function relog()
    {
        if ($this->getExpire() <= time()) {
            $this->setUri($this->baseUrl.'/oauth');
            $this->setParameterPost(array(
                'grant_type' => 'refresh_token',
                'refresh_token' => $this->getRefreshToken(),
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret
            ));
            $response = $this->send();
            $json = json_decode($response->getContent());
            $this->setAccessToken($json->access_token);
            $this->setExpire(time()+$json->refresh_token);
        }
    }
    
    public function logout()
    {
        
    }
    
    public function getAccessToken()
    {
        if (!$this->accessToken) {
            $this->setAccessToken($this->sessionContainer->accessToken);
        }
        return $this->accessToken;
    }
    
    public function setAccessToken($token)
    {
        $this->accessToken = $token;
        $this->sessionContainer->accessToken = $token;
        return $this;
    }
    
    public function getRefreshToken()
    {
        if (!$this->refreshToken) {
            $this->setRefreshToken($this->sessionContainer->refreshToken);
        }
        return $this->refreshToken;
    }
    
    public function setRefreshToken($token)
    {
        $this->refreshToken = $token;
        $this->sessionContainer->refreshToken = $token;
        return $this;
    }
    
    public function getExpire()
    {
        if (!$this->expire) {
            $this->expire = $this->sessionContainer->expire;
        }
        return $this->expire;
    }
    
    public function setExpire($expire)
    {
        $this->expire = $expire;
        $this->sessionContainer->expire = $expire;
        return $this;
    }
}

?>