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
class ImageShowViewCpanel extends JView
{
		function display($tpl = null)
		{
			global $mainframe, $option, $componentVersion;
			$document = JFactory::getDocument();
			$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/imageshow.css?v='.$componentVersion);
			$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_imageshow.js?v='.$componentVersion);
			$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/ZeroClipboard.js?v='.$componentVersion);

			JHTML::_('behavior.modal', 'a.modal');
			$lists 			= array();
			$model			= $this->getModel();
			$document 		= JFactory::getDocument();
			$objJSNXML 		= JSNISFactory::getObj('classes.jsn_is_readxmldetails');
			$infoXmlDetail  = $objJSNXML->parserXMLDetails();
			$objJSNShowcase = JSNISFactory::getObj('classes.jsn_is_showcase');
			$objJSNShowlist = JSNISFactory::getObj('classes.jsn_is_showlist');
			$objJSNLog 		= JSNISFactory::getObj('classes.jsn_is_log');
			$objJSNUtils	= JSNISFactory::getObj('classes.jsn_is_utils');
			$objJSNTip 		= JSNISFactory::getObj('classes.jsn_is_tip');
			$totalShowlist 	= $objJSNShowlist->countShowlist();
			$totalShowcase 	= $objJSNShowcase->getTotalShowcase();
//			$totalProfile 	= $objJSNUtils->getTotalProfile();
			$allContentTips = $objJSNTip->getAllContentTips($document->getLanguage());
			$objJSNLog->deleteRecordLog();

			$checkModule 			= $objJSNUtils->checkIntallModule();
			$checkPluginContent 	= $objJSNUtils->checkIntallPluginContent();
			$checkPluginSystem 		= $objJSNUtils->checkIntallPluginSystem();

			if ($checkModule == false || $checkPluginContent == false || $checkPluginSystem == false)
			{
				if ($checkModule == false)
				{
					$msgNotice [] = '<li>&nbsp;&nbsp;-&nbsp;&nbsp;'.JText::_('CPANEL_JSN_IMAGESHOW_MODULE').'</li>';
				}

				if ($checkPluginSystem == false)
				{
					$msgNotice [] = '<li>&nbsp;&nbsp;-&nbsp;&nbsp;'.JText::_('CPANEL_JSN_IMAGESHOW_SYSTEM_PLUGIN').'</li>';
				}

				if ($checkPluginContent == false)
				{
					$msgNotice [] = '<li>&nbsp;&nbsp;-&nbsp;&nbsp;'.JText::_('CPANEL_JSN_IMAGESHOW_CONTENT_PLUGIN').'</li>';
				}

				$strMsg = implode('', $msgNotice);

				JError::raiseWarning(100, JText::sprintf('CPANEL_FOLLOWING_ELEMENTS_ARE_NOT_INSTALLED', $strMsg));
			}

			$presentationMethods = array(
				'0' => array('value' => '',
				'text' => '- '.JText::_('CPANEL_SELECT_PRESENTATION_METHOD').' -'),
				'1' => array('value' => 'menu',
				'text' => JText::_('CPANEL_VIA_MENU_ITEM_COMPONENT')),
				'2' => array('value' => 'module',
				'text' => JText::_('CPANEL_IN_MODULE_POSITION_MODULE')),
				'3' => array('value' => 'plugin',
				'text' => JText::_('CPANEL_INSIDE_ARTICLE_CONTENT_PLUGIN'))
			);

			$lists['presentationMethods'] 	= JHTML::_('select.genericList', $presentationMethods, 'presentation_method', 'class="jsn-gallery-selectbox" onchange="choosePresentMethode();" disabled="disabled"'. '', 'value', 'text', "" );
			$pluginContentInfo 				= $objJSNUtils->getPluginContentInfo();
			$languageObj 					= $document->getLanguage();
			$lists['showlist'] 				= $objJSNShowlist->renderShowlistComboBox('0', JText::_('CPANEL_SELECT_SHOWLIST'), 'showlist_id', 'class="jsn-gallery-selectbox" onchange="enableButton();"');
			$lists['showcase'] 				= $objJSNShowcase->renderShowcaseComboBox('0', JText::_('CPANEL_SELECT_SHOWCASE'), 'showcase_id', 'class="jsn-gallery-selectbox" onchange="enableButton();"');
			$lists['menu'] 					= $objJSNUtils->renderMenuComboBox(null, 'Select menu' ,'menutype', 'class="jsn-gallery-selectbox jsn-menutype-selection" onchange="createViaMenu(false);"');

			$this->assignRef('pluginContentInfo', $pluginContentInfo);
			$this->assignRef('totalShowlist', $totalShowlist[0]);
			$this->assignRef('totalShowcase', $totalShowcase[0]);
//			$this->assignRef('totalProfile', $totalProfile[0]);
			$this->assignRef('currentLanguage',	$languageObj);
			$this->assignRef('lists',	$lists);
			$this->assignRef('allContentTips', $allContentTips);
			$this->assignRef('infoXmlDetail', $infoXmlDetail);
			$this->assignRef('objJSNUtils', $objJSNUtils);
			parent::display($tpl);
		}
}