<?php

class Application_Model_Idea
{
	protected $_id;
	protected $_destination;
	protected $_title;
	protected $_startDate;
	protected $_endDate;
	protected $_tagString;
	protected $_author;
	protected $_createDatetime;
	protected $_lastUpdateDatetime;
	
	public function __construct(array $options = null)
	{
		if (is_array($options)) {
			$this->setOptions($options);
		}
	}
	
	public function __set($name, $value)
	{
		$method = 'set' . $name;
		if (('mapper' == $name) || !method_exists($this, $method)) {
			throw new Exception('Invalid member property');
		}
		$this->$method($value);
	}
	
	public function __get($name)
	{
		$method = 'get' . $name;
		if (('mapper' == $name) || !method_exists($this, $method)) {
			throw new Exception('Invalid member property');
		}
		return $this->$method();
	}
	
	public function setOptions(array $options)
	{
		$methods = get_class_methods($this);
		foreach ($options as $key => $value) {
			$method = 'set' . ucfirst($key);
			if (in_array($method, $methods)) {
				$this->$method($value);
			}
		}
		return $this;
	}
	
	public function setId($id)
	{
		$this->_id = (int) $id;
		return $this;
	}
	
	public function getId()
	{
		return $this->_id;
	}
	
	public function setDestination($destination)
	{
		$this->_destination = (string) $destination;
		return $this;
	}
	
	public function getDestination()
	{
		return $this->_destination;
	}
	
	public function setTitle($title)
	{
		$this->_title = (string) $title;
		return $this;
	}
	
	public function getTitle()
	{
		return $this->_title;
	}
	
	public function setStartDate($startDate)
	{
		$this->_startDate = (string) $startDate;
		return $this;
	}
	
	public function getStartDate()
	{
		return $this->_startDate;
	}
	
	public function setEndDate($endDate)
	{
		$this->_endDate = (string) $endDate;
		return $this;
	}
	
	public function getEndDate()
	{
		return $this->_endDate;
	}
	
	public function setTagString($tagString)
	{
		$this->_tagString = (string) $tagString;
		return $this;
	}
	
	public function getTagString()
	{
		return $this->_tagString;
	}
	
	public function setAuthor(Application_Model_Member $author)
	{
		$this->_author = $author;
		return $this;
	}
	
	public function getAuthor()
	{
		return $this->_author;
	}
	
	public function setCreateDatetime($createDatetime)
	{
		$this->_createDatetime = (string) $createDatetime;
		return $this;
	}
	
	public function getCreateDatetime()
	{
		return $this->_createDatetime;
	}
	
	public function setLastUpdateDatetime($lastUpdateDatetime)
	{
		$this->_lastUpdateDatetime = (string) $lastUpdateDatetime;
		return $this;
	}
	
	public function getLastUpdateDatetime()
	{
		return $this->_lastUpdateDatetime;
	}
}

