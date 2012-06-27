<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: view.showcase.php 8411 2011-09-22 04:45:10Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.application.component.view');
class ImageShowViewShow extends JView
{
	function display($tpl = null)
	{
		$showCaseID 	= JRequest::getInt('showcase_id', 0);
		$objUtils		= JSNISFactory::getObj('classes.jsn_is_utils');
		$URL   			= $objUtils->overrideURL();
		$objJSNShowcase	= JSNISFactory::getObj('classes.jsn_is_showcase');
		$row 			= $objJSNShowcase->getShowCaseByID($showCaseID);

		if(count($row) <=0){
			header("HTTP/1.0 404 Not Found");
			exit();
		}

		$objJSNJSON	= JSNISFactory::getObj('classes.jsn_is_json');
		$dataObj 	= $objJSNShowcase->getShowcase2JSON($row, $URL);

		echo $objJSNJSON->encode($dataObj);

		jexit();
	}
}
