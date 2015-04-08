<?php

namespace K12\Controller;

use Application\Entity\Question;
use Application\Mapper\QuestionMapper;
use Zend\Db\Adapter\Adapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class QuestionController extends AbstractActionController
{
    public function indexAction()
    {
        $message = $this->params()->fromRoute('message');
        $mapper = new QuestionMapper($this->getServiceLocator()->get('ZendDbAdapter'));
        return new ViewModel(array('questions' => $mapper->fetchAll(), 'message' => $message));
    }
    
    public function createAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $question = new Question($request->getPost()->toArray());
            $mapper = new QuestionMapper($this->getServiceLocator()->get('ZendDbAdapter'));
            $mapper->save($question);
        }
        return $this->redirect()->toRoute('question', array('message' => 'createSuccess'));
    }
    
    public function listAction()
    {
        
    }
}
