<?php

class Application_Form_Login extends Zend_Form
{

    public function init()
    {
    	$this->setMethod('post');
    	
    	$this->addElement(
    			'text', 'memberLogin', array(
    					'label' => 'Username:',
    					'required' => true,
    					'filters'    => array('StringTrim'),
    			));
    	
    	$this->addElement('password', 'memberPassword', array(
    			'label' => 'Password:',
    			'required' => true,
    	));
    	
    	$this->addElement('submit', 'submit', array(
    			'ignore'   => true,
    			'label'    => 'Login',
    	));
    }
}