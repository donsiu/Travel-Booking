<?php
require_once('Zend/Log.php');
require_once('Zend/Log/Writer/Stream.php');

class IdeasController extends Zend_Controller_Action
{

    protected $_flashMessenger = null;

    protected $_logger = null;

    public function init()
    {
    	//For redirection
    	$this->_redirector = $this->_helper->getHelper('Redirector');
    	//For printing user notification messages
    	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    	$this->initView();
    	//For debugging
    	$this->_logger = new Zend_Log();
    	$writer = new Zend_Log_Writer_Stream('sample.log');
    	$this->_logger->addWriter($writer);
    }

    public function preDispatch()
    {
    	//Check if the user is logged in
    	$authns = new Zend_Session_Namespace('AuthNameSpace');
    	
    	if (isset($authns->userId)) {
    		// user is logged in
    		// do nothing
    	} else {
    		// re-direct to the member login page
    		$this->_redirector->gotoSimple(
    				'login',
    				'members',
    				null,
    				array()
    		);
    	}
    }

    public function indexAction()
    {
    	$this->_redirector->gotoSimple(
    			'view',
    			'ideas',
    			null,
    			array()
    	);
    }

    public function viewAction()
    {
    	$idea = new Application_Model_IdeaMapper();
    	$this->view->ideas = $idea->fetchAll();
    }

    public function createAction()
    {
    	$request = $this->getRequest();
        $form = new Application_Form_Idea();
        
		if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {
                $idea = new Application_Model_Idea($form->getValues());
                
                // Get idea author information
                $author = new Application_Model_Member();
                $authns = new Zend_Session_Namespace('AuthNameSpace');
                
                $mapper = new Application_Model_MemberMapper();
                $mapper->find($authns->userId, $author);
                $idea->setAuthor($author);
                
                $this->_logger->log('author ID = '.$authns->userId, Zend_Log::INFO);
                
                // Save the idea
                $mapper = new Application_Model_IdeaMapper();
                $this->_logger->log('here', Zend_Log::INFO);
                $id = $mapper->save($idea);
                $this->_logger->log('inserted travel idea successfully.', Zend_Log::INFO);
                
                // Save its tag
                $tags = explode(";", $idea->tagString);
                foreach ($tags as $temp)
                {
                	if (strlen($temp) > 0) {
	                	$tag = new Application_Model_Tag();
	                	$tag->setTag($temp);
	                	$tag->setIdeaId($id);
	                	
	                	$this->_logger->log('tag = '.$temp, Zend_Log::INFO);
	                	
	                	$mapper = new Application_Model_TagMapper();
	                	$mapper->save($tag);
                	}
                }
                
                $this->_helper->FlashMessenger()->setNamespace('success')->addMessage('Submission Successful!');
                $this->_redirector->gotoSimple(
                	'detail',
                	'ideas',
                	null,
                	array(
                	    'id' => $id,
                	)
                );
            }
        }
        $this->view->form = $form;
    }

    public function searchAction()
    {	
    	$request = $this->getRequest();
    	
    	//Retrive the request parameters
    	$searchOption = $request->getParam("option");
    	$isDisableLayout = $request->getParam("isDisableLayout");
    	$isDisableLayout = filter_var($isDisableLayout, FILTER_VALIDATE_BOOLEAN);
    	
    	if ($isDisableLayout) {
    		$this->_helper->layout()->disableLayout();
    	} else {
    		// nothing to do
    	}
    	
    	$idea = new Application_Model_IdeaMapper();
    	
    	if ($searchOption == "Member") {
    		$authns = new Zend_Session_Namespace('AuthNameSpace');
    		$this->view->searchOption = $searchOption;
    		$this->view->ideas = $idea->fetchByAuthor($authns->userId);
    	} else {
    		$searchText = $request->getParam("content");
    		$filteredSearchText = ($searchText) ? strip_tags(htmlspecialchars($searchText, ENT_QUOTES)) : '';
    		
	    	if ($searchOption == "Destination") {
	    		$isPartial = $request->getParam("partial");
	    		$this->_logger->log('Ajax search by destination start.  searchText='.$filteredSearchText.", isPartial=".$isPartial, Zend_Log::INFO);
	    		$this->view->ideas = $idea->fetchByDestination($filteredSearchText, $isPartial);
	    	} else if ($searchOption == "Tags") {
	    		$this->view->ideas = $idea->fetchByTag($filteredSearchText);
	    	} else {
	    		// something wrong
	    	}
    	}
    }

    public function editAction()
    {
    	$request = $this->getRequest();
    	$id = $request->getParam("id");
    	
    	// Get the idea information from the database
    	$form = new Application_Form_Idea();
    	$mapper = new Application_Model_IdeaMapper();
    	$idea = new Application_Model_Idea();
    	$mapper->find($id, $idea);
    	
    	// Get the tag information from the database
    	$mapper = new Application_Model_TagMapper();
    	$tags = $mapper->fetchByIdea($idea);
    	
    	// formulate the tag string for the idea
    	$tagString = "";
    	foreach ($tags as $tag) {
    		$tagString = $tagString.$tag->getTag().";";
    	}
    	$idea->setTagString($tagString);
    	
    	//handle changes to travel idea
    	if ($this->getRequest()->isPost()) {
    		if ($form->isValid($this->getRequest()->getPost())) {
    			//get the newly updated form value
    			$idea = new Application_Model_Idea($form->getValues());
    			//set the id of the idea in order to make changes to the same idea in database
    			$idea->setId($id);
    			
    			// Get idea author information
    			$author = new Application_Model_Member();
    			$authns = new Zend_Session_Namespace('AuthNameSpace');
    			
    			$mapper = new Application_Model_MemberMapper();
    			$mapper->find($authns->userId, $author);
    			$idea->setAuthor($author);
    			
    			// save the idea in the database
    			$mapper = new Application_Model_IdeaMapper();
    			$mapper->save($idea);
    			
    			// Delete all the tag related to this idea
    			$mapper = new Application_Model_TagMapper();
    			$mapper->deleteByIdeaId($id);
    			
    			$this->_logger->log('after update idea, tag String ='.$idea->tagString, Zend_Log::INFO);
    			
    			// Extract the updated tag
    			$tags = explode(";", $idea->tagString);
    			foreach ($tags as $temp)
    			{
    				if (strlen($temp) > 0) {
	    				$tag = new Application_Model_Tag();
	    				$tag->setTag($temp);
	    				$tag->setIdeaId($id);
	    				 
	    				$this->_logger->log('tag = '.$temp, Zend_Log::INFO);
	    				// Save the tag in the database
	    				$mapper->save($tag);
    				}
    			}
    			
    			$this->_helper->FlashMessenger()->setNamespace('success')->addMessage('Update Successful!');
    			$this->_logger->log('Update Idea successfully! ID ='.$id, Zend_Log::INFO);
    				
    			$this->_redirector->gotoSimple(
    				'detail',
    				'ideas',
    				null,
    				array(
    					'id' => $id,
    				)
    			);
    			return;
    		}
    	}
    	
    	//form data array to populate idea creation form with existing data
    	$data = array(
    		'title'   => $idea->getTitle(),
    		'destination'  => $idea->getDestination(),
    		'startDate'   => $idea->getStartDate(),
    		'endDate'   => $idea->getEndDate(),
    		'tagString'   => $idea->getTagString(),
    	);
    	
    	$form->populate($data);
    	
    	$this->view->form=$form;
    }

    public function detailAction()
    {
    	//Receive request
    	$request = $this->getRequest();
    	
    	//Get comment list ID
    	$id = $request->getParam("id");
    	
    	$this->_logger->log('Detail idea check point 1, id='.$id, Zend_Log::INFO);
    	
    	//For chatroom message handling
    	$usermsg = $request->getParam("usermsg");
    	if ($usermsg != null)
    	{
    		//Get the user name    		
    		$authns = new Zend_Session_Namespace('AuthNameSpace');
    		$username = $authns->userId;
    		$fp = fopen("idea_".$id."_comment.html", 'a');
    		fwrite($fp, "<div class='msgln'>".date("d-M-Y h:i:s A")."<br>".$username.": ".stripslashes(htmlspecialchars($usermsg))."<br></div>");
    		fclose($fp);
    	}
    	
    	$this->_logger->log('Detail idea check point 2, id='.$id, Zend_Log::INFO);
    	
    	// For showing the idea detail
    	$id = $request->getParam("id");
    	// Get travel idea details
    	$idea = new Application_Model_Idea();
    	$mapper = new Application_Model_IdeaMapper();
    	$mapper->find($id, $idea);
    	$this->view->idea = $idea;
    	
    	$this->_logger->log('Detail idea check point 3, id='.$id, Zend_Log::INFO);
    	
    	// Get the tag related to the travel idea
    	$tag     = new Application_Model_Tag();
    	$mapper  = new Application_Model_TagMapper();
    	$this->view->tags = $mapper->fetchByIdea($idea);
    }

    public function deleteAction()
    {
    	//Get travel idea ID
    	$request = $this->getRequest();
    	$id = $request->getParam("id");
    	
    	// Delete travel idea from the database
    	$mapper = new Application_Model_IdeaMapper();
    	$result = $mapper->delete($id);
    	
    	$this->_logger->log('Delete idea result='.$result, Zend_Log::INFO);
    	
    	// Delete related tag from the database
    	$mapper = new Application_Model_TagMapper();
    	$result = $mapper->deleteByIdeaId($id);
    	
    	$this->_logger->log('Delete tag result='.$result, Zend_Log::INFO);
    	
    	$this->_redirector->gotoSimple(
    			'search',
    			'ideas',
    			null,
    			array(
    				'option' => 'Member',
    				'isDisableLayout' => 'false'
    			)
    	);
    }
}

