<?php

namespace Application\Controller;

use Application\Mapper\UserMapper;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Application\Entity\User;
use Zend\Crypt\Password\Bcrypt;

class UserController extends AbstractActionController
{
	protected $bcryptCost = 10;
	
    public function infoAction()
    {
        if ($token = $this->params()->fromPost('access_token')) {
            $mapper = new UserMapper($this->getServiceLocator()->get('ZendDbAdapter'));
            $user = $mapper->getInfoByToken($token);
            $result = $user->current()->toArray();
            unset($result['password']);
            return new JsonModel($result);
        }
    }
    
    public function outAction()
    {
        $token = $this->params()->fromPost('access_token');
        $mapper = new UserMapper($this->getServiceLocator()->get('ZendDbAdapter'));
        $user = $mapper->deleteOAuthAccess($token);
        return new JsonModel(array('Message' => 'User successfully signed out'));
    }
    
    public function registerAction()
    {
    	if ($jsonUser = $this->params()->fromPost('User')) {
    		$userArray = json_decode($jsonUser, true);
    		$pwCrypt = new Bcrypt();
    		$pwCrypt->setCost($this->getBcryptCost());
    		$user = new User(array(
    			'username' => $userArray['user'],
    			'password' => $pwCrypt->create($userArray['password']),
    			'email' => $userArray['email'],
    			'firstName' => $userArray['name'],
    			'lastName' => $userArray['surname'],
    			'school' => $userArray['school']
    		));
    		$mapper = new UserMapper($this->getServiceLocator()->get('ZendDbAdapter'));
    		$mapper->save($user);
            $this->regmail($userArray);
    		return new JsonModel(array('success' => true, 'messagge' => 'User successfully registered'));
    	}
    	else
    		return new JsonModel(array('success' => false, 'messagge' => 'No user data provided'));
    }
    
    public function getBcryptCost()
    {
    	return $this->bcryptCost;
    }
    
    public function setBcryptCost($cost)
    {
    	$this->bcryptCost = $cost;
    	return $this;
    }
    
    public function regmail($userArray) {
            $url = 'https://api.sendgrid.com/';
            $username = 'chZe8myXW0';
            $password = 'KZbrejGKpo1Z7215';
            $request =  $url.'api/mail.send.json';
            $emailto=$userArray['email'];/*
            $template = file_get_contents("public/template.html");
            $template = str_replace('%name%', $userArray['name'], $template);
            $template = str_replace('%surname%',$userArray['surname'], $template);
            $template = str_replace('%user%', $userArray['user'], $template);
            $template = str_replace('%pass%', $userArray['password'], $template);*/
            $template = '<h1>Hi, '.$userArray['name'].' '.$userArray['surname'].'</h1>
                        <p class="lead">You have been registered to K12-Educationet Android App</p>
                        <p>Enjoy our application for learn everyday the math.</p>
                        <p>Credential:</p>
                        <p>Username: '.$userArray['user'].' <p>
                        <p>Password: '.$userArray['password'].' <p>
                        </p>';
            // Generate curl request
            $session = curl_init($request);
            $params = array(
                'api_user'  => $username,
                'api_key'   => $password,
                'to'        => $emailto,
                'subject'   => 'Educationet App Registration',
                'html'      => $template,
                'x-smtpapi' => '{
                                  "filters": {
                                    "templates": {
                                      "settings": {
                                        "enable": 1,
                                        "template_id": "1d975e78-f5a9-45b8-9037-d315b2b1c859"
                                      }
                                    }
                                  }
                                }',
                'from'      => 'admin@educationet.tk',
            );
            // Tell curl to use HTTP POST
            curl_setopt ($session, CURLOPT_POST, true);
            // Tell curl that this is the body of the POST
            curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
            // Tell curl not to return headers, but do return the response
            curl_setopt($session, CURLOPT_HEADER, false);
            // Tell PHP not to use SSLv3 (instead opting for TLS)
            curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
            curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
            
            // obtain response
            $response = curl_exec($session);
            curl_close($session);
            return($response);
        }
}
