<?php

namespace Application\Mapper;

use Application\Entity\Question;
use Application\Entity\Questionnaire;
use Application\Mapper\QuestionMapper;
use Sorus\StdMapper;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator\ClassMethods;

class QuestionnaireMapper extends StdMapper
{
    protected $tableGateway;
    protected $tableName = 'questionnaires';
    protected $questionsCrossTable = 'questions_questionnaires';
    
    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter);
    }
    
    public function fetchAll()
    {
        return $this->getTableGateway()->select();
    }
    
    public function addQuestions(Questionnaire $questionnaire, array $questions)
    {
        if(!empty($questions)) {
            $this->getAdapter()->getDriver()->getConnection()->beginTransaction();
            $this->save($questionnaire);
            $crossTable = new TableGateway($this->questionsCrossTable, $this->getAdapter());
            $insertTemplate = array('questionnaire' => $questionnaire->id);
            foreach ($questions as $one) {
                if (!$one instanceof Question)
                    throw new \InvalidArgumentException('Invalid question');
                $insertTemplate['question'] = $one->id;
                $crossTable->insert($insertTemplate);
            }
            return $this->getAdapter()->getDriver()->getConnection()->commit();
        }
    }
    
    public function fetchById($id)
    {
        $questionnaireResultSet = $this->getTableGateway()->select(array('id' => $id));
        if (!$questionnaireResultSet->count())
            return false;
        else
            $questionnaire = $questionnaireResultSet->current();
        $questionMapper = new QuestionMapper($this->getAdapter());
        $questionResultSet = $questionMapper->fetchByQuestionnaire($questionnaire);
        while ($question = $questionResultSet->current()) {
            $questionnaire->addQuestion($question);
            $questionResultSet->next();
        }
        return $questionnaire;
    }
    
    public function save(Questionnaire $questionnaire)
    {
        if (!$questionnaire->id)
            return $this->getTableGateway()->insert($questionnaire->toArray());
        else
            return $this->getTableGateway()->update($questionnaire->toArray(), array('id' => $questionnaire->id));
    }
    
    public function delete($id)
    {
        $this->getTableGateway()->delete(array('id' => $id));
    }
    
    public function setTableGateway(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
        return $this;
    }
    
    public function getTableGateway()
    {
        if ($this->tableGateway instanceof TableGateway)
            return $this->tableGateway;
        else {
             $this->tableGateway = new TableGateway(
                $this->tableName,
                $this->getAdapter(),
                null,
                new HydratingResultSet(
                    new ClassMethods,
                    new Questionnaire
                )
            );
            return $this->tableGateway;
        }
    }
}

?>