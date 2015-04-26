<?php

namespace Application\Controller;

use Application\Entity\Questionnaire;
use Application\Mapper\QuestionnaireMapper;
use Zend\Db\Adapter\Adapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Application\Entity\Question;

class QuestionnaireController extends AbstractActionController
{
    public function listAction()
    {
        $questionnaireMapper = new QuestionnaireMapper($this->getServiceLocator()->get('ZendDbAdapter'));
        $questionnaires = $questionnaireMapper->fetchAll();
        $arrayResult = array();
        while ($questionnaire = $questionnaires->current()) {
            $arrayResult[] = $questionnaire->toArray();
            $questionnaires->next();
        }
        return new JsonModel($arrayResult);
    }
    
    public function createAction()
    {
        $jsonQuestionnaire = json_decode($this->params()->fromPost('questionnaire'), true);
        if (!isset($jsonQuestionnaire['name']))
            return new JsonModel(array('success' => false, 'error' => 'No questionnaire provided'));
        $questionnaire = new Questionnaire($jsonQuestionnaire);
        $questionnaireMapper = new QuestionnaireMapper($this->getServiceLocator()->get('ZendDbAdapter'));
        $questionnaireMapper->save($questionnaire);
        return new JsonModel(array('success' => true, 'message' => 'Questionnaire successfully added'));
    }
    
    public function deleteAction()
    {
        $id = $this->params()->fromPost('id');
        if (!$id)
            return new JsonModel(array('success' => false, 'message' => 'No ID provided'));
        else if (!is_int((int) $id))
            return new JsonModel(array('success' => false, 'message' => 'Provided ID isn\' a valid ID'));
        $mapper = new QuestionnaireMapper($this->getServiceLocator()->get('ZendDbAdapter'));
        $mapper->delete($id);
        return new JsonModel(array('success' => true, 'message' => 'Questionnaire successfully deleted'));
    }
    
    public function viewAction()
    {
        $id = $this->params()->fromPost('id');
        if (!$id)
            return new JsonModel(array('success' => false, 'message' => 'No ID provided'));
        else if (!is_int((int) $id))
            return new JsonModel(array('success' => false, 'message' => 'Provided ID isn\' a valid ID'));
        $mapper = new QuestionnaireMapper($this->getServiceLocator()->get('ZendDbAdapter'));
        $questionnaire = $mapper->fetchById($id);
        if (!$questionnaire)
            return new JsonModel(array('success' => false, 'message' => 'Questionnaire not found'));
        return new JsonModel($questionnaire->toArray());
    }
    
    public function assignQuestionsAction()
    {
    	$jsonQuestionnaire = $this->params()->fromPost('questionnaire');
    	if (!$jsonQuestionnaire)
    		return new JsonModel(array('success' => false, 'message' => 'No Questionnaire provided'));
    	
    	$jsonQuestions = $this->params()->fromPost('questions');
    	if (!$jsonQuestions)
    		return new JsonModel(array('success' => false, 'message' => 'No Question provided'));
    	
    	$questionnaire = new Questionnaire(array('id' => json_decode($jsonQuestionnaire, true)));
    	
    	$questions = array();
    	foreach (json_decode($jsonQuestions, true) as $one)
    		array_push($questions, new Question(array('id' => $one)));
    	
    	$mapper = new QuestionnaireMapper($this->getServiceLocator()->get('ZendDbAdapter'));
    	$mapper->addQuestions($questionnaire, $questions);
    	
    	return new JsonModel(array('success' => true, 'message' => 'Questions successfully assigned'));
    }
}

?>