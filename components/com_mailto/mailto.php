<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_mailto
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');
jimport('joomla.application.component.helper');

require_once JPATH_COMPONENT.'/helpers/mailto.php';
require_once JPATH_COMPONENT.'/controller.php';

$controller = JController::getInstance('Mailto');
$controller->registerDefaultTask('mailto');
$controller->execute(JRequest::getCmd('task'));

//$controller->redirect();
