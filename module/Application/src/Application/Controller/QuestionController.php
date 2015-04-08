<?php

namespace Application\Controller;

use Application\Entity\Question;
use Application\Entity\Score;
use Application\Mapper\QuestionMapper;
use Application\Mapper\UserMapper;
use Zend\Db\Adapter\Adapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class QuestionController extends AbstractActionController
{
    public function randomAction()
    {
        $adapter = $this->getServiceLocator()->get('ZendDbAdapter');
        $mapper = new QuestionMapper($this->getServiceLocator()->get('ZendDbAdapter'));
        $topics = json_decode($this->params()->fromPost('topics'));
        if (!isset($topics))
            $topics = array();
        $result = $mapper->fetchRandom(1,$topics);
        return new JsonModel($result->current()->toArray());
    }
    
    public function listAction()
    {
        $by = $this->params()->fromPost('by');
        if ($by == 'topic') {
            $topics = json_decode($this->params()->fromPost('topics'));
            $mapper = new QuestionMapper($this->getServiceLocator()->get('ZendDbAdapter'));
            $result = $mapper->fetchByTopics($topics);
        }
        return new JsonModel($result);
    }
    
    public function scoreAction()
    {
        $jsonScores = $this->params()->fromPost('scores');
        $inputScores = json_decode($jsonScores);
        if (!$inputScores) {
            return new JsonModel(array('error' => 'No data provided'));
        }
        $mapper = new UserMapper($this->getServiceLocator()->get('ZendDbAdapter'));
        $scorePrototype = new Score(
            array('user' => $mapper->getInfoByToken($this->params()->fromPost('access_token'))->current())
        );
        $scores = array();
        foreach ($inputScores as $inputScore) {
            $score = clone $scorePrototype;
            $score->setQuestion(new Question(array('id' => $inputScore[0])))
                  ->setResult($inputScore[1])
                  ->setDate($inputScore[2]);
            $scores[] = $score;
        }
        $questionMapper = new QuestionMapper($this->getServiceLocator()->get('ZendDbAdapter'));
        $questionMapper->insertScores($scores);
        return new JsonModel(array('success' => true, 'message' => 'Scores added'));
    }
}