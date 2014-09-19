<?php

class Application_Model_MemberMapper
{
	protected $_dbTable;
	protected $_loggedInUserId;
	
	public function setDbTable($dbTable)
	{
		if (is_string($dbTable)) {
			$dbTable = new $dbTable();
		}
		if (!$dbTable instanceof Zend_Db_Table_Abstract) {
			throw new Exception('Invalid table data gateway provided');
		}
		$this->_dbTable = $dbTable;
		return $this;
	}
	
	public function getDbTable()
	{
		if (null === $this->_dbTable) {
			$this->setDbTable('Application_Model_DbTable_Members');
		}
		return $this->_dbTable;
	}
	
	// save member back to db
	public function save(Application_Model_Member $member)
	{
		$data = array(
				'member_login'   => $member->getMemberLogin(),
				'member_password'   => $member->getMemberPassword(),
				'first_name'   => $member->getFirstName(),
				'last_name'   => $member->getLastName(),
				'email'   => $member->getEmail(),
		);
		if (null === ($id = $member->getId())) {
			unset($data['member_id']);
			$this->getDbTable()->insert($data);
		} else {
			$this->getDbTable()->update($data, array('member_id = ?' => $id));
		}
	}
	
	public function verifyPassword(Application_Model_Member $member, $oldPassword)
	{
		$select = $this->getDbTable()->select()->where('member_login = ?', $member->getMemberLogin())->where('member_password = ?', $oldPassword);
		$resultSet = $this->getDbTable()->fetchAll($select);
	
		$rowCount = count($resultSet);
	
		if ($rowCount > 0) {
			return true;
		}
		return false;
	}
	
	//method to log the user in, test if the user is in the database
	public function login(Application_Model_Member $member)
	{
		$select = $this->getDbTable()->select()->where('member_login = ?', $member->getMemberLogin())->where('member_password = ?', $member->getMemberPassword());
		$resultSet = $this->getDbTable()->fetchAll($select);
	
		$rowCount = count($resultSet);
	
		if ($rowCount > 0) {
			return true;
		}
		return false;
	}
	
	//find the member based on its login id
	public function find($userId, Application_Model_Member $member)
	{
		$select = $this->getDbTable()->select()->where('member_login = ?', $userId);
		$resultSet = $this->getDbTable()->fetchRow($select);
	
		if (0 == count($resultSet)) {
			return;
		}
		$row = $resultSet;
	
		$member->setId($row->member_id)
		->setMemberLogin($row->member_login)
		->setMemberPassword($row->member_password)
		->setEmail($row->email)
		->setFirstName($row->first_name)
		->setLastName($row->last_name);
	}
}

