<?php

namespace Application\Controller;

use Application\Mapper\UserMapper;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
    
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
}
