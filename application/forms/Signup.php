<?php

class Application_Form_Signup extends Zend_Form
{
	
    public function init()
    {
    	$this->setMethod('post');
    	
    	//Fields for user information update
    	$authns = new Zend_Session_Namespace('AuthNameSpace');
    	if(isset($authns->userId)){
    		$this->addElement(
    				'text', 'memberLogin', array(
    						'label' => 'Username:',
    						'required' => true,
    						'filters' => array('StringTrim'),
    						'validators' => array(
    								'alnum',
    								array('StringLength', false, array(5,20)),
    						)
    				));
    	}
    	else{
	    	$this->addElement(
	    			'text', 'memberLogin', array(
	    					'label' => 'Username:',
	    					'required' => true,
	    					'filters' => array('StringTrim'),
	    					'validators' => array(
	    							'alnum',
	    							array('StringLength', false, array(5,20)),
	    							array('Db_NoRecordExists', false, array(
	    									'table' => 'members',
	    									'field' => 'member_login',
	    									'messages' => 'Username unavailable'
	    							)),
	    					)
	    			));
    	}
    	
    	//Fields for user information update
    	$authns = new Zend_Session_Namespace('AuthNameSpace');
    	if(isset($authns->userId)){
    		$this->addElement(
    				'password', 'oldPassword', array(
    				'label' => 'Current Password:',
    				'required' => true,
    				'validators'=> array('alpha',array('StringLength', false, array(5,5))),
    		));
    		$this->addElement(
    				'password', 'memberPassword', array(
    						'label' => 'New Password:',
    						'required' => true,
    						'validators'=>array('alpha',array('StringLength', false, array(5,5))),
    		));
	    	$this->addElement(
	    			'password', 'confirmPassword', array(
	    			'label' => 'Confirm New Password:',
	    			'required' => true,
	    			'validators' => array(
	    					array('identical', false, array('token' => 'memberPassword'))
	    			)
	    	));
    	}
    	//Fields for user signup
    	else{
	    	$this->addElement(
	    			'password', 'memberPassword', array(
	    			'label' => 'Password:',
	    			'required' => true,
	    			'validators'=>array('alpha',array('StringLength', false, array(5,5))),
	    	));
	    	
	    	$this->addElement(
	    			'password', 'confirmPassword', array(
	    			'label' => 'Confirm Password:',
	    			'required' => true,
	    			'validators' => array(
	    					array('identical', false, array('token' => 'memberPassword'))
	    			)
	    	));
    	}
    	
    	$this->addElement(
    			'text', 'firstName', array(
    					'label' => 'First Name:',
    					'validators'=>array('alpha',array('StringLength', false, array(3,20))),
    					'required' => true,
    					'filters' => array('StringTrim'),
    			));
    	
    	$this->addElement(
    			'text', 'lastName', array(
    					'label' => 'Last Name:',
    					'required' => true,
    					'filters'    => array('StringTrim'),
    					'validators'=>array('alpha',array('StringLength', false, array(3,20))),
    			));
    	
    	$this->addElement(
    			'text', 'email', array(
    					'label' => 'Email:',
    					'required' => true,
    					'filters'    => array('StringTrim'),
    					'validators' => array(
    							'EmailAddress',
    					)
    			));
    	
    	$this->addElement(
    			'submit', 'submit', array(
    			'ignore'   => true,
    			'label'    => 'Submit',
    	));
    }
}