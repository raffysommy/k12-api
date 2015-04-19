<?php

namespace Application\Controller;

use Application\Mapper\UserMapper;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController
{
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
}
