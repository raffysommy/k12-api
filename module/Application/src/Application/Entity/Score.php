<?php

namespace Application\Entity;

use Sorus\StdEntity;

class Score extends StdEntity
{
	protected $user;
	protected $question;
	protected $date;
	protected $result;

	public function getUser()
	{
		return $this->user;
	}

	public function getQuestion()
	{
		return $this->question;
	}

	public function getDate()
	{
		return $this->date;
	}

	public function getResult()
	{
		return $this->result;
	}

	public function setUser(User $value)
	{
		$this->user = $value;
		return $this;
	}

	public function setQuestion(Question $value)
	{
		$this->question = $value;
		return $this;
	}

	public function setDate($value)
	{
		$this->date = $value;
		return $this;
	}

	public function setResult($value)
	{
		$this->result = $value;
		return $this;
	}
	
	public function toRelationalTable()
	{
		$relationalData = $this->toArray();
		$relationalData['user'] = $relationalData['user']->id;
		$relationalData['question'] = $relationalData['question']->id;
		return $relationalData;
	}
	
}

?>