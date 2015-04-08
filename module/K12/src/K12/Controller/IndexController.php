<?php

namespace K12\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
    
    public function testAction()
    {
        $k12Service = $this->getServiceLocator()->get('K12-Service');
        $k12Service->login('root', 'rootroot');
        return new ViewModel();
    }
}

?>