<?php
require_once('Zend/Log.php');
require_once('Zend/Log/Writer/Stream.php');

class MembersController extends Zend_Controller_Action
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
    
    public function indexAction()
    {
        // action body
    }

    public function loginAction()
    {
    	//Use of session to store login and logout user
    	$authns = new Zend_Session_Namespace('AuthNameSpace');
    	
    	if(isset($authns->userId)){
    		//User has already logged in before
    		$mapper  = new Application_Model_MemberMapper();
    		$member  = new Application_Model_Member();
    		$mapper->find($authns->userId, $member);
    		if ($mapper->login($member)){
    			// return $this->_redirect('/');
    			$this->_redirector->gotoSimple(
    					'view',
    					'ideas',
    					null,
    					array()
    			);
    		}
    	}
    	//User has not yet login, present login form
    	$loginForm = new Application_Form_Login();
    	if ($this->getRequest()->isPost()) {
    		if ($loginForm->isValid($this->getRequest()->getPost())) {
    			$member = new Application_Model_Member($loginForm->getValues());
    			$mapper  = new Application_Model_MemberMapper();
    			//If the user has registered
    			if ($mapper->login($member)){
    				//Use of session to store login and logout user
    				$authns = new Zend_Session_Namespace('AuthNameSpace');
    				if(!isset($authns->userId)){
    					//Retrieve the user ID
    					$authns->userId = $member->getMemberLogin();
    				}
    				// return $this->_redirect('/');
    				$this->_redirector->gotoSimple(
    						'view',
    						'ideas',
    						null,
    						array()
    				);
    			}
    			else{
    				$this->_helper->FlashMessenger()->setNamespace('error')->addMessage('Login Failed.');
    				// return $this->_redirect('/members/login/');
    				$this->_redirector->gotoSimple(
    						'login',
    						'members',
    						null,
    						array()
    				);
    			}
    		}
    	}
    	
    	$this->view->loginForm = $loginForm;
    }

    public function logoutAction()
    {
        $authns = new Zend_Session_Namespace('AuthNameSpace');
        
        if(isset($authns->userId)){
            unset($authns->userId);
            return $this->_redirect('/members/login/');
        }
    }

    public function signupAction()
    {
    	$signupForm = new Application_Form_Signup();

    	if ($this->getRequest()->isPost()) {
    		if ($signupForm->isValid($this->getRequest()->getPost())) {
    			$member = new Application_Model_Member($signupForm->getValues());
    			$mapper  = new Application_Model_MemberMapper();
    			// Save new user data
    			$mapper->save($member);
    			$this->_helper->FlashMessenger()->setNamespace('success')->addMessage('Signup Successful!');
    			// Signin the new user
    			$authns = new Zend_Session_Namespace('AuthNameSpace');
    			if(!isset($authns->userId)){
    				$authns->userId = $member->getMemberLogin();
    			}
    			return $this->_redirect('/');
    		}
    	}
    	
    	$this->view->signupForm = $signupForm;
    }

    public function editAction()
    {    	
		$request = $this->getRequest();
		
        $authns = new Zend_Session_Namespace('AuthNameSpace');
        if(!isset($authns->userId)){
            return;
        }
        $form    = new Application_Form_Signup();
		$mapper  = new Application_Model_MemberMapper();
        $member  = new Application_Model_Member();
        $mapper->find($authns->userId, $member);
        //reuse member_id in post
        $id = $member->getId();
        
        //handle changes to member info
        if ($this->getRequest()->isPost()) {   
            if ($form->isValid($this->getRequest()->getPost())) {
                //get the newly updated form value
                $member = new Application_Model_Member($form->getValues());
                //set the id of the form in order to make changes to the same member in database
                $member->setId($id);
                
                //Verify user password before updating user information
                $oldPassword = $form->getValue('oldPassword');
                if ($mapper->verifyPassword($member, $oldPassword)){
                	$mapper->save($member);
                	$this->_helper->FlashMessenger()->setNamespace('success')->addMessage('Update Successful!');
                	$this->_logger->log('Update: Password Verified!', Zend_Log::INFO);
                	return $this->_redirect('/');
                }
		        else{
		        	$this->_helper->FlashMessenger()->setNamespace('error')->addMessage('Incorrect password. Failed to save changes.');
		        	$this->_logger->log('Update: Incorrect Password!', Zend_Log::INFO);
		        	return $this->_redirect('/members/edit');
		        }

            }
        }
        //form data array to populate signup form with existing data
        $data = array(
                      'memberLogin'   => $member->getMemberLogin(),
                      'memberPassword'  => $member->getMemberPassword(),
                      'firstName'   => $member->getFirstName(),
                      'lastName'   => $member->getLastName(),
                      'email'   => $member->getEmail(),
                      );
        
		$form->populate($data);
		
		$this->view->form=$form;
    }
}