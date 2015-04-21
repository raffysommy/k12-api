<?php

return array(
    'router' => array(
        'routes' => array(
            'api' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/api',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
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
    	),
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Question' => 'Application\Controller\QuestionController',
            'Application\Controller\User' => 'Application\Controller\UserController',
            'Application\Controller\Topic' => 'Application\Controller\TopicController',
            'Application\Controller\Questionnaire' => 'Application\Controller\QuestionnaireController',
        	'Application\Controller\Score' => 'Application\Controller\ScoreController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    	'strategies' => array(
    		'ViewJsonStrategy',
    	),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
