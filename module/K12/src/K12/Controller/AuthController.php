<?php

namespace K12\Controller;

use K12\Model\User;
use K12\Model\UserMapper;

use Zend\Db\Adapter\Adapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class AuthController extends AbstractActionController
{
    public function startAction()
    {
        $k12Api = $this->getServiceLocator()->get('k12-api');
        $k12Api->login('root', 'rootroot');
        print_r($k12Api->getAccessToken());
        $this->redirect()->toRoute('home');
    }
}

?>