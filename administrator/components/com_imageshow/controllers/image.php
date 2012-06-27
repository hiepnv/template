<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: image.php 8411 2011-09-22 04:45:10Z hiennv $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
class ImageShowControllerImage extends JController {
	function __construct($config = array())
	 {
		parent::__construct($config);
		$this->registerTask('editimage',  'display');
		$this->registerTask('showlinkpopup','display');
	 }
	 public function display()
	 {
		//JRequest::setVar('hidemainmenu', 1);
		$document = JFactory::getDocument();

		$task = $this->getTask();
		switch ($task) {
			case 'editimage':
				JRequest::setVar('hidemainmenu', 1);
				JRequest::setVar('layout', 'editimage');
				JRequest::setVar('view', 'image');
				JRequest::setVar('model', 'image');
				break;
			case 'showlinkpopup':
				JRequest::setVar('hidemainmenu', 1);
				JRequest::setVar('layout', 'showlinkpopup');
				JRequest::setVar('view', 'image');
				JRequest::setVar('model', 'image');
			default:
				# code...
				break;
		}
		//JRequest::setVar('edit', false );

		$imageID     = JRequest::getVar('imageID', '');
		$showListID  = JRequest::getVar('showListID', '');
		$sourceName  = JRequest::getVar('sourceName', '');
		$sourceType  = JRequest::getVar('sourceType', '');
		$app 		 = JFactory::getApplication();

		$app->setUserState('com_imageshow.images.imageID', $imageID);
		$app->setUserState('com_imageshow.images.showlistID', $showListID);
		$app->setUserState('com_imageshow.images.sourceName', $sourceName);
		$app->setUserState('com_imageshow.images.sourceType', $sourceType);
		parent::display();
	 }
	/**
	 *
	 * Save image details
	 */
	public function apply()
	{
		$model = $this->getModel('image');
		$model->saveImages(JRequest::get());
		// end of process update
		$app = JFactory::getApplication();
		$showListID			= JRequest::getVar('showlistID');
		$app->redirect('index.php?option=com_imageshow&controller=showlist&task=edit&cid[]='.$showListID);

	}
	function getMenuItemCate()
	{
		JHTML::stylesheet('image_sortable.css','administrator/components/com_imageshow/assets/css/');
		JHTML::stylesheet('window.css','administrator/components/com_imageshow/assets/css/');
		JHTML::stylesheet('videoshow.css','administrator/components/com_imageshow/assets/css/');
		JHTML::script('jquery.min.js','administrator/components/com_imageshow/assets/js/jquery/');
		JHTML::script('conflict.js','administrator/components/com_imageshow/assets/js/joomlashine/');
		JHTML::script('imagegrid.js','administrator/components/com_imageshow/assets/js/joomlashine/');
		JHTML::script('jquery-treeview.js','administrator/components/com_imageshow/assets/js/jquery/');

		JHTML::stylesheet('image_sortable.css','administrator/components/com_imageshow/assets/css/');
		JHTML::stylesheet('window.css','administrator/components/com_imageshow/assets/css/');
		JHTML::stylesheet('videoshow.css','administrator/components/com_imageshow/assets/css/');
		JHTML::stylesheet('jquery.treeview.css','administrator/components/com_imageshow/assets/css/');

		$cat_id = JRequest::getVar('catid');
		$model  = $this->getModel('image');
		$articleCate = $model->_getListMenuItem($cat_id);
		include(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'views'.DS.'image'.DS.'tmpl'.DS.'menuitem.php');
		jexit();
	}

	function getArticleCate()
	{

		JHTML::script('jquery.min.js','administrator/components/com_imageshow/assets/js/jquery/');
		JHTML::script('conflict.js','administrator/components/com_imageshow/assets/js/joomlashine/');
		JHTML::stylesheet('image_sortable.css','administrator/components/com_imageshow/assets/css/');
		JHTML::stylesheet('window.css','administrator/components/com_imageshow/assets/css/');
		JHTML::stylesheet('videoshow.css','administrator/components/com_imageshow/assets/css/');
		JHTML::stylesheet('jquery.treeview.css','administrator/components/com_imageshow/assets/css/');
		$catid = str_replace('art_cat_','',JRequest::getVar('catid'));
		$model  = $this->getModel('image');
		$articleCate = $model->_getListArticleItem($catid);
		include(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'views'.DS.'image'.DS.'tmpl'.DS.'articleCate.php');
		jexit();
	}

	function PurgeAbsoleteImages()
	{

		$imageid 	= JRequest::getVar('ImageID');
		$showListID = JRequest::getVar('showListID');
		$model  = $this->getModel('image');
		$articleCate = $model->PurgeAbsoleteImages($showListID,$imageid);
		jexit();
	}


	function resetImageDetails()
	{
		$images = JRequest::getVar('img_detail', '');
		$images	= json_decode($images);
		if (!is_null($images))
		{
			$model  					= $this->getModel('image');
			$images->image_title 		= urldecode($images->original_title);
			$images->image_description 	= $images->original_description;
			$images->image_link 		= urldecode($images->original_link);
			$images->custom_data 		= '0';
			unset($images->original_title);
			unset($images->original_description);
			unset($images->original_link);
			$model->updateImageInformation($images);
		}
		jexit();
	}
}
