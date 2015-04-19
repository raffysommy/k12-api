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
               ->columns(array('user_id','username','first_name','last_name','email','role','school'))
               ->where('access_token = \''.$token.'\'');
        $result = $this->getTableGateway()->selectWith($select);
        return $result;
    }
    
    public function deleteOAuthAccess($token)
    {
        $this->getAdapter()->getDriver()->getConnection()->beginTransaction();
        $user = $this->getInfoByToken($token)->current();
        $accessTokenTable = new TableGateway('oauth_access_tokens', $this->getAdapter());
        $accessTokenTable->delete(array('user_id' => $user->id));
        $refreshTokenTable = new TableGateway('oauth_refresh_tokens', $this->getAdapter());
        $refreshTokenTable->delete(array('user_id' => $user->id));
        $this->getAdapter()->getDriver()->getConnection()->commit();
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