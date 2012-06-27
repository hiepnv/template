<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: images_sources_external.php 13036 2012-06-05 02:24:48Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'imagesources'.DS.'images_source_external_helper.php';

class JSNImagesSourcesExternal extends JSNImagesSourcesDefault implements JSNImageSourceExternalHelper
{
	var $_externalPath;
	var $_sourceTablePrefix = 'source';

	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->_externalPath = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow';

		//load profile table
		$this->_source['profileTable'] = JTable::getInstance('SourceProfile', 'Table');
		$this->_source['profileTable']->load($this->_showlistTable->image_source_profile_id);
		// load source table

		$this->_source['sourceTable'] = $this->getSourceTable();
		$this->_source['sourceTable']->load($this->_source['profileTable']->external_source_id);

	}

//	public function getCategories($config = array()){}

	/*public function saveImages($config = array())
	{

		parent::saveImages($config);
	}*/

//	public function updateImages($config = array()){}

	public function getAvaiableProfiles()
	{
		$profiles = array();

		if ($this->_source['sourceIdentify'])
		{
			$query = 'SELECT source.external_source_profile_title as text, source.external_source_id as value
					  FROM #__imageshow_external_source_'.$this->_source['sourceIdentify'].' source
					  ORDER BY source.external_source_id DESC';
			$this->_db->setQuery($query);

			$profiles = $this->_db->loadObjectList();
		}

		return $profiles;
	}

//	public function onSelectProfile($config = array()){}

	public function getSourceTable() {
		return JTable::getInstance($this->_sourceTablePrefix.$this->_source['sourceIdentify'], 'Table');
	}

	/**
	 * @return true/false
	 */
	public function getValidation($config = array()) {
		return true;
	}

	public function getProfileTitle() {
		return (isset($this->_source['sourceTable']->external_source_profile_title)) ? $this->_source['sourceTable']->external_source_profile_title : '';
	}

//	public function loadImages($config = array()){}

	public function addOriginalInfo() {
		return $this->_data['images'];
	}

	public function getOriginalInfoImages($config = array()){}

	public function getImages2JSON($config = array())
	{
		parent::getImages2JSON($config);

		return $this->_data['images'];
	}

	public function getImageSrc($config = array('image_big' => '', 'URL' => '')){}

	public function checkProfileExists($config = array('name' => ''))
	{
		$condition = '';

		$config = array_merge(array('ignoreProfileID'=> 0), $config);

		if ($config['ignoreProfileID'] > 0)
		{
			$condition = ' AND external_source_id <> '.(int)$config['ignoreSourceID'] . ' ';
		}

		$query = 'SELECT * FROM #__imageshow_external_source_'.$this->_source['sourceIdentify'].' WHERE external_source_profile_title LIKE '.$this->_db->quote($config['name']).$condition;
		$this->_db->setQuery($query);

		$result = $this->_db->loadResult();

		return ($result) ? true : false;
	}

	public function removeShowlist()
	{
		if ($this->_source['profileTable']->load($this->_showlistTable->image_source_profile_id)) {
			$this->_source['profileTable']->delete();
		}

		parent::removeShowlist();
	}


    /**
     *  ##################################################################################################################
     * @since: 17-04-2012
     *  Code update for new function of image show (change from manage by flash to javascript)
     * ###################################################################################################################
    */
    /**
	 *
	 * Save sync
	 */
	public function savesync()
	{
		$syncCate    = JRequest::getVar('syncCate', '');
		$showListID  = JRequest::getVar('showListID', '');
		$sourceType  = JRequest::getVar('sourceType', '');
		$sourceName  = JRequest::getVar('sourceName', '');
		$imageSource = JSNFactory::getSource( $sourceName, $sourceType, $showListID );
		$imageSource->saveSync($syncCate);
		jexit();
	}

    /**
	 *
	 * Check Image exists in showlist
	 *
	 * @param String $ImageID
	 */
	public function checkImageSelected( $ImageID )
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select("count(image_id)");
		$query->from("#__imageshow_images");
		$query->where("image_extid=".$db->quote($ImageID));
		$query->where("showlist_id=".$this->_source['showlistID']);
		$db->setQuery($query);
		return (int) $db->loadResult() > 0 ? true : false;
	}
    /**
     * Load images store
    */
    /*public function loadImagesStored()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select("*");
		$query->from("#__imageshow_images");
		$query->where("showlist_id=".$this->_source['showlistID']);
		$query->where("image_extid IS NOT NULL ");
		$query->order("ordering ASC");
		$db->setQuery($query);
		//echo $query;

		$results = $db->loadAssocList();

		$images = Array();
		foreach ($results as $value) {
			$images[] = $value;
		}

		return $images;
	}*/

	/**
	 *
	 * Reset showlist
	 */
	public function resetShowListImages()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->delete();
		$query->from("#__imageshow_images");
		$query->where("showlist_id=".$this->_source['showlistID']);
		$db->setQuery($query);
		$db->query();
	}

	/**
	* Check image exists?
	*/
	/*function chechImageExists($image_exitid,$showlistId)
	{
		$db = JFactory::getDbo();
		$query = "SELECT * FROM #__imageshow_images WHERE image_extid=".$image_exitid." AND showlist_id=".$showlistId;
		$db->setQuery($query);
		$db->query();
		$num_rows = $db->getNumRows();
		return $num_rows;

	}*/

	/**
	* Save list images on showlist
	*/
	/*function saveImagesShowlist($data = array())
	{

		$imagesTable 					= JTable::getInstance('images', 'Table');
		$imagesTable->showlist_id   	= $data->showlist_id;
		$imagesTable->image_extid  		= $data->image_extid;
		$imagesTable->album_extid   	= $data->album_extid;
		$imagesTable->ordering      	= $data->ordering;
		$imagesTable->image_small 		= $data->image_small;
		$imagesTable->image_medium 		= $data->image_medium;
		$imagesTable->image_big 		= $data->image_big;
		$imagesTable->image_link 		= $data->image_link;
		$imagesTable->image_description = $data->image_description;
		$imagesTable->image_title		= $data->image_title;
		$imagesTable->sync				= $data->sync;
        if(JSNImagesSourcesExternal::checkImageSelected($imagesTable->image_extid,$imagesTable->showlist_id) > 0){

        }else{
         return $imagesTable->store(array('replcaceSpace' => false));
        }
	}*/

	/**
	 *
	 * Get video details
	 * @param String $videoID
	 */
	public function getImage($imageID)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select("*");
		$query->from("#__imageshow_images");
		$query->where("image_extid=".$db->quote($videoID));
		$query->where("showlist_id=".$this->_source['showlistID']);
		$db->setQuery($query);
		return $db->loadObject();
	}

	/**
	 *
	 * Get showlist mode
	 */
	public function getShowlistMode()
	{
		return false;
	}
	/*
	 *
	 * Check sync if exists
	 * @param String $syncName
	 */
	public function checkSync($syncName)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select("count(image_id)" );
		$query->from("#__imageshow_images");
		$query->where("showlist_id=".$this->_source['showlistID']);
		$query->where("album_extid = ".$db->quote( $syncName ));
		$db->setQuery($query);
		//echo  $query;
		return $db->loadResult() > 0 ? true : false ;
	}
	 function convertXmlToTreeMenu($xmlarray,$catSelected)
	{
		$categories = '';
		if(isset($xmlarray[0])){
			foreach($xmlarray as $node){
				$selected = (JSNImagesSourcesExternal::checkCatisSelected($node['@attributes']['data']))?' catselected':'';
				$catchoosed = ($catSelected == $node['@attributes']['data'] && $catSelected!='0')?' catchoosed':'';
				$categories.= '<li class="selectitem'.$selected.$catchoosed.'" id="'.$node['@attributes']['data'].'">'.$node['@attributes']['label'];
				$categories.= '<ul>';
				if(isset($node['node'])){
					foreach($node['node'] as $childnode){
						$selected1 = (JSNImagesSourcesExternal::checkCatisSelected($childnode['@attributes']['data']))?' catselected':'';
						$catchoosed = ($catSelected == $childnode['@attributes']['data'] && $catSelected!='0')?' catchoosed':'';
						$categories.= '<li class="'.$selected1.$catchoosed.'" id="'.$childnode['@attributes']['data'].'">'.$childnode['@attributes']['label'].'</li>';
					}
				}
				$categories.= '</ul>';
				$categories.= '</li>';
			}
		}else{
			$selected = (JSNImagesSourcesExternal::checkCatisSelected($xmlarray['@attributes']['data']))?' catselected':'';
			$catchoosed = ($catSelected == $xmlarray['@attributes']['data'] && $catSelected!='0')?' catchoosed':'';
			$categories.= '<li class="'.$selected.$catSelected.'" id="'.$xmlarray['@attributes']['data'].'">'.$xmlarray['@attributes']['label'];
			$categories.= '<ul>';
			if(isset($xmlarray['node'])){
				foreach($xmlarray['node'] as $xmlarray){
					$selected1 = (JSNImagesSourcesExternal::checkCatisSelected($xmlarray['@attributes']['data']))?' catselected':'';
					$catchoosed = ($catSelected == $xmlarray['@attributes']['data'] && $catSelected!='0')?' catchoosed':'';
					$categories.= '<li class="'.$selected1.$catchoosed.'" id="'.$xmlarray['@attributes']['data'].'">'.$xmlarray['@attributes']['label'].'</li>';
				}
			}
			$categories.= '</ul>';
			$categories.= '</li>';
		}

		return $categories;
	}


	function checkCatisSelected($catId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select("count(image_id)" );
		$query->from("#__imageshow_images");
		$query->where("album_extid = ".$db->quote( $catId ));
		$query->where("showlist_id = ".$db->quote( $this->_source['showlistID'] ));
		$db->setQuery($query);
		//echo  $query;
		return $db->loadResult() > 0 ? true : false ;
	}
    /**
     *  ##################################################################################################################
     *  End Code update for new function of image show (change from manage by flash to javascript)
     * ###################################################################################################################
    */

}
