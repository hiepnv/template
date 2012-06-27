<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: showlist.php 12608 2012-05-12 04:47:22Z hiennv $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
class ImageShowControllerShowList extends JController {

	function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask('add',  'display');
		$this->registerTask('edit', 'display');
		$this->registerTask('apply', 'save');
	}

	function display($cachable = false, $urlparams = false)
	{
		switch($this->getTask())
		{
			case 'add' :
			{
				JRequest::setVar('hidemainmenu', 1);
				JRequest::setVar('layout', 'form');
				JRequest::setVar('view', 'showlist');
				JRequest::setVar('edit', false);
				JRequest::setVar('model', 'showlist');
			}
			break;
			case 'edit' :
			{
				JRequest::setVar('hidemainmenu', 1);
				JRequest::setVar('layout', 'form');
				JRequest::setVar('view', 'showlist');
				JRequest::setVar('edit', true);
				JRequest::setVar('model', 'showlist');
			}
				break;
			case 'elements':
			{
				JRequest::setVar('layout', 'elements');
				JRequest::setVar('view', 'showlists');
				JRequest::setVar('model', 'showlists');
			}
			break;
			case 'element':
			{
				JRequest::setVar('layout', 'element');
				JRequest::setVar('view', 'showlists');
				JRequest::setVar('model', 'showlists');
			}
			break;
			case 'deleteSource':
			{
				JRequest::setVar('layout', 'delete_source');
				JRequest::setVar('view', 'showlists');
				JRequest::setVar('model', 'showlists');
			}
			break;
			case 'profile':
			{
				JRequest::setVar('layout', 'form_profile');
				JRequest::setVar('view', 'showlist');
				JRequest::setVar('model', 'showlist');
			}
			break;
			case 'authenticate':
			{
				JRequest::setVar('layout', 'form_login');
				JRequest::setVar('view', 'showlist');
				JRequest::setVar('model', 'showlist');
			}
			break;
			default:
				JRequest::setVar('layout', 'default');
				JRequest::setVar('view', 'showlists');
				JRequest::setVar('model', 'showlists');
		}
		parent::display();
	}

	function save()
	{
		JRequest::checkToken() or jexit('Invalid Token');
		global $objectLog, $mainframe;
		$link 			= 'index.php?option=com_imageshow&controller=showlist';
		$objJSNUtils 	= JSNISFactory::getObj('classes.jsn_is_utils');
		$objJSNShowlist = JSNISFactory::getObj('classes.jsn_is_showlist');
		$date 			= JFactory::getDate();
		$user			= JFactory::getUser();
		$userID			= $user->get ('id');
		$db						= JFactory::getDBO();
		$post					= JRequest::get('post');
		$cid					= JRequest::getVar('cid', array(0), 'post', 'array');
		$alternativeStatus		= JRequest::getInt('alternative_status');
		$seoStatus				= JRequest::getInt('seo_status');
		$authorizationStatus	= JRequest::getInt('authorization_status');
		$post['showlist_id'] 	= (int) $cid[0];
		$post['date_create']	= $date->toFormat('%Y-%m-%d %H:%M:%S');
		$post['showlist_link']	= $objJSNUtils->encodeUrl($post['showlist_link']);

		if ($cid[0] == '' or $cid[0] == 0)
		{
			$post['date_create']			= $date->toFormat('%Y-%m-%d %H:%M:%S');
			$post['date_modified']			= $date->toFormat('%Y-%m-%d %H:%M:%S');
		}
		else
		{
			unset($post['date_create']);
			$post['date_modified']			= $date->toFormat('%Y-%m-%d %H:%M:%S');
		}

		if ($alternativeStatus != 2) {
			$post['alter_id'] = 0;
		}

		if ($alternativeStatus != 1) {
			$post['alter_module_id'] = 0;
		}

		if ($alternativeStatus != 3) {
			$post['alter_image_path'] = '';
		}

		if ($seoStatus != 1 && ($seoStatus == 0 || $seoStatus == 2)) {
			$post['seo_article_id'] = 0;
		}

		if ($seoStatus != 2 && ($seoStatus == 1 || $seoStatus == 0)) {
			$post['seo_module_id'] = 0;
		}

		if ($authorizationStatus != 1) {
			$post['alter_autid']			= 0;
		}

		$model 		 = $this->getModel('showlist');
		$count   	 = $objJSNShowlist->countShowlist();
		$arrayID 	 = $objJSNShowlist->getShowlistID();
		$limitStatus = $objJSNUtils->checkLimit();

		if ($count[0] >= 3 && $limitStatus == true)
		{
			if(!in_array((int)$cid[0], $arrayID))
			{
				$this->setRedirect('index.php?option=com_imageshow&controller=showlist');
				return false;
			}
		}

		if ($model->store($post))
		{
			if($post['showlist_id']==0 or $post['showlist_id'] =='')
			{
				$objectLog->addLog($userID, JRequest::getURI(), JRequest::getVar('showlist_title'), 'showlist', 'add');
			}
			else
			{
				if($this->getTask() == 'save')
				{
					$objectLog->addLog($userID, JRequest::getURI(), JRequest::getVar('showlist_title'), 'showlist', 'modify');
				}
			}

			switch ($this->getTask())
			{
				case 'apply':
					$msg  = JText::_('SUCCESSFULLY_SAVED_CHANGES');
					$link = 'index.php?option=com_imageshow&controller=showlist&task=edit&cid[]='. $model->_id;
					break;
				default:
					$msg  = JText::_('SUCCESSFULLY_CREATED');
					$link = 'index.php?option=com_imageshow&controller=showlist';
					if (isset($post['jsn-menu-link-redirect']))
					{
						$msg = '';
						$link = ($post['jsn-menu-link-redirect'] != '') ? $post['jsn-menu-link-redirect'] : $link;
					}
					break;
			}
		}
		else
		{
			$msg = JText::_('ERROR_SAVING_SHOWLIST');
		}

		$this->setRedirect($link, $msg);

	}

	function remove()
	{
		global $mainframe, $objectLog;
		JRequest::checkToken() or jexit('Invalid Token');
		$user			= & JFactory::getUser();
		$objJSNShowlist	= JSNISFactory::getObj('classes.jsn_is_showlist');
		$userID			= $user->get ('id');
		$cid 			= JRequest::getVar('cid', array(), 'post', 'array');
		JArrayHelper::toInteger($cid);

		if (count($cid) < 1) {
			JError::raiseError(500, JText::_('PLEASE MAKE A SELECTION FROM THE LIST TO').' '.JText::_('DELETE'));
		}

		$model = $this->getModel('showlists');

		if(count($cid) == 1){
			$showlistInfo = $objJSNShowlist->getTitleShowList($cid[0]);
		}

		if (!$model->delete($cid))
		{
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}
		else
		{
			if(count($cid) == 1){
				$objectLog->addLog($userID, JRequest::getURI(), $showlistInfo[0], 'showlist', 'delete');
			}else{
				$objectLog->addLog($userID, JRequest::getURI(), count($cid), 'showlist', 'delete');
			}
		}

		$msg = JText::_('Delete showlist successful, with images in that');
		$mainframe->redirect('index.php?option=com_imageshow&controller=showlist', $msg);
	}

	function publish()
	{
		global $mainframe;
		JRequest::checkToken() or jexit('Invalid Token');
		$cid = JRequest::getVar('cid', array(), 'post', 'array');
		JArrayHelper::toInteger($cid);

		if (count($cid) < 1) {
			JError::raiseError(500, JText::_('SELECT AN ITEM TO PUBLISH'));
		}

		$model = $this->getModel('showlists');

		if(!$model->approve($cid, 1))
		{
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$mainframe->redirect('index.php?option=com_imageshow&controller=showlist');
	}

	function unpublish()
	{
		global $mainframe;
		JRequest::checkToken() or jexit('Invalid Token');
		$cid = JRequest::getVar('cid', array(), 'post', 'array');
		JArrayHelper::toInteger($cid);

		if (count($cid) < 1) {
			JError::raiseError(500, JText::_('SELECT AN ITEM TO UNPUBLISH'));
		}

		$model = $this->getModel('showlists');

		if(!$model->approve($cid, 0)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$mainframe->redirect('index.php?option=com_imageshow&controller=showlist');
	}

	function cancel()
	{
		global $mainframe;
		JRequest::checkToken() or jexit('Invalid Token');
		$mainframe->redirect('index.php?option=com_imageshow&controller=showlist');
	}

	function saveOrder()
	{
		global $mainframe;
		JRequest::checkToken() or jexit('Invalid Token');
		$db			= & JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(0), 'post', 'array');
		$order		= JRequest::getVar('order', array (0), 'post', 'array');
		$total		= count($cid);
		$conditions	= array ();
		$row 		= & JTable::getInstance('showlist','Table');
		JArrayHelper::toInteger($cid, array(0));
		JArrayHelper::toInteger($order, array(0));

		for ($i = 0; $i < $total; $i ++)
		{
			$row->load((int) $cid[$i]);

			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];
				if (!$row->store())
				{
					JError::raiseError(500, $db->getErrorMsg());
					return false;
				}
			}
		}

		$msg = JText::_('New ordering saved');
		$mainframe->redirect('index.php?option=com_imageshow&controller=showlist', $msg);
	}

	function orderup()
	{
		$cid	= JRequest::getVar('cid', array(0), 'post', 'array');
		JArrayHelper::toInteger($cid, array(0));
		$this->orderCategory($cid[0], -1);
	}

	function orderdown()
	{
		$cid	= JRequest::getVar('cid', array(0), 'post', 'array');
		JArrayHelper::toInteger($cid, array(0));
		$this->orderCategory($cid[0], 1);
	}

	function orderCategory($uid, $inc)
	{
		global $mainframe;
		JRequest::checkToken() or jexit('Invalid Token');
		$db 	=& JFactory::getDBO();
		$row 	= & JTable::getInstance('showlist','Table');
		$row->load($uid);
		$row->move($inc);
		$msg 	= JText::_('New ordering saved');

		$mainframe->redirect('index.php?option=com_imageshow&controller=showlist', $msg);
	}

	function imanager()
	{
		global $mainframe;
		$cid	= JRequest::getVar('cid', array(0), 'post', 'array');
		$link 	= 'index.php?option=com_imageshow&controller=images&showlist_id=' . (int) $cid[0];
		$mainframe->redirect($link);
	}

	function onSelectSource()
	{
		$get = JRequest::get('get');

		if ($get['source_identify'] && $get['image_source_type'])
		{
			$objImageSource = JSNISFactory::getSource($get['source_identify'], $get['image_source_type']);

			$result = $objImageSource->onSelectSource($get);
		}

		global $mainframe;
		$link = 'index.php?option=com_imageshow&controller=showlist&task=edit&cid[]='.$get['showlist_id'];
		$mainframe->redirect($link);
	}

	function createProfile()
	{
		$post 			= JRequest::get('post');
		$showlistTable 	= JTable::getInstance('showlist', 'Table');
		$imageSource 	= JSNISFactory::getSource($post['source_identify'], $post['image_source_type'], $post['showlist_id']);
		$sourceTable 	= $imageSource->getSourceTable();
		$profileTable 	= JTable::getInstance('SourceProfile', 'Table');

		try
 		{
 			if ($showlistTable->load((int)$post['showlist_id'])) {
 			}else{
 				throw new Exception(JText::_('SHOWLIST_FLEX_SHOWLIST_NOT_EXISTS'), 500);
 			}

			$profileNameExists  = $imageSource->checkProfileExists(array('name' => trim($post['external_source_profile_title'])));

 			if ($profileNameExists == true) {
 				throw new Exception(JText::_('SHOWLIST_FLEX_PROFILE_NAME_EXISTS'), 500);
 			}

 			$sourceTable->bind($post);

 			if (!$imageSource->getValidation($post)) {
 				throw new Exception($imageSource->_errorMsg, 500);
 			}

			if (!$sourceTable->store()) {
 				throw new Exception(JText::_('SHOWLIST_FLEX_SOURCE_INFORMATION_NOT_SAVE'), 500);
 			}

 			$profileTable->bind($post);
 			$profileTable->external_source_id = $sourceTable->external_source_id;

			if (!$profileTable->store()) {
 				throw new Exception(JText::_('SHOWLIST_FLEX_EXTERNAL_INFORMATION_NOT_SAVE'), 500);
 			}

 			$showlistTable->image_source_type = $post['image_source_type'];
			$showlistTable->image_source_name =	$post['source_identify'];

 			if ($post['image_source_type'] == 'external') {
 				$showlistTable->image_source_profile_id = $profileTable->external_source_profile_id;
 			}

			if (!$showlistTable->store()) {
 				throw new Exception(JText::_('SHOWLIST_FLEX_SHOWLIST_SOURCE_NOT_UPDATE'), 500);
 			}

 			return true;
		}
 		catch (Exception $e)
 		{
 			JError::raiseWarning(100, $e->getMessage());
 			return false;
		}
	}

	function changeProfile()
	{
		$post 			= JRequest::get('post');
		$showlistTable 	= JTable::getInstance('showlist', 'Table');
		$profileTable 	= JTable::getInstance('SourceProfile', 'Table');

		$profileTable->external_source_id = $post['external_source_id'];

		if (!$profileTable->store()) {
			JError::raiseWarning(100, JText::_('SHOWLIST_FLEX_CAN_NOT_CHANGE_SHOWLIST_SOURCE'));
			return false;
		}

		$showlistTable->load((int)$post['showlist_id']);
		$showlistTable->image_source_profile_id = $profileTable->external_source_profile_id;
		$showlistTable->image_source_type 	= $post['image_source_type'];
		$showlistTable->image_source_name 	= $post['source_identify'];

		if (!$showlistTable->store())
		{
			JError::raiseWarning(100, JText::_('SHOWLIST_FLEX_CAN_NOT_CHANGE_SHOWLIST_SOURCE'));
			return false;
		}

		JError::raiseNotice(100, JText::_('SHOWLIST_FLEX_SHOWLIST_SOURCE_HAVE_CHANGED'));
		return true;
	}

	function changeSource()
	{
		$showlistID 	= JRequest::getInt('showlist_id', 0);
		$showlistTable 	= JTable::getInstance('showlist', 'Table');
		$data 			= new stdClass();
		$data->status	= 'false';
		$data->msg 		= 'showlist no exists';

		if ($showlistTable->load($showlistID))
		{

			$imageSource = JSNISFactory::getSource($showlistTable->image_source_name, $showlistTable->image_source_type, $showlistID);
			$imageSource->removeAllImages(array('showlist_id' => $showlistID));

			if ($showlistTable->image_source_type == 'external') {
				$imageSource->_source['profileTable']->delete($showlistTable->image_source_profile_id);
			}

			if ($imageSource->getError() == true)
			{
				$data->status = 'false';
				$data->msg = $imageSource->getErrorMsg();
			}
			else
			{
				$showlistTable->image_source_name = '';
				$showlistTable->image_source_type = '';
				$showlistTable->image_source_profile_id = 0;

				if ($showlistTable->store())
				{
					$data->msg 	  = 'successfull';
					$data->status = 'true';
				}
				else
				{
					$data->msg 	  = 'showlist not save';
					$data->status = 'false';
				}
			}
		}

		echo json_encode($data);

		jexit();
	}
	/**
	* Function init show all images selected of the first album is selected 
	*/
	function init()
	{		
		// load all album is selected with current showlist -> and then get the firt album to get all images of that album.
		$showListID  = JRequest::getVar('showListID', '');
		$sourceType  = JRequest::getVar('sourceType', '');
		$sourceName  = JRequest::getVar('sourceName', '');
		$selectMode  = JRequest::getVar('selectMode', '' );

		$imageSource = JSNISFactory::getSource($sourceName, $sourceType, $showListID);
		$model  	 = $this->getModel('images');
		$cat 		 = $model->getAllCatShowlist($showListID);
		if(!empty($cat)){
			$catid		 = $cat[0];		
			$config		 = array('album'=>$catid);	
			$images 	 = $imageSource->loadImages($config);
		}else {
			$images->images	 = array();
		}						
		include(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'views'.DS.'showlist'.DS.'tmpl'.DS.'init.php');
		jexit();
	}
}
?>