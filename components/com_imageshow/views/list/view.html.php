<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: view.html.php 13017 2012-06-04 11:04:16Z haonv $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.view');
class ImageShowViewList extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $option;
		JHTML::_('behavior.mootools');
		JHTML::_('behavior.modal', 'a.modal');
		$objComponent  = JSNISFactory::getObj('classes.jsn_is_utils');
		$componentVersionInfo = $objComponent->getCoreInfo();
		$componentVersion     = $componentVersionInfo->version;
		$doc 	= JFactory::getDocument();
		$doc->addStyleSheet(JURI::root(true).'/components/com_imageshow/assets/css/imageshow.css?v='.$componentVersion);
		$doc->addStyleSheet(JURI::root(true).'/components/com_imageshow/assets/css/style.css?v='.$componentVersion);
		$objJSNShowlist	= JSNISFactory::getObj('classes.jsn_is_showlist');
		$objJSNShowcase	= JSNISFactory::getObj('classes.jsn_is_showcase');
		$objJSNImages	= JSNISFactory::getObj('classes.jsn_is_images');
		$app 			=& JFactory::getApplication('site');
		$menu_params 	= $app->getParams('com_imageshow');
		$menus			= $app->getMenu();
		$menu 			= $menus->getActive();
		$itemid			= @$menu->id;
		$showlists			= $objJSNShowlist->getShowlistIDs($menu_params->get('showlist_id'));
		$showcase			= $objJSNShowcase->getShowCaseByID($menu_params->get('showcase_id'));
		$modalDimension		= $menu_params->get('dimension_modal');
		$modalDimension		= @explode(',', $modalDimension);

		$viewType = $menu_params->get('view_type', 'new-page');
		$menuLayout = $menu_params->get('layout', 'thumbnails');

		if ($viewType == 'modal-window')
		{
			$width	= (int) @$modalDimension[0];
			$height	= (int) @$modalDimension[1];
		}
		else
		{
			$width 	= '0';
			$height	= '0';
		}

		if ($width == '0' || $width == '')
		{
			$width 	= @$showcase->general_overall_width;
		}

		if ($height == '0' || $height == '')
		{
			$height = (int) @$showcase->general_overall_height;
		}

		if ($viewType == 'modal-window')
		{
			if (strpos($width, '%'))
			{
				$width = (int) '650';
			}
		}

		if (!strpos($width, '%'))
		{
			$width = (int) $width;
		}
		$this->assignRef('viewType', $viewType);
		$this->assignRef('width', $width);
		$this->assignRef('height', $height);
		$this->assignRef('showlists', $showlists);
		$this->assignRef('showcase', $showcase);
		$this->assignRef('itemid', $itemid);
		$this->assignRef('objJSNImages', $objJSNImages);
		$this->assignRef('menuParams', $menu_params);
		$this->assignRef('menuLayout', $menuLayout);
		parent::display($tpl);
	}
}