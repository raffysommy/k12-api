<?php

namespace Application\Mapper;

use Application\Entity\Topic;
use Sorus\StdMapper;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator\ClassMethods;

class TopicMapper extends StdMapper
{
    protected $tableGateway;
    
    public function fetchAll()
    {
        return $this->getTableGateway()->select();
    }
    
    public function getTableGateway()
    {
        if ($this->tableGateway instanceof TableGateway)
            return $this->tableGateway;
        else {
             $this->tableGateway = new TableGateway(
                'topics',
                $this->getAdapter(),
                null,
                new HydratingResultSet(
                    new ClassMethods,
                    new Topic()
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