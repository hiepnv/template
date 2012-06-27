<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: view.showlist.php 6505 2011-05-31 11:01:31Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.application.component.view');
class ImageShowViewShowLists extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $option, $Itemid;	
		jimport('joomla.utilities.simplexml');
		
		$objJSNUtils		= JSNISFactory::getObj('classes.jsn_is_utils');
		$URL				= dirname($objJSNUtils->overrideURL()).'/';
		$objJSNShowlist		= JSNISFactory::getObj('classes.jsn_is_showlist');
		$objJSNJSON 		= JSNISFactory::getObj('classes.jsn_is_json');
		$objJSNShowcase		= JSNISFactory::getObj('classes.jsn_is_showcase');
		$showlistID 		= JRequest::getVar('showlist_id');		
		$dataObj 			= $objJSNShowlist->getShowlist2JSON($URL, $showlistID);
		
		echo $objJSNJSON->encode($dataObj);
		jexit();
	}
}
