<?php

class Application_Form_Idea extends Zend_Form
{
	
    public function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');
        
        $this->addElementPrefixPath('Custom_Validate', 'Custom/Validate/', 'validate');
        
        $this->addElement('text', 'title', array(
        	'label'      => 'Title:',
        	'required'   => true,
        	'validators' => array(
        		array('validator' => 'StringLength', 'options' => array(5, 255))
        	)
        ));
        
        $this->addElement('text', 'destination', array(
        	'label'      => 'Destination:',
            'required'   => true,
        	'validators' => array(
        		array('validator' => 'StringLength', 'options' => array(1, 255))
        	)
        ));
		
        $this->addElement('text', 'startDate', array(
            'label'      => 'Start Date:',
            'required'   => true,
       		'filters'    => array('StringTrim'),
        	'validators' => array(
        		array('date', false, array('format' => 'YYYY-MM-dd')),
        	    array('DateCompare', false, array('endDate', false)))
        ));
        
        $this->addElement('text', 'endDate', array(
            'label'      => 'End Date:',
            'required'   => true,
       		'filters'    => array('StringTrim'),
        	'validators' => array(
        				array('date', false, array('format' => 'YYYY-MM-dd')),
        	            array('DateCompare', false, array('startDate', true)))
        ));
        
        $this->addElement('text', 'tagString', array(
        	'label'      => 'Tags:',
        	'required'   => true,
        	'filters'    => array('StringTrim'),
        	'validators' => array(
        		array('validator' => 'StringLength', 'options' => array(1, 255))
        	)
        ));
        
        $this->addElement('submit', 'submit', array(
        	'ignore'   => true,
            'label'    => 'Submit',
        ));
    }
}