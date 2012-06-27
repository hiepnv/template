<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: view.html.php 13017 2012-06-04 11:04:16Z haonv $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class ImageShowViewMediaImagesList extends JView
{
	function display($tpl = null)
	{
		global $mainframe,$componentVersion;
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/popup-imagemanager.css?v='.$componentVersion);		
		
		$document->addScriptDeclaration("var JSNISImageManager = window.parent.JSNISImageManager;");

		$objJSNMediaManager = JSNISFactory::getObj('classes.jsn_is_mediamanager');
		$images 			= $objJSNMediaManager->getImages();
		$folders 			= $objJSNMediaManager->getFolders();
		
		$this->assign('baseURL', $objJSNMediaManager->comMediaBaseURL);
		$this->assignRef('images', $images);
		$this->assignRef('folders', $folders);
		parent::display($tpl);
	}
}