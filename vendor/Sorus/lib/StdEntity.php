<?php

namespace Sorus;

class StdEntity
{
	/* Metodo costruttore */
	public function __construct(array $options = NULL)
	{
		if (is_array($options))
		{
			$this->setOptions($options);
		}
	}

	/* Getters and Setters */
	public function __get ($name)
	{
		$method = 'get'.$name;
		if ($name == 'mapper' || !method_exists($this, $method))
		{
			throw new \InvalidArgumentException('Invalid '.__CLASS__.' property');
		}
		return $this->$method();
	}

	public function __set ($name, $value)
	{
		$method = 'set'.$name;
		if ($name == 'mapper' || !method_exists($this, $method))
		{
			throw new \InvalidArgumentException('Invalid '.__CLASS__.' property');
		}
		$this->$method($value);
	}

	public function setOptions (array $options)
	{
		$methods = get_class_methods($this);
		foreach ($options as $key => $value)
		{
			$method = 'set'.ucfirst($key);
			if (in_array($method, $methods))
			{
				$this->$method($value);
			}
		}
		return $this;
	}
	
	public function toArray()
	{
		return get_object_vars($this);
	}
}

?>