<?php

namespace Sorus;

class StdMapper
{
    protected $adapter;
    
    public function __construct($adapter)
    {
        $this->setAdapter($adapter);
    }
    
    public function getAdapter()
    {
        return $this->adapter;
    }
    
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }
}

?>