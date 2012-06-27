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
class ImageShowViewShowcase extends JView
{
		function display($tpl = null)
		{
			global $mainframe, $option, $componentVersion;

			$objISUtils 	= JSNISFactory::getObj('classes.jsn_is_utils');
			$objJSNTheme	  = JSNISFactory::getObj('classes.jsn_is_themes');
			$document = JFactory::getDocument();
			$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/mooRainbow.1.2.js?v='.$componentVersion);
			$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/mooRainbow.css?v='.$componentVersion);
			$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/imageshow.css?v='.$componentVersion);
			$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_imageshow.js?v='.$componentVersion);
			$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_utils.js?v='.$componentVersion);
			$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_installshowcasethemes.js?v='.$componentVersion);
			$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_installdefault.js?v='.$componentVersion);
			$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_accordions.js?v='.$componentVersion);
			$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/accordion.css?v='.$componentVersion);

			JHTML::_('behavior.modal', 'a.modal');
			$lists 				= array();
			$format 			= JRequest::getVar('view_format', 'temporary');
			$showlist_id 		= JRequest::getInt('showlist_id');
			$showcaseTheme 		= JRequest::getVar('theme', 'showcasethemeclassic');
			$model	 			= $this->getModel();
			$items 				= $this->get('data');
			$session 			= JFactory::getSession();
			$overallWidthDimensionValue     = '%';
			$showcaseThemeSession 	= $session->get('showcaseThemeSession');
			$session->clear('showcaseThemeSession');

			// GENERAL TAB BEGIN
			if($showcaseThemeSession){
				$publishShowcase = $showcaseThemeSession['published'];
			}else if($items->published != ''){
				$publishShowcase = $items->published;
			}else{
				$publishShowcase = 1;
			}
			$lists['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $publishShowcase);

			$query 		= 'SELECT ordering AS value, showcase_title AS text'
			. ' FROM #__imageshow_showcase'
			. ' ORDER BY ordering';
			$lists['ordering'] 			= JHTML::_('list.specificordering',  $items, $items->showcase_id, $query );

			$generalImagesOrder= array(
				'0' => array('value' => 'forward',
				'text' => JText::_('SHOWCASE_GENERAL_FORWARD')),
				'1' => array('value' => 'backward',
				'text' => JText::_('SHOWCASE_GENERAL_BACKWARD')),
				'2' => array('value' => 'random',
				'text' => JText::_('SHOWCASE_GENERAL_RANDOM'))
			);

			$dimension  = array(
				'0' => array('value' => 'px',
				'text' => JText::_('px')),
				'1' => array('value' => '%',
				'text' => JText::_('%'))
			);

			// GENERAL TAB END

			$generalData = array();

			if(!empty($showcaseThemeSession))
      		{
      			$generalData['generalTitle'] 			= $showcaseThemeSession['showcase_title'];
      			$generalData['generalWidth'] 			= $showcaseThemeSession['general_overall_width'].$showcaseThemeSession['overall_width_dimension'];
      			$generalData['generalHeight'] 			= $showcaseThemeSession['general_overall_height'];
      		}
      		else if($items->general_overall_width)
      		{
      			$generalData['generalTitle'] 			= htmlspecialchars($items->showcase_title);
      			$generalData['generalWidth'] 			= $items->general_overall_width;
      			$generalData['generalHeight'] 			= $items->general_overall_height;
      		}
      		else
      		{
      			$generalData['generalTitle'] 			= '';
      			$generalData['generalWidth'] 			= '100%';
      			$generalData['generalHeight'] 			= '450';
      		}

			$overallWith = $generalData['generalWidth'];
			$posPercentageOverallWidth = strpos($overallWith, '%');

			if ($posPercentageOverallWidth)
			{
				$overallWith 	= substr($overallWith, 0, $posPercentageOverallWidth + 1);
				$overallWidthDimensionValue = "%";
			}
			else
			{
				$overallWith = (int) $overallWith;
				$overallWidthDimensionValue = "px";
			}

			$lists['overallWidthDimension'] = JHTML::_('select.genericList', $dimension, 'overall_width_dimension', 'class="inputbox" onchange="checkOverallWidth();" '. '', 'value', 'text', $overallWidthDimensionValue );


			$remoteTheme 	 = $objJSNTheme->compareSources();
			$needInstallList = $objJSNTheme->getNeedInstallList($remoteTheme);
			$localTheme 	 = $objJSNTheme->compareLocalSources();
			$needUpdateList	 = $objJSNTheme->getNeedUpdateList($localTheme);
			$canAutoDownload = true;
			$objJSNUtils 	 = JSNISFactory::getObj('classes.jsn_is_utils');

			if (!$objJSNUtils->checkEnvironmentDownload()) {
				$canAutoDownload = false;
			}

			$this->assignRef('canAutoDownload', $canAutoDownload);
			$this->assignRef('needUpdateList', $needUpdateList);
			$this->assignRef('needInstallList', $needInstallList);
			$this->assignRef('generalData', $generalData);
			$this->assignRef('lists', $lists);
			$this->assignRef('items', $items);

			parent::display($tpl);
		}
}
?>