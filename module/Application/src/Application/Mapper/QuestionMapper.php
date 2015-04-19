<?php

namespace Application\Mapper;

use Application\Entity\Question;
use Application\Entity\Questionnaire;
use Application\Entity\Score;
use Sorus\StdMapper;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Expression;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator\ClassMethods;

class QuestionMapper extends StdMapper
{
    protected $tableGateway;
    protected $tableName = 'questions';
    
    
    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter);
    }
    
    public function fetchByTopics(array $topics)
    {
        if (count($topics) == 0)
            return $this->fetchAll();
        $select = $this->getTableGateway()->getSql()->select();
        $select->join('topics',
                      'name=topic',
                      array());
        $whereClause = '';
        foreach($topics as $index => $topic) {
            $whereClause .= 'topic = \''.$topic.'\'';
            if (isset($topics[$index+1]))
                $whereClause .= ' OR ';
        }
        $select->where($whereClause);
        return $this->getTableGateway()->selectWith($select);
    }
    
    public function fetchAll()
    {
        return $this->getTableGateway()->select();
    }
    
    public function fetchByQuestionnaire(Questionnaire $questionnaire)
    {
        $select = $this->getTableGateway()->getSql()->select();
        $select->join('questions_questionnaires', 'question='.$this->tableName.'.id', array())
               ->where(array('questionnaire' => $questionnaire->id));
        return $this->getTableGateway()->selectWith($select);
    }
    
    public function fetchRandom($limit = null, array $topics = array())
    {
        $select = $this->getTableGateway()->getSql()->select();
        if (count($topics) != 0) {
            $select->join('topics',
                          'name=topic',
                          array());
            $whereClause = '';
            foreach($topics as $index => $topic) {
                $whereClause .= 'topic = \''.$topic.'\'';
                if (isset($topics[$index+1]))
                    $whereClause .= ' OR ';
            }
            $select->where($whereClause);
        }
        if ($limit)
            $select->limit($limit);
        $rand = new Expression('RAND()');
        $select->order($rand);
        return $this->getTableGateway()->selectWith($select);
    }
    
    public function insertScores(array $scores)
    {
        $scoresTableGateway = new TableGateway(
                'scores',
                $this->getAdapter(),
                null,
                new HydratingResultSet(
                    new ClassMethods,
                    new Score()
                )
            );
        foreach ($scores as $score) {
            if (!$score instanceof Score)
                throw new \InvalidArgumentException('Valid Score not provided');
            $scoresTableGateway->insert($score->toRelationalTable());
        }
    }
    
    public function save(Question $question)
    {
        if (!$question->id)
            $this->getTableGateway()->insert($question->toArray());
        else
            $this->getTableGateway()->update($question->toArray(), array('id' => $question->id));
    }
    
    public function delete($id)
    {
        $this->getTableGateway()->delete(array('id' => $id));
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
                    new Question()
                )
            );
            return $this->tableGateway;
        }
    }
    
    public function setTableGateway(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
        return $this;
    }
}

?>