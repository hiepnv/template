<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_flex.php 13076 2012-06-06 09:26:10Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.utilities.utility' );
class JSNISFlex{

	var $_token = '';

	public static function getInstance()
	{
		static $instanceFlex;

		if ($instanceFlex == null){
			$instanceFlex = new JSNISFlex();
		}
		return $instanceFlex;
	}

	function JSNISFlex()
	{
		$this->_token = JUtility::getToken();
	}

	function getToken()
	{
		return $this->_token;
	}

	function bindObject($success = true, $msg, $data = '')
	{
		$obj			= new stdClass();
		$obj->isSuccess = $success;
		$obj->msg 		= $msg;

		if ($data != '') {
			$obj->data		= $data;
		}

		$objJSNJSON = JSNISFactory::getObj('classes.jsn_is_json');

		return $objJSNJSON->encode($obj);
	}

	function init($showlistID)
	{
		if (!JRequest::checkToken('get')) {
			return JSNISFlex::bindObject(false, JText::_('SHOWLIST_FLEX_INVALID_TOKEN'));
		}

		$showlistTable = JTable::getInstance('showlist','Table');

		if ($showlistTable->load((int)$showlistID))
		{
			$images 			= array();
			$profileType 		= '';
			$profileTitle 		= '';
			$albumCategories 	= '';

			if ($showlistTable->image_source_type != '')
			{
				$objImageSource 	= JSNISFactory::getSource($showlistTable->image_source_name, $showlistTable->image_source_type, $showlistTable->showlist_id);
				$images    			= $objImageSource->getImages(array('showlist_id' => $showlistTable->showlist_id));
				$profileType  		= $objImageSource->getSourceName();
				$albumCategories 	= $objImageSource->getCategories(array('showlist_id' => $showlistTable->showlist_id));
				$profileTitle 		= $objImageSource->getProfileTitle();
			}

			$objJSNImages 	 = JSNISFactory::getObj('classes.jsn_is_images');
			$albumSync 		 = $objJSNImages->getSyncAlbumsByShowlistID($showlistID);

			$obj 						= new stdClass();
			$obj->profile_type 			= $profileType;
			$obj->images				= (is_array($images)) ? $images : array();
			$obj->album					= ($albumCategories == false) ? '' : $albumCategories;
			$obj->syncmode				= (count($albumSync) > 0) ? true : false;
			$obj->sync_mode_enabled     = ($showlistTable->image_source_type != 'external') ? true : false;
			$obj->enable_get_image_info = ($showlistTable->image_source_type == 'external') ? true : false;
			$obj->has_pre_link			= ($showlistTable->image_source_type != 'external') ? true : false;
			$obj->profile_title 		= $profileTitle;
			$obj->image_source_name 	= (!empty($showlistTable->image_source_name)) ? $showlistTable->image_source_name : '';
			$obj->image_source_type 	= (!empty($showlistTable->image_source_type)) ? $showlistTable->image_source_type : '';
			$obj->folderSource			= ($showlistTable->image_source_name == 'folder') ? true : false;

			if (!empty($showlistTable->showlist_title)) {
				return JSNISFlex::bindObject(true, '', $obj);
			} else {
				return JSNISFlex::bindObject(false, JText::_('SHOWLIST_FLEX_SHOWLIST_NOT_EXISTS'));
			}
		}
	}

	function removeAllImagesByShowlistID($showlistID)
	{
		if (!JRequest::checkToken('get')) {
			return JSNISFlex::bindObject(false, JText::_('SHOWLIST_FLEX_INVALID_TOKEN'));
		}

		if (!empty($showlistID))
		{
			$showlistTable = JTable::getInstance('showlist', 'Table');

			if ($showlistTable->load($showlistID))
			{
				$imageSource = JSNISFactory::getSource($showlistTable->image_source_name, $showlistTable->image_source_type, $showlistID);
				$imageSource->removeAllImages(array('showlist_id' => $showlistID));

				$listSource   = $imageSource->getListSources();

				return JSNISFlex::bindObject(true, '', $listSource);
			}
		}
	}

	function addImages()
	{
		if (!JRequest::checkToken('post')) {
			return JSNISFlex::bindObject(false, JText::_('SHOWLIST_FLEX_INVALID_TOKEN'));
		}

		global $objectLog;

		$infoInsert 				= array();
		$infoInsert['imgID']		= JRequest::getVar('image_id', array(), 'post', 'array');
		$infoInsert['imgExtID']		= JRequest::getVar('image_extid', array(), 'post', 'array');
		$infoInsert['imgSmall']		= JRequest::getVar('image_small', array(), 'post', 'array');
		$infoInsert['imgMedium']	= JRequest::getVar('image_medium', array(), 'post', 'array');
		$infoInsert['imgBig']		= JRequest::getVar('image_big', array(), 'post', 'array');
		$infoInsert['imgTitle']		= JRequest::getVar('image_title', array(), 'post', 'array');
		$infoInsert['imgLink']		= JRequest::getVar('image_link', array(), 'post', 'array');
		$infoInsert['albumID']		= JRequest::getVar('album_extid', array(), 'post', 'array');
		$infoInsert['imgDescription'] = JRequest::getVar('image_description', array(), 'post', 'array');
		$infoInsert['showlistID'] 	= JRequest::getInt('showlist_id');
		$infoInsert['customData']   = JRequest::getVar('custom_data', array(), 'post', 'array');

		$objJSNImages		= JSNISFactory::getObj('classes.jsn_is_images');
		$objJSNUtils 		= JSNISFactory::getObj('classes.jsn_is_utils');
		$objJSNShowlist		= JSNISFactory::getObj('classes.jsn_is_showlist');

		$showlistTable 		= JTable::getInstance('showlist', 'Table');
		$showlistTable->load($infoInsert['showlistID']);

		$imageSource 		= JSNISFactory::getSource($showlistTable->image_source_name, $showlistTable->image_source_type, $showlistTable->showlist_id);
		$user				= JFactory::getUser();
		$userID				= $user->get('id');

		$showlistTitle 				= $objJSNShowlist->getTitleShowList($infoInsert['showlistID']);
		$imgArrayLocalExtID 		= $objJSNImages->getImageExtByShowlistID($infoInsert['showlistID']);
		$ordering 					= JRequest::getVar( 'ordering');

		if (!is_null($imgArrayLocalExtID) && !empty($imgArrayLocalExtID))
		{
			$arrayImagesRemove = array_diff($imgArrayLocalExtID, $infoInsert['imgExtID']);

			// remove images that not selected
			if (count($arrayImagesRemove))
			{
				$infoDelete 				 = array();
				$infoDelete['imgExtID'] 	 = array_values($arrayImagesRemove);
				$infoDelete['showlistID'] 	 = $infoInsert['showlistID'];

				$deleteImageExist 			 = $imageSource->removeImages($infoDelete);

				if ($deleteImageExist == false) {
					return JSNISFlex::bindObject(false, JText::_('SHOWLIST_FLEX_CAN_NOT_REMOVE_AVAILABLE_IMAGES_FROM_SHOWLIST'), '');
				}
			}

			if (!empty($infoInsert['imgExtID'][0]))
			{
				$arrayNewImages = array_diff($infoInsert['imgExtID'], $imgArrayLocalExtID);
				$arrayExtIDImages = array();

				// update images was edited
				foreach ($infoInsert['imgExtID'] as $imgExtID)
				{
					if(!in_array($imgExtID, $arrayNewImages)){
						$arrayExtIDImages[] = $imgExtID;
					}
				}
				$objJSNImages->updateImageDetail($arrayExtIDImages, $infoInsert);

				// insert new images
				if (count($arrayNewImages))
				{
					$infoInsert['imgExtID'] = array_values($arrayNewImages);
					$inserImageExist 		= $imageSource->saveImages($infoInsert);

					if ($inserImageExist == false)
					{
						return JSNISFlex::bindObject(false, JText::_('SHOWLIST_FLEX_CAN_NOT_INSERT_IMAGES_AFTER_REMOVING_AVAILABLE_IMAGES_FROM_SHOWLIST'), '');
					}
				}
			}
		}
		else
		{
			if (count($infoInsert['imgExtID']) > 0 && !is_null($infoInsert['imgExtID'][0]) && !empty($infoInsert['imgExtID'][0]))
			{
				$insertImage = $imageSource->saveImages($infoInsert);
				if ($insertImage == false) {
					return JSNISFlex::bindObject(false, JText::_('SHOWLIST_FLEX_CAN_NOT_INSERT_IMAGES'));
				}
			}
		}

		$objJSNImages->updateOrder($ordering , $infoInsert['showlistID']);
		$objJSNShowlist->updateDateModifiedShowlist((int)$infoInsert['showlistID']);
		$objectLog->addLog($userID, JRequest::getURI(), $showlistTitle[0],'addimages','any');

		return JSNISFlex::bindObject(true,'');
	}

	function loadRemoteImage()
	{
		if (!JRequest::checkToken('get')) {
			return JSNISFlex::bindObject(false, JText::_('SHOWLIST_FLEX_INVALID_TOKEN'));
		}

		$get 		= JRequest::get('get');
		$showlistID = JRequest::getVar('showlist_id');
		$album 		= JRequest::getVar('album');

		if (!empty($get['showlist_id']) && $get['album'] != '')
		{
			$showlistTable = JTable::getInstance('showlist','Table');

			if ($showlistTable->load($get['showlist_id']))
			{
				$objImageSource = JSNISFactory::getSource($showlistTable->image_source_name, $showlistTable->image_source_type, $showlistTable->showlist_id);
				$images = $objImageSource->loadImages($get);
			}

			if ($objImageSource->getError()) {
				return JSNISFlex::bindObject(false, $objImageSource->getErrorMsg());
			}

			return JSNISFlex::bindObject(true, '', $images);
		}

		return JSNISFlex::bindObject(false, JText::_('SHOWLIST_FLEX_INVALID_SHOWLISTID_OR_ALBUM'));
	}

	function getImageInfo()
	{
		if (!JRequest::checkToken('post')) {
			return JSNISFlex::bindObject(false, JText::_('SHOWLIST_FLEX_INVALID_TOKEN'));
		}

		$post 			= JRequest::get('post');
		$arrayImageInfo = array();

		if ($post['showlist_id'])
		{
			$showlistTable = JTable::getInstance('showlist', 'Table');

			if ($showlistTable->load($post['showlist_id']))
			{
				$imageSource 	= JSNISFactory::getSource($showlistTable->image_source_name, $showlistTable->image_source_type, $showlistTable->showlist_id);
				$arrayImageInfo = $imageSource->getOriginalInfoImages($post);
			}
		}

		return JSNISFlex::bindObject(true, '', $arrayImageInfo);
	}

	function loadLanguage()
	{
		$objJSNLang = JSNISFactory::getObj('classes.jsn_is_language');
		$result  = $objJSNLang->loadLanguageFlex();

		if ($result == false) {
			return JSNISFlex::bindObject(true,'','');
		}

		return JSNISFlex::bindObject(true,'',$result);
	}

	function saveSyncAlbum()
	{
		if (!JRequest::checkToken('post')) {
			return JSNISFlex::bindObject(false, JText::_('SHOWLIST_FLEX_INVALID_TOKEN'));
		}

		$post = JRequest::get('post');

		if (!isset($post['album_extid'])) {
			return JSNISFlex::bindObject(false, JText::_('SHOWLIST_FLEX_SELECT_A_FOLDER'), false);
		}

		$objJSNImages = JSNISFactory::getObj('classes.jsn_is_images');
		$result = $objJSNImages->saveSyncAlbum($post['showlist_id'], $post['album_extid']);

		if ($result) {
			return JSNISFlex::bindObject(true, JText::_('Enable sync album feature'));
		}

		return JSNISFlex::bindObject(false, JText::_('Unenable sync album feature'));
	}

	function loadImageSources()
	{
		$objJSNSource = JSNISFactory::getObj('classes.jsn_is_source');
		$listSource   = $objJSNSource->getListSources('active');
		return JSNISFlex::bindObject(true, '', $listSource);
	}

	function createThumb()
	{
		$post = JRequest::get('post');
		$imageSource = JSNISFactory::getSource($post['image_source_name'], $post['image_source_type'], $post['showlist_id']);
		$thumb = $imageSource->createThumb($post);

		return JSNISFlex::bindObject(true, 'create thumb', $thumb);
	}

	function getScriptCheckThumb()
	{
		$showlistID = JRequest::getInt('showlist_id');
		$script = '';

		$showlistTable = JTable::getInstance('showlist', 'Table');
		$showlistTable->load($showlistID);

		if ($showlistTable->image_source_name != '')
		{
			$imageSource = JSNISFactory::getSource($showlistTable->image_source_name, $showlistTable->image_source_type, $showlistID);
			$script .= $imageSource->renderScriptcheckThumb();
		}else{
			$script .= ' JSNISImageShow.checkThumbCallBack(); ';
		}
		return $script;
	}

	function checkThumb()
	{
		$get = JRequest::get('get');
		$db = JFactory::getDBO();
		$query = 'SELECT * FROM #__imageshow_images WHERE image_id ='.(int)$get['image_id'];
		$db->setQuery($query);
		$result = $db->loadObject();

		$objJSNThumb = JSNISFactory::getObj('classes.jsn_is_imagethumbnail');

		$objJSNThumb->checkImageFolderStatus($result);

		if ($get['total'] == $get['count'])
		{
			return json_encode(array('checkThumb' =>  true, 'image' => $result));
		}

		return json_encode(array('checkThumb' =>  false, 'image' => $result));
	}

	function selectLinkType()
	{
		if (!JRequest::checkToken('get')) {
			return JSNISFlex::bindObject(false, JText::_('SHOWLIST_FLEX_INVALID_TOKEN'));
		}

		$get = JRequest::get('get');
		$function = 'getTree'.ucfirst($get['link_type']);
		$result = $this->$function();

		return JSNISFlex::bindObject(true, $get['link_type'], array('link_type' => $get['link_type'], 'categories' => $result));
	}

	function selectLinkItem()
	{
		if (!JRequest::checkToken('get')) {
			return JSNISFlex::bindObject(false, JText::_('SHOWLIST_FLEX_INVALID_TOKEN'));
		}

		$get = JRequest::get('get');
		$function = '_getList'.ucfirst($get['link_type']).'Item';

		$result = $this->$function($get['identify']);

		return JSNISFlex::bindObject(true, $get['link_type'], array('link_type' => $get['link_type'], 'items' => $result));
	}

	function _getParseMenuItem($item)
	{
		$item->flink = $item->link;
		$params 	 = json_decode($item->params);

		switch ($item->type)
		{
			case 'separator':
				// No further action needed.
				continue;

			case 'url':
				if ((strpos($item->link, 'index.php?') === 0) && (strpos($item->link, 'Itemid=') === false)) {
					// If this is an internal Joomla link, ensure the Itemid is set.
					$item->flink = $item->link.'&Itemid='.$item->id;
				}
				break;

			case 'alias':
				// If this is an alias use the item id stored in the parameters to make the link.
				$menuObj = $this->_getMenuByID($params->aliasoptions);
				if ($menuObj) {
					$item->flink = $this->_getParseMenuItem($menuObj);
				}
				break;

			default:
				$router = JAdministrator::getRouter('site');
				if ($router->getMode() == JROUTER_MODE_SEF) {
					$item->flink = 'index.php?Itemid='.$item->id;
				}
				else {
					$item->flink .= '&Itemid='.$item->id;
				}
				break;
		}

		return $item->flink;
	}

	function _getMenuByID($menuID)
	{
		$db = JFactory::getDBO();
		$query = 'SELECT * FROM #__menu WHERE id ='.(int)$menuID;
		$db->setQuery($query);
		return  $db->loadObject();
	}

	function _getListArticleItem($catID, $selectedArticleID = 0)
	{
		$db = JFactory::getDBO();
		$query = 'SELECT id, title FROM #__content WHERE catid = ' .(int)$catID;
		$db->setQuery($query);
		$articles = $db->loadObjectList();
		$items = array();

		foreach ($articles as $article)
		{
			$item = new stdClass();
			$item->title = $article->title;
			$item->link = 'index.php?option=com_content&view=article&id='.$article->id;
			$item->selected = ($selectedArticleID == $article->id) ? true : false;
			$items[] = $item;
		}

		return $items;
	}

	function getTreeMenu($selectMenu = '')
	{
		$db = JFactory::getDBO();
		$query = 'SELECT id, menutype AS data, title FROM #__menu_types';
		$db->setQuery($query);
		$menus 	= $db->loadObjectList();
		$data 	= array();

		foreach ($menus as $menu) {
			$menu->selected = ($menu->data == $selectMenu) ? true : false;
			$data[] = $menu;
		}

		return $data;
	}

	function _getListMenuItem($menuType, $selectLink = '')
	{
		$db = JFactory::getDBO();
		$query = 'SELECT MIN(level) AS min_level FROM #__menu WHERE menutype ='.$db->quote($menuType) .' AND client_id = 0 ORDER BY lft ASC';
		$db->setQuery($query);
		$minLevel = $db->loadResult();

		$xmlObj = new JXMLElement('<node></node>');
		$xmlObj->addAttribute('label', 'root');
		$xmlObj->addAttribute('data', 'data');

		if ($minLevel)
		{
			$query = 'SELECT id, title, lft, rgt, level, menutype, link, params, type FROM #__menu WHERE menutype ='.$db->quote($menuType) .' AND client_id = 0 AND level = '.(int)$minLevel.' ORDER BY lft ASC';
			$db->setQuery($query);
			$menus = $db->loadObjectList();
			$count = count($menus);

			foreach ($menus as $menu)
			{
				$node = $xmlObj->addChild('node');
				$node->addAttribute('label', $menu->title);
				$node->addAttribute('data', $this->_getParseMenuItem($menu));

				($menu->type == 'separator') ? $node->addAttribute('disable', 'true') : $node->addAttribute('disable', 'false');

				if ($menu->link)
				{
					if (strpos($selectLink, $menu->link) === false) {
						$node->addAttribute('selected', 'false');
					} else {
						$node->addAttribute('selected', 'true');
					}
				}
				else
				{
					$node->addAttribute('selected', 'false');
				}


				$this->_menuItemXML($node, $menu, $selectLink);
			}
		}

		return $xmlObj->asFormattedXML();
	}

	function getTreeArticle($selectCateID = 0)
	{
		$db = JFactory::getDBO();
		$query = 'SELECT id, title, lft, rgt, level FROM #__categories WHERE id = 1';
		$db->setQuery($query);
		$root = $db->loadObject();

		$xmlObj = new JXMLElement('<node></node>');
		$xmlObj->addAttribute('label', 'root');
		$xmlObj->addAttribute('data', 'data');

		$this->_cateXML($xmlObj, $root, $selectCateID);
		return $xmlObj->asFormattedXML();
	}

	function _cateXML($xmlObj, $item, $selectID = 0)
	{
		$db = JFactory::getDBO();
		$query = 'SELECT id, title, lft, rgt, level FROM #__categories WHERE lft >= '.$item->lft.' AND rgt <= '.$item->rgt.' AND extension = \'com_content\' ORDER BY lft ASC';
		$db->setQuery($query);
		$categories = $db->loadObjectList();

		$count = count($categories);

		for ($i = 0 ; $i < $count; $i++)
		{
			$cate  = $categories[$i];

			// only add element in same level
			if ($item->level + 1 == $cate->level)
			{
				$node = $xmlObj->addChild('node');
				$node->addAttribute('label', $cate->title);
				$node->addAttribute('data', $cate->id);

				if ($cate->id == $selectID) {
					$node->addAttribute('selected', 'true');
				} else {
					$node->addAttribute('selected', 'false');
				}

				JSNISFlex::_cateXML($node, $cate, $selectID);
			}
		}
	}

	function loadLinkType()
	{
		if (!JRequest::checkToken('get')) {
			return JSNISFlex::bindObject(false, JText::_('SHOWLIST_FLEX_INVALID_TOKEN'));
		}

		$get 	= JRequest::get('get');
		$db 	= JFactory::getDBO();
		$link 	= $get['link'];
		$patt 	= 'index.php?option=com_content&view=article&id=';
		$articlePos   = strpos($link, $patt);
		$menuItemPost = strpos($link, '&Itemid');

		$data = new stdClass();
		$data = new stdClass();
		$data->{'link_type'} = 'article';
		$data->items = array();
		$data->categories = $this->getTreeArticle();

		if ($link != '')
		{
			if ($articlePos !== false && $menuItemPost === false) //article
			{
				$pattern = '/&id=\d+(&?)/';
				preg_match($pattern, $link, $matches);

				if (count($matches))
				{
					$articleID = explode('=', str_replace('&', '', $matches[0]));
					$query = 'SELECT id , catid FROM #__content WHERE id='.(int)$articleID[1];
					$db->setQuery($query);
					$result = $db->loadObject();

					if (isset($result->catid))
					{
						$data->categories = $this->getTreeArticle($result->catid);
						$data->items = $this->_getListArticleItem($result->catid, $articleID[1]);
					}
				}
			}
			else if ($menuItemPost !== false)
			{
				$pattern = '/&Itemid=\d+(&?)/';
				preg_match($pattern, $link, $matches);

				if (count($matches))
				{
					$menuItem = explode('=', str_replace('&', '', $matches[0]));
					$query = 'SELECT menutype FROM #__menu WHERE id='.(int)$menuItem[1];
					$db->setQuery($query);
					$result = $db->loadObject();

					if (isset($result->menutype))
					{
						$data->{'link_type'} = 'menu';
						$data->items = $this->_getListMenuItem($result->menutype, $link);
						$data->categories = $this->getTreeMenu($result->menutype);
					}
				}
			}
		}

		return JSNISFlex::bindObject(true, 'load link type', $data);
	}

	function _menuItemXML($xmlObj, $item, $selectLink)
	{
		$db = JFactory::getDBO();
		$query = 'SELECT id, title, lft, rgt, level, link, params, type FROM #__menu WHERE lft >= '.$item->lft.' AND rgt <= '.$item->rgt.' AND client_id = 0 ORDER BY lft ASC';
		$db->setQuery($query);
		$menus = $db->loadObjectList();

		$count = count($menus);

		if ($count)
		{
			for ($i = 0 ; $i < $count; $i++)
			{
				$menu  = $menus[$i];

				// only add element in same level
				if ($item->level + 1 == $menu->level)
				{
					$node = $xmlObj->addChild('node');
					$node->addAttribute('label', $menu->title);
					$node->addAttribute('data', $this->_getParseMenuItem($menu));

					if (strpos($selectLink, $menu->link) === false) {
						$node->addAttribute('selected', 'false');
					} else {
						$node->addAttribute('selected', 'true');
					}

					JSNISFlex::_menuItemXML($node, $menu, $selectLink);
				}
			}
		}
	}
}