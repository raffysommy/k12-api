<?php

namespace Application\Entity;

use Sorus\StdEntity;

class Topic extends StdEntity
{
	protected $name;
	protected $description;

	public function getName()
	{
		return $this->name;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function setName($value)
	{
		$this->name = $value;
		return $this;
	}

	public function setDescription($value)
	{
		$this->description = $value;
		return $this;
	}

}

?>