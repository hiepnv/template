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
jimport('joomla.application.component.view');
class ImageShowViewUpgrader extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $option, $componentVersion;
		$document = JFactory::getDocument();
		$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_imageshow.js?v='.$componentVersion);
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/imageshow.css?v='.$componentVersion);
		$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_accordions.js?v='.$componentVersion);
		$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_installdefault.js?v='.$componentVersion);
		$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_installmanual.js?v='.$componentVersion);
		$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_upgrader.js?v='.$componentVersion);
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/accordion.css?v='.$componentVersion);

		$objJSNUtil			= JSNISFactory::getObj('classes.jsn_is_utils');
		$objVersion			= new JVersion();
		$objJSNLightCart  	= JSNISFactory::getObj('classes.jsn_is_lightcart');
		$infoCore 			= $objJSNUtil->getCoreInfo();
		$remoteInfoCore 	= $objJSNUtil->getRemoteElementInfor($infoCore->id, '');
		$paramsLang 		= JComponentHelper::getParams('com_languages');
		$adminLang 			= $paramsLang->get('administrator', 'en-GB');
		$edition				= $objJSNUtil->getEdition();
		$core 							= new stdClass();
		$core->identify_name 			= strtolower($infoCore->id);
		$core->edition 					= '';
		$core->joomla_version 			= $objVersion->RELEASE;
		$core->wait_text				= JText::_('UPGRADER_UPGRADE_INSTALL_WAIT_TEXT', true);
		$core->process_text				= JText::_('UPGRADER_UPGRADE_INSTALL_PROCESS_TEXT', true);
		$core->based_identified_name	= '';
		$core->error_code		  		= $objJSNLightCart->getErrorCode('upgrader');
		$core->manual_download_text 	  = JText::_('MANUAL_DOWNLOAD', true);
		$core->manual_install_button 	  = JText::_('MANUAL_INSTALL', true);
		$core->manual_then_select_it_text = JText::_('MANUAL_THEN_SELECT_IT', true);
		$core->dowload_installation_package_text = JText::_('MANUAL_DOWNLOAD_INSTALLATION_PACKAGE', true);
		$core->select_download_package_text = JText::_('MANUAL_SELECT_DOWNLOADED_PACKAGE', true);
		$core->downloadLink				  = JSN_IMAGESHOW_AUTOUPDATE_URL;
		$core->language					  = $adminLang;
		$canAutoDownload 				  = $objJSNUtil->checkEnvironmentDownload();
		$this->assignRef('edition', $edition);
		$this->assignRef('objJSNUtil', $objJSNUtil);
		$this->assignRef('canAutoDownload', $canAutoDownload);
		$this->assignRef('core', $core);
		$this->assignRef('remoteInfoCore', $remoteInfoCore);
		parent::display($tpl);
	}
}