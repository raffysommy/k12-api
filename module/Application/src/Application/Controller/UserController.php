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
    		include_once 'public/SendMail.php';
            regmail($userArray);
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
}
