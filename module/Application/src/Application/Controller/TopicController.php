<?php

namespace Application\Controller;

use Application\Mapper\TopicMapper;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class TopicController extends AbstractActionController
{
    public function listAction()
    {
        $mapper = new TopicMapper($this->getServiceLocator()->get('ZendDbAdapter'));
        $topics = $mapper->fetchAll();
        $jsonResult = array();
        while ($topic = $topics->current()) {
            array_push($jsonResult, $topic->toArray());
            $topics->next();
        }
        return new JsonModel($jsonResult);
    }
}

?>