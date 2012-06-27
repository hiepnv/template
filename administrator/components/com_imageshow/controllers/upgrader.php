<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: upgrader.php 12585 2012-05-11 08:17:16Z hiennv $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.application.component.controller');
class ImageShowControllerUpgrader extends JController
{
	function __construct($config = array())
	{
		parent::__construct($config);
	}

	function display($cachable = false, $urlparams = false)
	{
		JRequest::setVar('hidemainmenu', 1);
		JRequest::setVar('layout', 'default');
		JRequest::setVar('view', 'upgrader');
		JRequest::setVar('model', 'upgrader');
		parent::display();
	}
	function authenticate()
	{
		JRequest::checkToken() or jexit('Invalid Token');
		$session 					= JFactory::getSession();
		$identifier					= md5('jsn_upgrader_jsn_imageshow');
		$identifierIsCustomer		= md5('jsn_upgrader_jsn_imageshow_is_customer');
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
		$link			= 'index.php?option=com_imageshow&controller=upgrader&step=2';
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
				if ($edition != 'free')
				{
					/* Standardize the returned array */
					if (!in_array('PRO UNLIMITED', $decodeToJSON->editions))
					{
						$msg = JText::_('UPGRADER_YOUR_ACCOUNT_IS_NOT_PROVIDED_WITH_UNLIMITED_EDITION');
						$type = 'error';
						$this->setRedirect($link, $msg, $type);
						return true;
					}
					else
					{
						$decodeToJSON->editions = array('PRO UNLIMITED');
					}

				}
				$session->set($identifier, array('success' => true, 'multiple' => true, 'message'=>'', 'editions'=>$decodeToJSON->editions, 'customer_password'=>$post['customer_password'], 'customer_username'=>$post['customer_username']), 'jsnimageshowsession');
				$session->set($identifierIsCustomer, true, 'jsnimageshowsession');
				if (count($decodeToJSON->editions) == 1)
				{
					$this->setRedirect('index.php?option=com_imageshow&controller=upgrader&step=3');
					return true;
				}
			}
		}
		else
		{
			$session->set($identifier, array('success' => false, 'multiple' => false, 'message'=>'', 'editions'=>array(), 'customer_password'=>'', 'customer_username'=>''), 'jsnimageshowsession');
			$msg = JText::sprintf('UPGRADER_CONNECT_FAILURE', JSN_IS_CUSTOMER_AREA);
			$type = 'error';
		}
		$this->setRedirect($link, $msg, $type);
		return true;
	}
}