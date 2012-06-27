<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: updater.php 13228 2012-06-12 09:18:53Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
class ImageShowControllerUpdater extends JController
{
	function __construct($config = array())
	{
		parent::__construct($config);
	}

	function display($cachable = false, $urlparams = false)
	{
		$layout = JRequest::getString('layout', 'default');
		JRequest::setVar('layout', $layout);
		JRequest::setVar('view', 'updater');
		parent::display();
	}

	function install()
	{
		JRequest::checkToken() or jexit('Invalid Token');
		$hash    		= JUtility::getHash('JSN_IMAGESHOW_'.@$_SERVER['HTTP_USER_AGENT']);
		$session 		= JFactory::getSession();
		if($session->has($hash))
		{
			$session->clear($hash);
		}
		$type 			= JRequest::getVar('type');
		$elementID 		= JRequest::getInt('element_id');
		$commercial	 	= JRequest::getVar('commercial');
		$link = 'index.php?option=com_imageshow&controller=updater&type='.$type.'&element_id='.$elementID.'&commercial='.$commercial;

		$model	= $this->getModel('installer');
		switch ($type)
		{
			case 'core':
				$result = $model->installComponent();
				if (!$result)
				{
					$this->setRedirect($link);
				}
			break;
			case 'theme':
				$result = $model->install();
				if (!$result)
				{
					$this->setRedirect($link);
				}
				else
				{
					$this->setRedirect('index.php?option=com_imageshow&controller=updater');
				}
			break;
			default:
				$this->setRedirect($link);
			break;
		}
	}

	function authenticate()
	{
		JRequest::checkToken() or jexit('Invalid Token');
		$session 					= JFactory::getSession();
		$identifier					= md5('jsn_updater_jsn_imageshow');
		$identifierIsCustomer		= md5('jsn_updater_jsn_imageshow_is_customer');
		$session->set($identifier, array(), 'jsnimageshowsession');
		$session->set($identifierIsCustomer, false, 'jsnimageshowsession');
		$objJSNUtil					= JSNISFactory::getObj('classes.jsn_is_utils');
		$objJSNLightCart  			= JSNISFactory::getObj('classes.jsn_is_lightcart');
		$errorCode		  			= $objJSNLightCart->getErrorCode('upgrader');
		$post 						= JRequest::get('post');
		$post['customer_password']	= JRequest::getVar('customer_password', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$objVersion 	= new JVersion();
		$link			= JSN_IMAGESHOW_AUTOUPDATE_URL.'&identified_name='.urlencode($post['identify_name']).'&based_identified_name='.urlencode($post['based_identified_name']).'&edition='.urlencode($post['edition']).'&joomla_version='.urlencode($objVersion->RELEASE).'&username='.urlencode($post['customer_username']).'&password='.urlencode($post['customer_password']).'&upgrade=no';
		$objJSNHTTP   	= JSNISFactory::getObj('classes.jsn_is_httprequest', null, $link);
		$result    		= $objJSNHTTP->DownloadToString();
		$link			= 'index.php?option=com_imageshow&controller=updater&step=2';
		$msg			= '';
		$type			= '';
		$edition		= $objJSNUtil->getEdition();
		if ($result)
		{
			$decodeToJSON = json_decode($result);
			if (is_null($decodeToJSON))
			{
				$session->set($identifier, array('success' => false, 'multiple' => false, 'message'=>(string) $result, 'editions'=>array(), 'customer_password'=>'', 'customer_username'=>''), 'jsnimageshowsession');
				$msg = $errorCode[(string) $result];
				$type = 'error';
			}
			else
			{
				$session->set($identifier, array('success' => true, 'multiple' => true, 'message'=>'', 'customer_password'=>$post['customer_password'], 'customer_username'=>$post['customer_username']), 'jsnimageshowsession');
				$session->set($identifierIsCustomer, true, 'jsnimageshowsession');
				$this->setRedirect('index.php?option=com_imageshow&controller=updater&step=3');
				return true;
			}
		}
		else
		{
			$session->set($identifier, array('success' => false, 'multiple' => false, 'message'=>'', 'customer_password'=>'', 'customer_username'=>''), 'jsnimageshowsession');
			$msg = JText::sprintf('UPGRADER_CONNECT_FAILURE', JSN_IS_CUSTOMER_AREA);
			$type = 'error';
		}
		$this->setRedirect($link, $msg, $type);
		return true;
	}
}
?>