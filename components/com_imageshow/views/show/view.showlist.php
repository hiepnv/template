<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: view.showlist.php 8445 2011-09-23 07:23:09Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class ImageShowViewShow extends JView
{
	function display($tpl = null)
	{
		$showlistID 		= JRequest::getInt('showlist_id', 0);
		$objUtils			= JSNISFactory::getObj('classes.jsn_is_utils');
		$URL				= $objUtils->overrideURL();
		$objJSNShowlist		= JSNISFactory::getObj('classes.jsn_is_showlist');
		$showlistInfo 		= $objJSNShowlist->getShowListByID($showlistID, true);

		if (!count($showlistInfo))
		{
			header("HTTP/1.0 404 Not Found");
			exit();
		}

		$objJSNShowlist->insertHitsShowlist($showlistID);
		$objJSNJSON = JSNISFactory::getObj('classes.jsn_is_json');
		$dataObj 	= $objJSNShowlist->getShowlist2JSON($URL, $showlistID);

		switch ($showlistInfo['image_loading_order'])
		{
			case 'backward':
				krsort($dataObj->showlist->images->image);
				$tmpImageArray = $dataObj->showlist->images->image;
				$dataObj->showlist->images->image = array_values($tmpImageArray);
				break;
			case 'random':
				shuffle($dataObj->showlist->images->image);
				break;
			case 'forward':
			default:
				ksort($dataObj->showlist->images->image);
				break;
		}
		echo $objJSNJSON->encode($dataObj);
		jexit();
	}
}
