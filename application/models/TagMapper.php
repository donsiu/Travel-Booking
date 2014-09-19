<?php
require_once('Zend/Log.php');
require_once('Zend/Log/Writer/Stream.php');

class Application_Model_TagMapper
{
	protected $_dbTable;
	
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
			$this->setDbTable('Application_Model_DbTable_Tags');
		}
		return $this->_dbTable;
	}
	
	public function fetchAll()
	{
		$resultSet = $this->getDbTable()->fetchAll();
		$entries   = array();
		foreach ($resultSet as $row) {
			$entry = new Application_Model_Tag();
			$entry->setTag($row->tag)
			->setIdeaId($row->idea_id)
			->setCreateDatetime($row->create_datetime)
			->setLastUpdateDatetime($row->last_update_datetime);
	
			$entries[] = $entry;
		}
		return $entries;
	}
	
	public function fetchByIdea($idea) {
		$logger = new Zend_Log();
		$writer = new Zend_Log_Writer_Stream('sample.log');
		$logger->addWriter($writer);
		
		$logger->log('Fetch Tag by Idea start...', Zend_Log::INFO);
		
		$select = $this->getDbTable()->select();
		$select->where('idea_id = ?', $idea->id);
		
		$resultSet = $this->getDbTable()->fetchAll($select);
		$entries   = array();
		foreach ($resultSet as $row) {
			$entry = new Application_Model_Tag();
			$entry->setTag($row->tag)
			->setIdeaId($row->idea_id)
			->setCreateDatetime($row->create_datetime)
			->setLastUpdateDatetime($row->last_update_datetime);
		
			$entries[] = $entry;
		}
		
		$logger->log('Fetch Tag by Idea end, result size = '.count($entries), Zend_Log::INFO);
		return $entries;
	}
	
	public function save(Application_Model_Tag $tag)
	{
		//For debugging
		$this->_logger = new Zend_Log();
		$writer = new Zend_Log_Writer_Stream('sample.log');
		$this->_logger->addWriter($writer);
		$this->_logger->log('here2', Zend_Log::INFO);
	
		$data = array(
				'tag' => $tag->getTag(),
				'idea_id' => $tag->getIdeaId(),
		);
	
		if (null === ($id = $tag->getId())) {
			unset($data['tag_id']);
			return $this->getDbTable()->insert($data);
		} else {
			return $this->getDbTable()->update($data, array('tag_id = ?' => $id));
		}
	}
	
	public function deleteByIdeaId($id) {
		//For debugging
// 		$this->_logger = new Zend_Log();
// 		$writer = new Zend_Log_Writer_Stream('sample.log');
// 		$this->_logger->addWriter($writer);
// 		$this->_logger->log('Delete idea ID = '.$id, Zend_Log::INFO);
	
		$result = $this->getDbTable()->delete(array('idea_id = ?' => $id));
	
		return $result;
	}
}
