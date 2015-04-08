<?php
namespace K12\Factory\Form;

use K12\Form\Login;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Form\LoginFilter;
use ZfcUser\Options;

class LoginFormFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceManager)
    {
        /* @var $options Options\ModuleOptions */
        $options = $serviceManager->get('zfcuser_module_options');

        $inputFilter = new LoginFilter($options);

        $form = new Login(null, $options);
        $form->setInputFilter($inputFilter);

        return $form;
    }
}
