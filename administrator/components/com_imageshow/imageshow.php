<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: imageshow.php 13385 2012-06-18 11:29:33Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined( '_JEXEC') or die( 'Restricted access');
global $mainframe;
$option = JRequest::getVar('option');
$task = JRequest::getVar('task');
if ($option != 'image' && $task != 'editimage')
{
	JHTML::_('behavior.mootools');
}
$mainframe  = JFactory::getApplication();
$user 		= JFactory::getUser();
require_once(JPATH_COMPONENT.DS.'controller.php');
require_once(JPATH_COMPONENT.DS.'defines.imageshow.php');
require_once(JPATH_COMPONENT.DS.'helpers'.DS.'media.php');
require_once(JPATH_COMPONENT.DS.'classes'.DS.'jsn_is_factory.php');
JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');
$controllerName = JRequest::getCmd('controller');

global $objectLog,$componentVersion;
$application  = JFactory::getApplication();
$templateName = $application->getTemplate();
$objComponent  = JSNISFactory::getObj('classes.jsn_is_utils');
$componentVersionInfo = $objComponent->getCoreInfo();
$componentVersion     = $componentVersionInfo->version;
$document    = JFactory::getDocument();
//$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/jquery/jquery.min.js?v='.$componentVersion);
//$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/conflict.js?v='.$componentVersion);
$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/bootstrap/bootstrap.min.css?v='.$componentVersion);
//$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/bootstrap/bootstrap.js?v='.$componentVersion);
if ($templateName == 'aplite') {
	JHTML::stylesheet('jsn_apilefix.css','administrator/components/com_imageshow/assets/css/');
}

$objShowcaseTheme = JSNISFactory::getObj('classes.jsn_is_showcasetheme');
$objectLog 		  = JSNISFactory::getObj('classes.jsn_is_log');
//get component version


$objShowcaseTheme->enableAllTheme();

if ($controller = JRequest::getWord('controller'))
{
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';

	if (file_exists($path)) {
		require_once $path;
	} else {
		$controller = '';
	}
}

$classname	= 'ImageShowController'.$controller;
$controller	= new $classname();
$controller->execute( JRequest::getVar( 'task'));
$controller->redirect();