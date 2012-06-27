<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: view.html.php 8418 2011-09-22 08:18:02Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.application.component.view');
class ImageShowViewFlex extends JView
{
	function display($tpl = null)
	{
		$task 		= JRequest::getVar('task');
		$showlistID = JRequest::getVar('showlist_id');
		$objJSNFlex = JSNISFactory::getObj('classes.jsn_is_flex');
		$folder 	= JRequest::getVar('folder');
		$sourceType = JRequest::getVar('source_type');
		$album = JRequest::getVar('album');

		switch ($task)
		{
			case 'getToken':
				echo $objJSNFlex->$task();
				jexit();
			break;
			case 'init':
				echo $objJSNFlex->$task($showlistID);
				jexit();
			break;
			case 'removeAllImagesByShowlistID':
				echo $objJSNFlex->$task($showlistID);
				jexit();
			break;
			case 'loadImageInFolder':
				echo $objJSNFlex->$task($folder);
				jexit();
			break;
			case 'addImages':
				echo $objJSNFlex->$task();
				jexit();
			break;
			case 'loadRemoteImage':
				echo $objJSNFlex->$task($showlistID, $album);
				jexit();
			break;
			case 'getImageInfo':
				echo $objJSNFlex->$task();
				jexit();
			break;
			case 'loadLanguage':
				echo $objJSNFlex->$task();
				jexit();
			break;
			case 'saveSyncAlbum':
				echo $objJSNFlex->$task();
				jexit();
			break;
			case 'loadImageSources':
				echo $objJSNFlex->$task();
				jexit();
			break;
			case 'createThumb':
				echo $objJSNFlex->$task();
				jexit();
			case 'checkThumb':
				echo $objJSNFlex->$task();
				jexit();
			case 'getScriptCheckThumb':
				echo $objJSNFlex->$task();
			jexit();
			case 'selectLinkItem':
				echo $objJSNFlex->$task();
			jexit();
			case 'selectLinkType':
				echo $objJSNFlex->$task();
			jexit();
			case 'loadLinkType':
				echo $objJSNFlex->$task();
			jexit();
			default:
				parent::display();
			break;
		}
	}
}