<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
    	$this->_redirector = $this->_helper->getHelper('Redirector');
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


}

