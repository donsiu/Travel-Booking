<?php
require_once('Zend/Log.php');
require_once('Zend/Log/Writer/Stream.php');

class Application_Model_IdeaMapper
{
	protected $_dbTable;
	protected $_logger = null;
	
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
			$this->setDbTable('Application_Model_DbTable_Ideas');
		}
		return $this->_dbTable;
	}
	
	// save travel idea back to db
	public function save(Application_Model_Idea $idea)
	{
		//For debugging
		$this->_logger = new Zend_Log();
		$writer = new Zend_Log_Writer_Stream('sample.log');
		$this->_logger->addWriter($writer);
		$this->_logger->log('here2', Zend_Log::INFO);
		
		$data = array(
			'destination' => $idea->getDestination(),
			'title' => $idea->getTitle(),
			'start_date' => $idea->getStartDate(),
			'end_date' => $idea->getEndDate(),
			'author_id' => $idea->getAuthor()->getId(),
		);
		
		$this->_logger->log('author ID = '.$idea->getAuthor()->getId(), Zend_Log::INFO);
		
		if (null === ($id = $idea->getId())) {
			unset($data['idea_id']);
			return $this->getDbTable()->insert($data);
		} else {
			return $this->getDbTable()->update($data, array('idea_id = ?' => $id));
		}
	}
	
	public function fetchAll()
	{
		$select = $this->getDbTable()->select()
		    ->from(array('i' => 'ideas'))
		    ->join(array('m' => 'members'),
		    	   'i.author_id = m.member_id')
		    ->setIntegrityCheck(false);
		
		$resultSet = $this->getDbTable()->fetchAll($select);
			
		$entries   = array();
		foreach ($resultSet as $row) {
			$author = new Application_Model_Member();
			$author->setId($row->member_id)
			       ->setMemberLogin($row->member_login)
			       ->setEmail($row->email)
			       ->setFirstName($row->first_name)
			       ->setLastName($row->last_name);
			
			$entry = new Application_Model_Idea();
			$entry->setId($row->idea_id)
			      ->setDestination($row->destination)
			      ->setTitle($row->title)
			      ->setStartDate($row->start_date)
			      ->setEndDate($row->end_date)
			      ->setAuthor($author)
			      ->setCreateDatetime($row->create_datetime)
			      ->setLastUpdateDatetime($row->last_update_datetime);

			$entries[] = $entry;
		}
		return $entries;
	}
	
	public function fetchRecord($text, $search_type, $is_partial)
	{
		//For debugging
		$this->_logger = new Zend_Log();
		$writer = new Zend_Log_Writer_Stream('sample.log');
		$this->_logger->addWriter($writer);

		//Array to hold the final search results
		$entries = array();
		
		//Search by destination name
		//Formulate SQL query for partial or full search
		if($search_type=="Destination"){
			$select = $this->getDbTable()->select();
			if($is_partial=="true"){
				$filteredText = "%".$text."%";
				$select->where('destination like ?', $filteredText);
				$this->_logger->log($select, Zend_Log::INFO);
			}
			else{
				$select->where('destination = ?', $text);
				$this->_logger->log($select, Zend_Log::INFO);
			}
			$resultSet = $this->getDbTable()->fetchAll($select);
		}		
		//Search by tags
		else if($search_type=="Tags"){
			//Access tag's mapper function
			$tagMapper = new Application_Model_TagMapper();
			$selectTag = $tagMapper->getDbTable()->select();
			$selectTag->where('tag = ?', $text);
			$this->_logger->log($selectTag, Zend_Log::INFO);
			$tagResultSet = $tagMapper->getDbTable()->fetchAll($selectTag);
			//Array to store matching ideas object
			$temp_entries = array();
			//Parse return results
			foreach ($tagResultSet as $row) {
				$entry = new Application_Model_Tag();
				$entry->setTag($row->tag)
				->setIdeaId($row->idea_id)
				->setCreateDatetime($row->create_datetime)
				->setLastUpdateDatetime($row->last_update_datetime);
				
				//Retrive ideas object by idea_id which has a matching tag
				$select = $this->getDbTable()->select();
				$select->where('idea_id = ?', $row->idea_id);
				$tempResultSet = $this->getDbTable()->fetchRow($select);
				//Aggregate the matching ideas to the same array
				array_push($temp_entries, $tempResultSet);				
			}
			//Assign result for parsing
			$resultSet = $temp_entries;
		}
		
		//Parse return results
		foreach ($resultSet as $row) {
			$author = new Application_Model_Member();
			$author->setId($row->author_id);
			
			$entry = new Application_Model_Idea();	
			$entry->setId($row->idea_id)
			->setDestination($row->destination)
			->setTitle($row->title)
			->setStartDate($row->start_date)
			->setEndDate($row->end_date)
			->setAuthor($row->author)
			->setCreateDatetime($row->create_datetime)
			->setLastUpdateDatetime($row->last_update_datetime);
			
			$entries[] = $entry;
		}
		
		return $entries;
	}
	
	public function find($id, Application_Model_Idea $idea)
	{
		//For debugging
		$this->_logger = new Zend_Log();
		$writer = new Zend_Log_Writer_Stream('sample.log');
		$this->_logger->addWriter($writer);
		
		$result = $this->getDbTable()->find($id);
		
		$select = $this->getDbTable()
		    ->select()
		    ->from(array('i' => 'ideas'))
		    ->join(array('m' => 'members'),
				   'i.author_id = m.member_id')
			->where('i.idea_id = ?', $id);
		    
	    $select->setIntegrityCheck(false);
		
	    $this->_logger->log('Detail idea check point 2a, id='.$id, Zend_Log::INFO);
	    
		$result = $this->getDbTable()->fetchAll($select);
	
		$this->_logger->log('Detail idea check point 2b, id='.$id, Zend_Log::INFO);
		
		if (0 == count($result)) {
			return;
		}
		$row = $result->current();
	
		$author = new Application_Model_Member();
		$author->setId($row->author_id)
		       ->setMemberLogin($row->member_login)
		       ->setEmail($row->email)
		       ->setFirstName($row->first_name)
		       ->setLastName($row->last_name);
		
		$idea->setId($row->idea_id)
		     ->setDestination($row->destination)
		     ->setTitle($row->title)
		     ->setStartDate($row->start_date)
		     ->setEndDate($row->end_date)
		     ->setAuthor($author)
		     ->setCreateDatetime($row->create_datetime)
		     ->setLastUpdateDatetime($row->last_update_datetime);
	}
	
	public function fetchByAuthor($login)
	{
		//For debugging
		$this->_logger = new Zend_Log();
		$writer = new Zend_Log_Writer_Stream('sample.log');
		$this->_logger->addWriter($writer);
		$this->_logger->log('Ajax fetch idea by author start', Zend_Log::INFO);
	
		$ideas = array();
	
		try {
			$select = $this->getDbTable()
			               ->select()
			               ->from(array('i' => 'ideas'))
			               ->join(array('m' => 'members'),
					              'i.author_id = m.member_id')
			               ->where('m.member_login = ?', $login);
			 
			$select->setIntegrityCheck(false);

			$resultSet = $this->getDbTable()->fetchAll($select);
				
			foreach ($resultSet as $row) {
				$author = new Application_Model_Member();
				$author->setId($row->member_id)
				       ->setMemberLogin($row->member_login)
				       ->setEmail($row->email)
				       ->setFirstName($row->first_name)
				       ->setLastName($row->last_name);

				$idea = new Application_Model_Idea();
				$idea->setId($row->idea_id)
				     ->setDestination($row->destination)
				     ->setTitle($row->title)
				     ->setStartDate($row->start_date)
			         ->setEndDate($row->end_date)
				     ->setAuthor($author)
				     ->setCreateDatetime($row->create_datetime)
				     ->setLastUpdateDatetime($row->last_update_datetime);

				$ideas[] = $idea;
			}
				
			$this->_logger->log('Ajax fetch idea by author end.  no. of result='.count($ideas), Zend_Log::INFO);
		} catch (Exception $ex) {
			$this->_logger->log($ex->getMessage(), Zend_Log::ERROR);
		}
	
		return $ideas;
	}
	
	public function fetchByTag($tag)
	{
		//For debugging
		$this->_logger = new Zend_Log();
		$writer = new Zend_Log_Writer_Stream('sample.log');
		$this->_logger->addWriter($writer);
		$this->_logger->log('Ajax fetch idea by tag start', Zend_Log::INFO);
		
		$ideas = array();
		
		try {
			$select = $this->getDbTable()
			               ->select()
			               ->from(array('i' => 'ideas'))
			               ->join(array('t' => 'tags'),
				                  'i.idea_id = t.idea_id')
			               ->join(array('m' => 'members'),
			    	              'i.author_id = m.member_id')
			               ->where('t.tag = ?', $tag);
			    
			$select->setIntegrityCheck(false);
		
			$resultSet = $this->getDbTable()->fetchAll($select);
			
			foreach ($resultSet as $row) {
				$author = new Application_Model_Member();
				$author->setId($row->member_id)
				       ->setMemberLogin($row->member_login)
				       ->setEmail($row->email)
				       ->setFirstName($row->first_name)
				       ->setLastName($row->last_name);
				
				$idea = new Application_Model_Idea();
				$idea->setId($row->idea_id)
				     ->setDestination($row->destination)
				     ->setTitle($row->title)
				     ->setStartDate($row->start_date)
				     ->setEndDate($row->end_date)
				     ->setAuthor($author)
				     ->setCreateDatetime($row->create_datetime)
				     ->setLastUpdateDatetime($row->last_update_datetime);
		
				$ideas[] = $idea;
			}
			
			$this->_logger->log('Ajax fetch idea by tag end.  no. of result='.count($ideas), Zend_Log::INFO);
		} catch (Exception $ex) {
			$this->_logger->log($ex->getMessage(), Zend_Log::ERROR);
		}
		
		return $ideas;
	}
	
	public function fetchByDestination($destination, $isPartial)
	{
		//For debugging
		$this->_logger = new Zend_Log();
		$writer = new Zend_Log_Writer_Stream('sample.log');
		$this->_logger->addWriter($writer);
		$this->_logger->log('Ajax fetch idea by destination start', Zend_Log::INFO);
	
		$ideas = array();
	
		try {
			$select = $this->getDbTable()
			               ->select()
			               ->from(array('i' => 'ideas'))
			               ->join(array('m' => 'members'),
				                  'i.author_id = m.member_id');

			if ($isPartial == 'true') {
				$destination = "%".$destination."%";
				$select->where('i.destination like ?', $destination);
				$this->_logger->log('Ajax fetch idea by destination partial, isPartial='.$isPartial, Zend_Log::INFO);
			} else {
				$select->where('i.destination = ?', $destination);
				$this->_logger->log('Ajax fetch idea by destination NOT partial, isPartial='.$isPartial, Zend_Log::INFO);
			}
			 
			$select->setIntegrityCheck(false);

			$resultSet = $this->getDbTable()->fetchAll($select);
				
			foreach ($resultSet as $row) {
				$author = new Application_Model_Member();
				$author->setId($row->member_id)
				       ->setMemberLogin($row->member_login)
				       ->setEmail($row->email)
				       ->setFirstName($row->first_name)
				       ->setLastName($row->last_name);

				$idea = new Application_Model_Idea();
				$idea->setId($row->idea_id)
				     ->setDestination($row->destination)
				     ->setTitle($row->title)
				     ->setStartDate($row->start_date)
				     ->setEndDate($row->end_date)
				     ->setAuthor($author)
				     ->setCreateDatetime($row->create_datetime)
				     ->setLastUpdateDatetime($row->last_update_datetime);

				$ideas[] = $idea;
			}
				
			$this->_logger->log('Ajax fetch idea by destination end.  no. of result='.count($ideas), Zend_Log::INFO);
	
		} catch (Exception $ex) {
			$this->_logger->log($ex->getMessage(), Zend_Log::ERROR);
		}
	
		return $ideas;
	}
	
	public function delete($id) {
		//For debugging
		$this->_logger = new Zend_Log();
		$writer = new Zend_Log_Writer_Stream('sample.log');
		$this->_logger->addWriter($writer);
		$this->_logger->log('Delete idea ID = '.$id, Zend_Log::INFO);

		$result = $this->getDbTable()->delete(array('idea_id = ?' => $id));
		
		return $result;
	}
}