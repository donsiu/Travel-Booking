<?php

class Application_Model_Member
{
	protected $_id;
	protected $_memberLogin;
	protected $_memberPassword;
	protected $_firstName;
	protected $_lastName;
	protected $_email;
	
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
	
	public function setMemberLogin($text)
	{
		$this->_memberLogin = (string) $text;
		return $this;
	}
	
	public function getMemberLogin()
	{
		return $this->_memberLogin;
	}
	
	public function setMemberPassword($text)
	{
		$this->_memberPassword = (string) $text;
		return $this;
	}
	
	public function getMemberPassword()
	{
		return $this->_memberPassword;
	}
	
	public function setFirstName($text)
	{
		$this->_firstName = (string) $text;
		return $this;
	}
	
	public function getFirstName()
	{
		return $this->_firstName;
	}
	
	public function setLastName($text)
	{
		$this->_lastName = (string) $text;
		return $this;
	}
	
	public function getLastName()
	{
		return $this->_lastName;
	}
	
	public function setEmail($text)
	{
		$this->_email = (string) $text;
		return $this;
	}
	
	public function getEmail()
	{
		return $this->_email;
	}
}

