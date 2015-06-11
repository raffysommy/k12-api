<?php

namespace Application;

use OAuth2\Request as OAuth2Request;
use OAuth2\Server as OAuth2Server;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $sm  = $e->getApplication()->getServiceManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $eventManager->attach(MvcEvent::EVENT_ROUTE, function(MvcEvent $e) use ($sm) {
            $routeMatch = $e->getRouteMatch();
            $server = $sm->get('ZF\OAuth2\Service\OAuth2Server');
            $controllerName = $routeMatch->getParams()['controller'];
            $actionName=$routeMatch->getParams()['action'];
            $controllerExclude=($actionName=='register' && $controllerName == 'Application\\Controller\\User');
            $moduleName = substr($controllerName,0,strpos($controllerName,'\\'));
            if ($moduleName == 'Application' && !$controllerExclude) {
                if (!$server->verifyResourceRequest(OAuth2Request::createFromGlobals())) {
                    $response   = $server->getResponse();
                    $parameters = $response->getParameters();
                    $errorUri   = isset($parameters['error_uri']) ? $parameters['error_uri'] : null;
                    return new ApiProblemResponse(
                        new ApiProblem(
                            $response->getStatusCode(), null, null, null
                        )
                    );
                }
            }
        });
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    'Sorus' => __DIR__ . '/../../vendor/Sorus/lib'
                ),
            ),
        );
    }
}
