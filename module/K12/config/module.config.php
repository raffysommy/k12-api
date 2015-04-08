<?php

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'K12\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'question' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/question[/:action[/:message]]',
                    'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'message'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                    'defaults' => array(
                        'controller' => 'K12\Controller\Question',
                        'action'     => 'index',
                    ),
                ),
            ),
            'startK12Session' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/auth/start',
                    'defaults' => array(
                        'controller' => 'K12\Controller\Auth',
                        'action'     => 'start',
                    ),
                ),
            ),
            'test' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/K12',
                    'defaults' => array(
                        '__NAMESPACE__' => 'K12\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'K12\Controller\Index' => 'K12\Controller\IndexController',
            'K12\Controller\Question' => 'K12\Controller\QuestionController',
            'K12\Controller\Auth' => 'K12\Controller\AuthController'
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'ZendDbAdapter' => function ($sm) {
    			$config = $sm->get('Config');
    			$dbParams = $config['db'];
    			return new Zend\Db\Adapter\Adapter(array(
    				'driver' => 'pdo',
    				'dsn' => 'mysql:dbname='.$dbParams['database'].';host='.$dbParams['hostname'],
    				'database' => $dbParams['database'],
    				'username' => $dbParams['username'],
    				'password' => $dbParams['password'],
    				'hostname' => $dbParams['hostname']
    			));	
    		},
    		'zfcuser_login_form' => 'K12\Factory\Form\LoginFormFactory',
    		'k12-api' => 'K12\Factory\Service\OAuthServiceManagerFactory' 
        ),
        'aliases' => array(
            'Zend\Db\Adapter\Adapter' => 'ZendDbAdapter'    
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
            'zfcuser' => __DIR__ . '/../view',
        ),
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'user/login' => __DIR__ . '/../view/layout/login.phtml'),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    )
);
