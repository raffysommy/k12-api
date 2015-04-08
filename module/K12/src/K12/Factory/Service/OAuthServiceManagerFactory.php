<?php

namespace K12\Factory\Service;

use K12\Service\OAuthServiceManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class OAuthServiceManagerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new OAuthServiceManager('http://mysql-raffysommy-1.c9.io', 'student-app', 'student-app-pw');
    }
}

?>