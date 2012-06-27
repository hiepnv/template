<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: about.php 8411 2011-09-22 04:45:10Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.application.component.controller' );

class ImageShowControllerAbout extends JController 
{
	function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	function display($cachable = false, $urlparams = false) 
	{		
		JRequest::setVar( 'layout', 'default' );
		JRequest::setVar( 'view', 'about' );
		parent::display();	
	}
}
?>