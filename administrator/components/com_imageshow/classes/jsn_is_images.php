<?php
/*------------------------------------------------------------------------
# Full Name of JSN Extension(e.g: JSN ImagesShow)
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
# @license - GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
# @version $Id: jsn_is_images.php 13115 2012-06-07 09:54:11Z giangnd $
-------------------------------------------------------------------------*/
defined('_JEXEC') or die( 'Restricted access' );

class JSNISImages
{
	var $_db = null;

	function JSNISImages()
	{
		if($this->_db == null)
		{
			$this->_db = JFactory::getDBO();
		}
	}

	public static function getInstance()
	{
		static $instanceImages;

		if ($instanceImages == null)
		{
			$instanceImages = new JSNISImages();
		}
		return $instanceImages;
	}

	function getImagesByShowlistID($showListID, $resultType = 'loadObjectList')
	{
		$query 	= 'SELECT
						image_id, image_title,
						image_small, image_big, image_medium,
						image_description, image_link,
						image_extid, album_extid, image_size, custom_data, exif_data
				   FROM #__imageshow_images
				   WHERE showlist_id =' .(int) $showListID . '
				   ORDER BY ordering ASC';
		$this->_db->setQuery($query);
		return $this->_db->$resultType();
	}

	function countImagesShowList($showlistID)
	{
		$query 	= 'SELECT COUNT(*) FROM #__imageshow_images
				   WHERE showlist_id ='.(int) $showlistID;

		$this->_db->setQuery($query);
		return $this->_db->loadRow();
	}

	function getImageExtByShowlistID($showlistID, $resultType = 'loadResultArray')
	{
		$query = "SELECT image_extid
				  FROM #__imageshow_images
				  WHERE showlist_id = ".(int) $showlistID;
		$this->_db->setQuery($query);
		return $this->_db->$resultType();
	}

	function getMaxOrderingByShowlistID($showListID)
	{
		$query 	= "SELECT MAX(ordering) FROM #__imageshow_images WHERE showlist_id = ".$showListID." GROUP BY showlist_id";
		$this->_db->setQuery($query);
		return $this->_db->loadRow();
	}

	function updateOrder($arrayOrdering, $showlistID)
	{
		if (count($arrayOrdering) > 0 && !empty($showlistID))
		{
			foreach ($arrayOrdering as $key => $value)
			{
				$query = "UPDATE #__imageshow_images
						  SET ordering = ".$this->_db->quote( $this->_db->getEscaped( $value ), false )."
						  WHERE image_extid = ".$this->_db->quote( $this->_db->getEscaped( $key ), false )."
						  AND showlist_id = ".(int)$showlistID;

				$this->_db->setQuery($query);

				$result = $this->_db->query();
			}

			if ($result)
			{
				return true;
			}
		}
		return false;
	}

	function getImagesByAlbumID($albumId, $showlistID)
	{
		$query = "SELECT
					image_id, image_extid,
					album_extid, showlist_id
				  FROM #__imageshow_images
				  WHERE showlist_id = ".(int)$showlistID."
				  AND album_extid = ".$this->_db->Quote($this->_db->getEscaped( $albumId, false ), false );

		$this->_db->setQuery($query);
		return $this->_db->loadAssocList();
	}

	function updateImageDetail($arrayKeyImages, $arrayPost)
	{
		$imageTable 	= JTable::getInstance('images','Table');
		$objJSNShowlist = JSNISFactory::getObj('classes.jsn_is_showlist');

		if (count($arrayKeyImages) > 0)
		{
			foreach ($arrayKeyImages as $imageExtID)
			{
				$query = 'SELECT image_id
						  FROM #__imageshow_images
						  WHERE showlist_id = '.(int)$arrayPost['showlistID'].'
						  AND image_extid = '.$this->_db->quote($imageExtID).'
						  GROUP BY image_id';
				$this->_db->setQuery($query);
				$result = $this->_db->loadResult();

				if ($result)
				{
					if ($imageTable->load((int)$result))
					{
						//image folder
						$realPath 	= str_replace('/', DS, $arrayPost['imgBig'][$imageExtID]);
						$realPath 	= JPATH_ROOT.DS.$realPath;
						$imageSize 	= @filesize($realPath);
						$imageTable->image_size			= @$imageSize;
						// image folder

						$imageTable->image_title 		= @$arrayPost['imgTitle'][$imageExtID];
						$imageTable->image_description 	= @$arrayPost['imgDescription'][$imageExtID];
						$imageTable->image_link 		= @$arrayPost['imgLink'][$imageExtID];
						$imageTable->image_small		= @$arrayPost['imgSmall'][$imageExtID];
						$imageTable->image_medium		= @$arrayPost['imgMedium'][$imageExtID];
						$imageTable->image_big			= @$arrayPost['imgBig'][$imageExtID];
						$imageTable->custom_data 		= @$arrayPost['customData'][$imageExtID];

						if ($imageTable->store(array('replaceSpace' => true)))
						{
							$objJSNShowlist->updateDateModifiedShowlist((int)$imageTable->showlist_id);
						}
					}
				}
			}
		}
	}

	function getImagesByImageID($arrayImageID)
	{
		if (count($arrayImageID) > 0)
		{
			$stringImageID = implode(',', $arrayImageID);

			$query 	= 'SELECT
							image_id, image_title,
							image_description, image_link,
							image_extid, custom_data
					   FROM #__imageshow_images
					   WHERE image_id IN ('.$stringImageID.')';

			$this->_db->setQuery($query);
			return $this->_db->loadObjectList();
		}
		return false;
	}

	function checkImageLimition($showlistID)
	{
		$objJSNUtils 		= JSNISFactory::getObj('classes.jsn_is_utils');
		$limitStatus	    = $objJSNUtils->checkLimit();
		$count 				= $this->countImagesShowList($showlistID);

		if (@$count[0] >= 10 && $limitStatus == true)
		{
			return true;
		}
		return false;
	}

	function getSyncAlbumsByShowlistID($showlistID)
	{
		$query = 'SELECT album_extid
				  FROM #__imageshow_images
				  WHERE showlist_id ='.(int)$showlistID.'
				  AND sync = 1
				  GROUP BY album_extid';

		$this->_db->setQuery($query);
		return $this->_db->loadResultArray();
	}

	function saveSyncAlbum($showlistID, $arrayAlbum)
	{
		if ($this->_db->query() && count($arrayAlbum) > 0)
		{
			$imagesTable = JTable::getInstance('images', 'Table');

			foreach ($arrayAlbum as $album)
			{
				$imagesTable->showlist_id = $showlistID;
				$imagesTable->album_extid = $album;
				$imagesTable->sync		  = 1;

				if ($imagesTable->store() == false) {
					return false;
				}

				$imagesTable->image_id = null;
			}
		}

		return true;
	}

	function getImagesBySourceName($sourceName)
	{
		$query = 'SELECT * FROM #__imageshow_showlist sl INNER JOIN #__imageshow_images img ON sl.showlist_id = img.showlist_id WHERE sl.image_source_name = '.$this->_db->Quote($sourceName);
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}

	function getArrayImgExtID($showListID)
	{
		$arrayID 	= array();
		$query		= 'SELECT image_extid
					   FROM #__imageshow_images
					   WHERE sync = 0 AND showlist_id='.(int)$showListID;

		$this->_db->setQuery($query);

		$items = $this->_db->loadAssocList();

		if (count($items))
		{
			foreach ($items as $value) {
				$arrayID [] = $value['image_extid'];
			}
		}

		return $arrayID;
	}

	/*
	* get all categories
	*/
	function getAllCatShowlist($showListID)
	{
		$query = 'SELECT DISTINCT album_extid FROM #__imageshow_images WHERE showlist_id = '.(int) $showListID.' ORDER BY image_id';
		$this->_db->setQuery($query);
		return $this->_db->loadRow();
	}


	function removeAllImagesByShowlistID($showlistID)
	{


		if (!empty($showlistID))
		{
			$showlistTable = JTable::getInstance('showlist', 'Table');

			if ($showlistTable->load($showlistID))
			{
				$imageSource = JSNISFactory::getSource($showlistTable->image_source_name, $showlistTable->image_source_type, $showlistID);
				$imageSource->removeAllImages(array('showlist_id' => $showlistID));

				$listSource   = $imageSource->getListSources();

				return $listSource;
			}
		}
	}

	/**
	* Add images to database
	*/

	function addImages($infoInsert)
	{

		global $objectLog;
		//$objJSNImages		= JSNISFactory::getObj('classes.jsn_is_images');
		$objJSNUtils 		= JSNISFactory::getObj('classes.jsn_is_utils');
		$objJSNShowlist		= JSNISFactory::getObj('classes.jsn_is_showlist');

		$showlistTable 		= JTable::getInstance('showlist', 'Table');
		$showlistTable->load($infoInsert['showlistID']);

		$imageSource 		= JSNISFactory::getSource($showlistTable->image_source_name, $showlistTable->image_source_type, $showlistTable->showlist_id);
		$user				= JFactory::getUser();
		$userID				= $user->get('id');

		$showlistTitle 				= $objJSNShowlist->getTitleShowList($infoInsert['showlistID']);
		$imgArrayLocalExtID 		= $this->getImageExtByShowlistID($infoInsert['showlistID']);
		$ordering 					= JRequest::getVar('ordering');
		if (!is_null($imgArrayLocalExtID) && !empty($imgArrayLocalExtID))
		{
			$arrayImagesRemove = array_diff($imgArrayLocalExtID, $infoInsert['imgExtID']);

			// remove images that not selected
			if (count($arrayImagesRemove))
			{
				$infoDelete 				 = array();
				$infoDelete['imgExtID'] 	 = array_values($arrayImagesRemove);
				$infoDelete['showlistID'] 	 = $infoInsert['showlistID'];

				/*$deleteImageExist 			 = $imageSource->removeImages($infoDelete);

				if ($deleteImageExist == false) {
					return JSNISFlex::bindObject(false, JText::_('SHOWLIST_FLEX_CAN_NOT_REMOVE_AVAILABLE_IMAGES_FROM_SHOWLIST'), '');
				}*/
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
				//$this->updateImageDetail($arrayExtIDImages, $infoInsert);

				// insert new images
				if (count($arrayNewImages))
				{
					$infoInsert['imgExtID'] = array_values($arrayNewImages);
					$inserImageExist 		= $imageSource->saveImages($infoInsert);
					if ($inserImageExist == false)
					{
						//return JSNISFlex::bindObject(false, JText::_('SHOWLIST_FLEX_CAN_NOT_INSERT_IMAGES_AFTER_REMOVING_AVAILABLE_IMAGES_FROM_SHOWLIST'), '');
					}
				}
			}
		}
		else
		{
			if (count(@$infoInsert['imgExtID']) > 0 && !is_null(@$infoInsert['imgExtID'][0]) && !empty($infoInsert['imgExtID'][0]))
			{
				$insertImage = $imageSource->saveImages($infoInsert);
				if ($insertImage == false) {
					//return JSNISFlex::bindObject(false, JText::_('SHOWLIST_FLEX_CAN_NOT_INSERT_IMAGES'));
				}
			}
		}

		$this->updateOrder(@$infoInsert['order'] , $infoInsert['showlistID']);
		$objJSNShowlist->updateDateModifiedShowlist((int)$infoInsert['showlistID']);
		$objectLog->addLog($userID, JRequest::getURI(), $showlistTitle[0],'addimages','any');
	}



	function getImageInfo()
	{
		$post 			= JRequest::get('post');
		$arrayImageInfo = array();
		$config['showlist_id'] 		= JRequest::getVar('showlist_id');
		$config['album_extid'] 		= JRequest::getVar('album_extid');
		$config['image_extid'][0]	= JRequest::getVar('image_extid');
		if ($config['showlist_id'])
		{
			$showlistTable = JTable::getInstance('showlist', 'Table');

			if ($showlistTable->load($config['showlist_id']))
			{
				if($showlistTable->image_source_type == 'external'){
					$imageSource 	= JSNISFactory::getSource($showlistTable->image_source_name, $showlistTable->image_source_type, $showlistTable->showlist_id);
					$arrayImageInfo = $imageSource->getOriginalInfoImages($config);
					if(!empty($arrayImageInfo)){
							$query = "UPDATE #__imageshow_images
								  SET image_description = ".$this->_db->quote( $this->_db->getEscaped( $arrayImageInfo[0]->description ), false )."
								  ,custom_data = 0,
								  image_title =".$this->_db->quote( $this->_db->getEscaped( $arrayImageInfo[0]->title ), false ).",
								  image_link = ".$this->_db->quote( $this->_db->getEscaped( $arrayImageInfo[0]->link ), false )."
								  WHERE image_extid = ".$this->_db->quote( $this->_db->getEscaped( JRequest::getVar('image_extid') ), false )."
								  AND showlist_id = ".(int)$config['showlist_id'];
							$this->_db->setQuery($query);
							$this->_db->query();
					}
				}else{
					// reset detail of internal source
					$query = "UPDATE #__imageshow_images
						  SET image_description = '',custom_data = 0
						  WHERE image_extid = ".$this->_db->quote( $this->_db->getEscaped( JRequest::getVar('image_extid') ), false )."
						  AND showlist_id = ".(int)$config['showlist_id'];
					$this->_db->setQuery($query);
					$this->_db->query();
				}
			}
		}
	}

	function checkThumb($showlistID, $imageExid)
	{
		$query = "SELECT image_small FROM #__imageshow_images WHERE showlist_id = ".$this->_db->quote($this->_db->getEscaped($showlistID), false).' AND image_extid = '.$this->_db->quote($this->_db->getEscaped($imageExid), false);
		$this->_db->setQuery($query);
		$result = $this->_db->loadObject();
		if ($result)
		{
			if ((string) $result->image_small == '')
			{
				return false;
			}
			return true;
		}
		return false;
	}

	function updateImageThumb($showlistID, $imageExtid, $imageSmall)
	{
		$query = "UPDATE #__imageshow_images
				  SET image_small = ".$this->_db->quote($this->_db->getEscaped($imageSmall), false )."
				  WHERE image_extid = ".$this->_db->quote( $this->_db->getEscaped($imageExtid), false)."
				  AND showlist_id = ".(int) $showlistID;
		$this->_db->setQuery($query);
		return $this->_db->query();
	}
}