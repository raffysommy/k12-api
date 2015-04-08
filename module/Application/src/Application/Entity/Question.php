<?php

namespace Application\Entity;

use Sorus\StdEntity;

class Question extends StdEntity
{
	protected $id;
	protected $body;
	protected $answer;
	protected $fakeAnswer1;
	protected $fakeAnswer2;
	protected $fakeAnswer3;
	protected $topic;

	public function getId()
	{
		return $this->id;
	}

	public function getBody()
	{
		return $this->body;
	}

	public function getAnswer()
	{
		return $this->answer;
	}

	public function getFakeAnswer1()
	{
		return $this->fakeAnswer1;
	}

	public function getFakeAnswer2()
	{
		return $this->fakeAnswer2;
	}

	public function getFakeAnswer3()
	{
		return $this->fakeAnswer3;
	}
	
	public function getTopic()
	{
	    return $this->topic;
	}

	public function setId($value)
	{
		$this->id = $value;
		return $this;
	}

	public function setBody($value)
	{
		$this->body = $value;
		return $this;
	}

	public function setAnswer($value)
	{
		$this->answer = $value;
		return $this;
	}

	public function setFakeAnswer1($value)
	{
		$this->fakeAnswer1 = $value;
		return $this;
	}

	public function setFakeAnswer2($value)
	{
		$this->fakeAnswer2 = $value;
		return $this;
	}

	public function setFakeAnswer3($value)
	{
		$this->fakeAnswer3 = $value;
		return $this;
	}
	
	public function setTopic($value)
	{
	    $this->topic = $value;
	    return $this;
	}

}

?>