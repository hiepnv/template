<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: ajax.php 12008 2012-04-03 11:24:16Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.application.component.controller' );

class ImageShowControllerAjax extends JController
{
	function __construct($config = array())
	{
		parent::__construct($config);
	}

	function display($cachable = false, $urlparams = false)
	{
		JRequest::setVar('layout', 'default');
		JRequest::setVar('view', 'ajax');
		parent::display();
	}

	function checkUpdate()
	{
		$name 		 	= JRequest::getVar('name');
		$edition 	 	= JRequest::getVar('edition');
		$objJSNUtil     = JSNISFactory::getObj('classes.jsn_is_utils');
		$link		 	= JSN_IMAGESHOW_INFO_URL.'&identified_name='.urlencode($name).'&edition='.urlencode($edition);
		$objJSNHTTP   	= JSNISFactory::getObj('classes.jsn_is_httprequest', null, $link);
		$objJSNJSON     = JSNISFactory::getObj('classes.jsn_is_json');
		$result    		= $objJSNHTTP->DownloadToString();

		if (!$result)
		{
			echo $objJSNJSON->encode(array('connection' => false, 'version' => '', 'commercial'=>''));
			exit();
		}
		else
		{
			$parse = $objJSNJSON->decode($result);
			echo $objJSNJSON->encode(array('connection' => true, 'version' => @$parse->version, 'commercial' => @$parse->authentication));
			exit();
		}
	}

	function authenthicateCustomerInfo()
	{
		$post 			= JRequest::get('post');
		$objVersion 	= new JVersion();
		$link			= JSN_IMAGESHOW_AUTOUPDATE_URL.'&identified_name='.urlencode($post['identify_name']).'&based_identified_name='.urlencode($post['based_identified_name']).'&edition='.urlencode($post['edition']).'&joomla_version='.urlencode($objVersion->RELEASE).'&username='.urlencode($post['username']).'&password='.urlencode($post['password']).'&upgrade=no';
		$objJSNHTTP   	= JSNISFactory::getObj('classes.jsn_is_httprequest', null, $link);
		$result    		= $objJSNHTTP->DownloadToString();

		if ($result)
		{
			$decodeToJSON = json_decode($result);

			if (is_null($decodeToJSON))
			{
				echo json_encode(array('success' => false, 'multiple' => false, 'message'=>(string) $result, 'editions'=>array()));
			}
			else
			{
				echo json_encode(array('success' => true, 'multiple' => true, 'message'=>'', 'editions'=>$decodeToJSON->editions));
			}
		}
		else
		{
			echo json_encode(array('success' => false, 'multiple' => false, 'message'=> '', 'editions'=>array()));
		}
		exit();
	}
}
?>