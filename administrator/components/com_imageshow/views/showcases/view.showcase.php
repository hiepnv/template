<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: view.showcase.php 9308 2011-11-01 03:52:53Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.application.component.view');
class ImageShowViewShowCases extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $option;

		$document 	= JFactory::getDocument();
		$document->setMimeEncoding( 'application/json' );

		$showcaseID  	= JRequest::getVar('showcase_id');
		$objJSNShowcase = JSNISFactory::getObj('classes.jsn_is_showcase');
		$objShowcaseTheme 	= JSNISFactory::getObj('classes.jsn_is_showcasetheme');
		$themeProfile 		= $objShowcaseTheme->getThemeProfile($showcaseID);

		if ($showcaseID > 0 && !is_null($themeProfile))
		{
			$showcaseData = $objJSNShowcase->getShowcaseByID($showcaseID);
		}
		elseif ($showcaseID > 0 && is_null($themeProfile))
		{
			$theme 			= JRequest::getVar('theme');
			$showcaseTable  = JTable::getInstance('showcase', 'Table');
			$showcaseTable->showcase_id = $showcaseID;
			$showcaseTable->theme_name = $theme;
			$showcaseData 	= $showcaseTable;
		}
		else// default
		{
			$theme 			= JRequest::getVar('theme');
			$showcaseTable  = JTable::getInstance('showcase', 'Table');
			$showcaseTable->showcase_id = 0;
			$showcaseTable->theme_name = $theme;
			$showcaseData 	= $showcaseTable;
		}

		$objJSNUtils	= JSNISFactory::getObj('classes.jsn_is_utils');
		$URL   			= dirname($objJSNUtils->overrideURL()).'/';

		$objJSNJSON		= JSNISFactory::getObj('classes.jsn_is_json');
		$dataObj 		= $objJSNShowcase->getShowcase2JSON($showcaseData, $URL);

		echo $objJSNJSON->encode($dataObj);

		jexit();
	}
}
