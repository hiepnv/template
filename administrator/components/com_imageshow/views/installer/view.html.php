<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: view.html.php 13024 2012-06-04 13:14:53Z haonv $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class ImageShowViewInstaller extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $option, $componentVersion;
		$this->model = $this->getModel();
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/imageshow.css?rand='.time());
		$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_imageshow.js?v='.$componentVersion);
		$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_installdefault.js?v='.$componentVersion);
		$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_installmanual.js?v='.$componentVersion);
		$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_utils.js?v='.$componentVersion);

		$objJSNXML 		= JSNISFactory::getObj('classes.jsn_is_readxmldetails');
		$objJSNPlugin 	= JSNISFactory::getObj('classes.jsn_is_plugins');
		$listJSNPluginNeedInstall = $objJSNPlugin->getListJSNPluginNeedInstall();
		$infoXmlDetail    		  = $objJSNXML->parserXMLDetails();

		$objJSNUtils 		= JSNISFactory::getObj('classes.jsn_is_utils');
		$enviromentDownload = $objJSNUtils->checkEnvironmentDownload();

		$params = JComponentHelper::getParams('com_languages');
		$adminLang = $params->get('administrator', 'en-GB');

		$this->assignRef('adminLang', $adminLang);
		$this->assignRef('infoXmlDetail',$infoXmlDetail);
		$this->assignRef('listJSNPluginNeedInstall', $listJSNPluginNeedInstall);
		$this->assignRef('environmentDownload', $enviromentDownload);

		parent::display($tpl);
	}
}