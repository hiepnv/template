<?php

defined('_JEXEC') or die;
jimport('joomla.application.component.controller');

class ImageShowControllerModules extends JController
{
	function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	function display($cachable = false, $urlparams = false) 
	{		
		require_once JPATH_COMPONENT.'/helpers/modules.php';
		
		switch($this->getTask())
		{
			default:			
				JRequest::setVar('layout', 'default');
				JRequest::setVar('view', 'modules');
				JRequest::setVar('model', 'modules');			
		}
		parent::display();	
	}
}
