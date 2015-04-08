<?php

namespace K12\Model;

use Sorus\StdEntity;

class User extends StdEntity
{
    protected $username;
    protected $password;
    protected $permissions;
    
    public function getUsername()
    {
        return $this->username;
    }
    
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }
    
    public function getPassword()
    {
        return $this->password;
    }
    
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }
    
    public function getPermissions()
    {
        return $this->permissions;
    }
    
    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;
        return $this;
    }
}

?>