<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: view.html.php 13228 2012-06-12 09:18:53Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.application.component.view');
class ImageShowViewUpdater extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $option, $componentVersion;
		$document = JFactory::getDocument();
		$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_imageshow.js?v='.$componentVersion);
		$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_updater.js?v='.$componentVersion);
		$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_installdefault.js?v='.$componentVersion);
		$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_installmanual.js?v='.$componentVersion);
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/imageshow.css?v='.$componentVersion);

		$objVersion 	= new JVersion();
		$objJSNXML 		= JSNISFactory::getObj('classes.jsn_is_readxmldetails');
		$objJSNUtil		= JSNISFactory::getObj('classes.jsn_is_utils');
		$infoXmlDetail  = $objJSNXML->parserXMLDetails();
		$objJSNThemes   = JSNISFactory::getObj('classes.jsn_is_themes');
		$objJSNSources  = JSNISFactory::getObj('classes.jsn_is_source');
		$sources 		= $objJSNSources->compareSources(true);
		$themes 		= $objJSNThemes->compareSources();
		$core 			= $objJSNUtil->getCoreInfo();
		$canAutoUpdate 	= $objJSNUtil->checkEnvironmentDownload();

		$this->assignRef('imageshowCore', $core);
		$this->assignRef('themes', $themes);
		$this->assignRef('sources', $sources);
		$this->assignRef('infoXmlDetail',$infoXmlDetail);
		$this->assignRef('objVersion',$objVersion);
		$this->assignRef('objJSNUtil',$objJSNUtil);
		$this->assignRef('canAutoUpdate', $canAutoUpdate);
		parent::display($tpl);
	}
}