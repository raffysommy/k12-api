<?php

namespace Application\Mapper;

use Zend\Stdlib\Hydrator\ClassMethods;

class UserHydrator extends ClassMethods
{
    public function extract($object)
    {
        $data = parent::extract($object);
        return $this->mapField('id', 'user_id', $data);
    }
    
    public function hydrate(array $data, $object)
    {
        $data = $this->mapField('user_id', 'id', $data);
        return parent::hydrate($data, $object);
    }
    
    protected function mapField($keyFrom, $keyTo, array $array)
    {
        if (isset($array[$keyFrom])) {
            $array[$keyTo] = $array[$keyFrom];
        }
        unset($array[$keyFrom]);
        return $array;
    }
}

?>