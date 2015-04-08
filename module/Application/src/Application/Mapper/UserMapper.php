<?php

namespace Application\Mapper;

use Application\Entity\User;
use Application\Mapper\UserHydrator;
use Sorus\StdMapper;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;

class UserMapper extends StdMapper
{
    protected $tableGateway;
    
    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter);
    }
    
    public function getInfoByToken($token)
    {
        $select = $this->getTableGateway()->getSql()->select();
        $select->join('oauth_access_tokens','oauth_users.user_id=oauth_access_tokens.user_id')
               ->columns(array('user_id','username','first_name','last_name','email','role'))
               ->where('access_token = \''.$token.'\'');
        $result = $this->getTableGateway()->selectWith($select);
        return $result;
    }
    
    public function getTableGateway()
    {
        if ($this->tableGateway instanceof TableGateway)
            return $this->tableGateway;
        else {
             $this->tableGateway = new TableGateway(
                'oauth_users',
                $this->getAdapter(),
                null,
                new HydratingResultSet(
                    new UserHydrator(),
                    new User()
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