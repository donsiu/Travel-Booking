<?php

class Application_Model_Tag
{
	protected $_id;
	protected $_tag;
	protected $_ideaId;
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
	
	public function setTag($tag)
	{
		$this->_tag = (string) $tag;
		return $this;
	}
	
	public function getTag()
	{
		return $this->_tag;
	}
	
	public function setIdeaId($ideaId)
	{
		$this->_ideaId = (int) $ideaId;
		return $this;
	}
	
	public function getIdeaId()
	{
		return $this->_ideaId;
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

