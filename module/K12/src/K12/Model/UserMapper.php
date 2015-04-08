<?php

namespace K12\Model;

use K12\Model\User;
use Sorus\StdMapper;
use Zend\Db\Adapter\Adapter;

class UserMapper extends StdMapper
{
    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter);
    }
    
    public function getPermissions(User $user)
    {
        $result = $this->getAdapter()->query(
            'SELECT nome, cognome, scuola, permissions FROM utenti WHERE Username=\''.$user->username.'\' and password=\''.$user->password.'\'',
            Adapter::QUERY_MODE_EXECUTE);
        return $result->current();
    }
}

?>