<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: controller.php 12885 2012-05-31 03:10:25Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.application.component.controller' );
class ImageShowController extends JController {

	function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerTask('plugin',  'display');
	}

	function display($cachable = false, $urlparams = false)
	{
		switch($this->getTask())
		{
			case 'plugin':
				JRequest::setVar('layout', 'plugin');
				JRequest::setVar('view', 'cpanel');
				JRequest::setVar('model', 'cpanel');
			break;
			case 'alltip':
				JRequest::setVar('layout', 'all_tip');
				JRequest::setVar('view', 'cpanel');
				JRequest::setVar('model', 'cpanel');
			break;
			case 'modal':
				JRequest::setVar('layout', 'modal');
				JRequest::setVar('view', 'cpanel');
				JRequest::setVar('model', 'cpanel');
			break;
			default:
				JRequest::setVar( 'layout', 'default' );
				JRequest::setVar( 'view', 'cpanel' );
				JRequest::setVar( 'model', 'cpanel' );
		}

		parent::display();
	}

	function sampledata()
	{
		$sampleData	= JRequest::getInt( 'sample_data' );
		$menuType	= JRequest::getString( 'menutype' );
		$keepAllData	= JRequest::getInt( 'keep_all_data' );
		$installMessage	= JRequest::getInt( 'install_message' );

		$model 	= $this->getModel('cpanel');
		$msg = '';
		if ($keepAllData == 1)
		{
			$model->clearData();
		}

		if($installMessage == 1)
		{
			$objJSNInstMessage 		= JSNISFactory::getObj('classes.jsn_is_installermessage');
			$objJSNInstMessage->installMessage();
		}

		if ($sampleData == 1)
		{
			$model->populateDatabase();
			if ($menuType != '')
			{
				$model->insertMenuSample($menuType);
			}
			$msg  = JText::_( 'Install sample data successfully' );
		}

		$link = 'index.php?option=com_imageshow';

		$this->setRedirect($link, $msg);
	}

	function launchAdapter()
	{
		$jsnUtils		= JSNISFactory::getObj('classes.jsn_is_utils');
		$app 			= JFactory::getApplication();
		$type			= JRequest::getCmd('type');
		$showcaseID 	= JRequest::getInt('showcase_id');
		$showlistID 	= JRequest::getInt('showlist_id');
		$app->setUserState('com_imageshow.add.showcase_id', $showcaseID);
		$app->setUserState('com_imageshow.add.showlist_id', $showlistID);

		switch ($type)
		{
			case 'module':
				$moduleInfo 	= $jsnUtils->getModuleInfo();
				$link = 'index.php?option=com_modules&task=module.add&eid='.$moduleInfo->extension_id;
				$this->setRedirect($link);
			break;
			case 'menu':
				$componetInfo 				= $jsnUtils->getComponentInfo();
				$data ['type'] 				= 'component';
				$data ['title'] 			= '';
				$data ['alias'] 			= '';
				$data ['note'] 				= '';
				$data ['link'] 				= 'index.php?option=com_imageshow&view=show';
				$data ['published'] 		= '1';
				$data ['access'] 			= '1';
				$data ['menutype'] 			= JRequest::getCmd('menutype');
				$data ['parent_id'] 		= '1';
				$data ['browserNav'] 		= '0';
				$data ['home'] 				= '0';
				$data ['language'] 			= '*';
				$data ['template_style_id'] = '0';
				$data ['id'] 				= '0';
				$data ['component_id'] 		= $componetInfo->extension_id;
				$app->setUserState('com_menus.edit.item.data', $data);
				$app->setUserState('com_menus.edit.item.type',	'component');
				$app->setUserState('com_menus.edit.item.link','index.php?option=com_imageshow&view=show');
				$link 						= 'index.php?option=com_menus&view=item&layout=edit';
				$this->setRedirect($link);
				break;
			default:
			break;
		}
		return true;
	}
}
?>