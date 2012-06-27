<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: view.html.php 13385 2012-06-18 11:29:33Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class ImageShowViewMaintenance extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $option, $componentVersion;
		JHTML::_('behavior.modal','a.modal');
		JHTML::_('behavior.modal','a.jsn-modal');
		$document = JFactory::getDocument();

		$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/jquery/jquery.min.js?v='.$componentVersion);
		$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/conflict.js?v='.$componentVersion);
		$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/bootstrap/bootstrap.js?v='.$componentVersion);

		$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_imageshow.js?v='.$componentVersion);
		$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_sampledata.js?v='.$componentVersion);
		$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_sampledatamanual.js?v='.$componentVersion);
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/imageshow.css?v='.$componentVersion);
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/accordion.css?v='.$componentVersion);
		$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_installimagesources.js?v='.$componentVersion);
		$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_installshowcasethemes.js?v='.$componentVersion);
		$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_installdefault.js?v='.$componentVersion);
		$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_accordions.js?v='.$componentVersion);

		$screen				= $mainframe->getUserStateFromRequest('com_imageshow.maintenance.msg_screen', 'msg_screen', '', 'string');
		$profileTitle 		= $mainframe->getUserStateFromRequest('com_imageshow.maintenance.configuration_title', 'config_title', '', 'string');
		$profileSource		= $mainframe->getUserStateFromRequest('com_imageshow.maintenance.img_source', 'img_source', '', 'string');

		$lists 		= array();
		$type  		= JRequest::getWord('type','backup');
		$model 		= $this->getModel();
		$objJSNProfile = JSNISFactory::getObj('classes.jsn_is_profile');
		$objJSNMsg 	= JSNISFactory::getObj('classes.jsn_is_displaymessage');
		$task		= JRequest::getWord('task','');
		if ($task != 'login')
		{
			switch($type)
			{
				case 'inslangs':
					$objJSNLang	 = JSNISFactory::getObj('classes.jsn_is_language');
					$arrayFolder = $objJSNLang->megerArrayFolder();
					$this->assignRef('arrayFolder', $arrayFolder);
				break;
				case 'msgs':
					$arrayScreen 			= $objJSNMsg->listScreenDisplayMsg();
					$lists['arrayScreen'] 	= JHTML::_('select.genericList', $arrayScreen, 'msg_screen', 'class="inputbox" onchange="document.adminForm.submit();"'. '', 'value', 'text', $screen);
					$getMessages 			= $objJSNMsg->getMessages($screen);
					$this->assignRef('messages', $getMessages);
					$this->assignRef('screen', $screen);
				break;
				case 'profiles':
					jimport('joomla.html.pagination');
					$limitStart = $mainframe->getUserStateFromRequest('com_imageshow.sourceManager.limitstart', 'limitstart', 0, 'int');
					$limit 		= $mainframe->getUserStateFromRequest('com_imageshow.sourceManager.limit', 'limit', 0, 'int');

					$objJSNSource = JSNISFactory::getObj('classes.jsn_is_source');
					$listSources  = $objJSNSource->getListSources();
					$arraySources = array();

					if ($limit != 0) {
						$count = (($limit + $limitStart) > count($listSources)) ? count($listSources) - 1 :  ($limit + $limitStart);
					} else {
						$count = count($listSources) - 1;
					}

					for ($i = $limitStart; $i < $count; $i++)
					{
						$source = $listSources[$i];
						if ($source->type == ('external' || 'internal')) {
							$source->profiles = $objJSNProfile->getProfiles($profileTitle, $source->identified_name);
						}

						$arraySources[] = $source;
					}

					$lists['profileTitle'] = $profileTitle;

					$this->pagination = new JPagination(count($arraySources), $limitStart, $limit);
					$this->assignRef('listSources', $arraySources);
				break;
				case 'editprofile':
					$sourceID 		= JRequest::getInt('external_source_id');
					$countShowlist	= JRequest::getInt('count_showlist', 0);
					$source 		= JRequest::getString('source_type');
					$imageSource	= JSNISFactory::getSource($source, 'external');
					$imageSource->_source['sourceTable']->load($sourceID);
					$this->assignRef('sourceInfo', $imageSource->_source['sourceTable']);
					$this->assignRef('countShowlist', $countShowlist);
				break;
				case 'configs':
					$parameters 		= $objJSNProfile->getParameters();
					$objJSNUtils 		= JSNISFactory::getObj('classes.jsn_is_utils');
					$modQuickIconInfo 	= $objJSNUtils->getModuleInformation('mod_imageshow_quickicon');
					$showQuickIcons		= (@$parameters->show_quick_icons == '')?'1':@$parameters->show_quick_icons;
					$jshowQuickIcons	= isset($modQuickIconInfo->published)?$modQuickIconInfo->published:'1';
					if ($jshowQuickIcons != $showQuickIcons)
					{
						$post['show_quick_icons'] = $jshowQuickIcons;
						$objJSNProfile = JSNISFactory::getObj('classes.jsn_is_profile');
						$objJSNProfile->saveParameters($post);
					}
					$parameters 					= $objJSNProfile->getParameters();
					if (is_null(@$parameters->show_quick_icons))
					{
						$show = '1';
					}
					else
					{
						$show = @$parameters->show_quick_icons;
					}
					$lists['showQuickIcons'] 		= JHTML::_('select.booleanlist', 'show_quick_icons','class="inputbox"', $show);
					$this->assignRef('parameters', $parameters);
				break;
				case 'data':
					$objJSNUtils 		= JSNISFactory::getObj('classes.jsn_is_utils');
					$sampleData 		= JSNISFactory::getObj('classes.jsn_is_sampledata');
					$objReadXmlDetail	= JSNISFactory::getObj('classes.jsn_is_readxmldetails');
					$inforPackage 		= $objReadXmlDetail->parserXMLDetails();
					$sampleData->getPackageVersion(trim(strtolower($inforPackage['realName'])));
					$objJSNUtils->checkTmpFolderWritable();
				break;
				case 'themes':
					$filterState 		= $mainframe->getUserStateFromRequest('com_imageshow.themesManager.filter_state', 'filter_state', '', 'word');
					$filterOrder		= $mainframe->getUserStateFromRequest('com_imageshow.themesManager.filter_order','filter_order', '', 'cmd');
					$filterOrderDir		= $mainframe->getUserStateFromRequest('com_imageshow.themesManager.filter_order_Dir',	'filter_order_Dir',	'',	'word');
					//$filterPluginName	= $mainframe->getUserStateFromRequest('com_imageshow.themesManager.plugin_name', 'plugin_name', '', 'string');

					$lists['state']		= JHTML::_('grid.state',  $filterState);
					$lists['order_Dir'] = $filterOrderDir;
					$lists['order'] 	= $filterOrder;
					$pluginModel 		= JModel::getInstance('plugins', 'imageshowmodel');

					$listJSNPlugins		= $pluginModel->getData();
					$pagination 		= $pluginModel->getPagination();

					$this->assignRef('lists', $lists);
					$this->assignRef('pagination', $pagination);
					//$this->assignRef('filterPluginName', $filterPluginName);
					$this->assignRef('listJSNPlugins', $listJSNPlugins);
				break;
				case 'themeparameters':
				break;
				default:
					$mainframe->redirect('index.php?option=com_imageshow&controller=maintenance&type=configs');
			    break;
			}
		}
		$this->assignRef('lists', $lists);
		parent::display($tpl);
	}
}