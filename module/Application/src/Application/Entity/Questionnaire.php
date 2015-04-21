<?php

namespace Application\Entity;

use Sorus\StdEntity;

class Questionnaire extends StdEntity
{
    protected $id;
	protected $name;
	protected $questions;
	
	public function __construct(array $options = NULL)
	{
		parent::__construct($options);
		$this->questions = array();
	}
	
	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}
	
	public function getQuestions()
	{
		return $this->questions;
	}
	
	public function setId($value)
	{
		$this->id = $value;
		return $this;
	}

	public function setName($value)
	{
		$this->name = $value;
		return $this;
	}
	
	public function setQuestions(array $questions)
	{
		foreach ($questions as $one)
			$this->addQuestion($one);
		return $this;
	}
	
	public function addQuestion(Question $question)
	{
		array_push($this->questions, $question);
		return $this->questions;
	}
	
	public function toArray()
	{
		$array = parent::toArray();
		if (!empty($this->questions))
			for ($i=0; $i<count($array['questions']); $i++)
				$array['questions'][$i] = $array['questions'][$i]->toArray();
		else
			unset($array['questions']);
		return $array;
	}
}

?>