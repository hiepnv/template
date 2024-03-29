<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_showlist.php 11377 2012-02-25 03:58:05Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );

class JSNISShowlist
{
	var $_db = null;

	function JSNISShowlist()
	{
		if ($this->_db == null) {
			$this->_db = JFactory::getDBO();
		}

		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'tables');
	}

	public static function getInstance()
	{
		static $instanceShowlist;
		if ($instanceShowlist == null)
		{
			$instanceShowlist = new JSNISShowlist();
		}
		return $instanceShowlist;
	}

	function getTitleShowList($showListID)
	{
		$query 	= "SELECT showlist_title
				   FROM #__imageshow_showlist
				   WHERE showlist_id = ".(int)$showListID;
		$this->_db->setQuery($query);
		return $this->_db->loadRow();
	}

	function renderShowlistComboBox($ID, $elementText, $elementName, $parameters = '')
	{
		$query	= 'SELECT showlist_title AS text, showlist_id AS value
				   FROM #__imageshow_showlist
				   ORDER BY showlist_title ASC';
		$this->_db->setQuery($query);
		$data 	= $this->_db->loadObjectList();

		array_unshift($data, JHTML::_('select.option', '0', '- '.JText::_($elementText).' -', 'value', 'text'));
		return JHTML::_('select.genericlist', $data, $elementName, $parameters, 'value', 'text', $ID);
	}

	function updateDateModifiedShowlist($showListID)
	{
		$dbo 						= JFactory::getDbo();
		$date 						= JFactory::getDate();
		$showlist 					= new stdClass;
		$showlist->showlist_id 		= $showListID;
		$showlist->date_modified 	= JFactory::getDate()->format($dbo->getDateFormat());//JHTML::_('date', $date->toUnix(), '%Y-%m-%d %H:%M:%S');

		$this->_db->updateObject( '#__imageshow_showlist', $showlist, 'showlist_id' );
	}

	function getShowListByID($showlistID, $published = true, $resultType = 'loadAssoc')
	{
		$condition = '';

		if ($published == true)
		{
			$condition = ' published = 1 AND ';
		}

		$query 	= 'SELECT * FROM #__imageshow_showlist WHERE '.$condition.' showlist_id = '.(int)$showlistID;
		$this->_db->setQuery($query);

		return $this->_db->$resultType();
	}

	function countShowList()
	{
		$query	= 'SELECT COUNT(*) FROM #__imageshow_showlist';
		$this->_db->setQuery( $query );
		return $this->_db->loadRow();
	}

	function getListShowlistIDByConfigID($configIDs)
	{
		$query 	= 'SELECT showlist_id FROM #__imageshow_showlist WHERE image_source_profile_id IN ('.$configIDs.')';
		$this->_db->setQuery( $query );
		return $this->_db->loadRowList();
	}

	// prepare data showlist for encode to json data
	function getShowlist2JSON($URL, $showlistID)
	{
		$objJSNShowlist = JSNISFactory::getObj('classes.jsn_is_showlist');
		$showlistTable  = JTable::getInstance('showlist', 'Table');

		$images = array();

		if ($showlistTable->load($showlistID) && $showlistTable->image_source_name)
		{
			$imageSource 	= JSNISFactory::getSource($showlistTable->image_source_name, $showlistTable->image_source_type, $showlistID);
			$images		 	= $imageSource->getImages2JSON(array('URL' => $URL, 'showlist_id' => $showlistID, 'limitEdition' => true));
		}

		$showlistInfo 	= $objJSNShowlist->getShowListByID($showlistID);

		$dataObj = new stdClass();

		//showlist
		$showlistObj = new stdClass();
		$showlistObj->{'title'} 		= $showlistInfo['showlist_title'];
		$showlistObj->{'description'} 	= $showlistInfo['description'];
		$showlistObj->{'link'} 			= $showlistInfo['showlist_link'];

		$overrideTitle = $showlistInfo['override_title'];
		$overrideDesc  = $showlistInfo['override_description'];
		$overrideLink  = $showlistInfo['override_link'];
		$showExifData  = $showlistInfo['show_exif_data'];

			//images object
			$imagesObj 		= new stdClass();
			$arrayImage 	= array();

			foreach ($images as $image)
			{
				if ($overrideTitle == 1) {
					$image->title = $showlistInfo['showlist_title'];
				}

				if ($overrideDesc == 1) {
					$image->description = $showlistInfo['description'];
				}

				if ($overrideLink == 1) {
					$image->link = $showlistInfo['showlist_link'];
				} else {
					$image->link = JRoute::_($image->link, false);
				}
				$tmpExifData = '';
				if (@$image->exif_data != '')
				{
					$tmpExifData = '('.@$image->exif_data.')';
				}
				if ($showExifData == 'title')
				{
					$image->title = $image->title.' '.$tmpExifData;
				}
				elseif ($showExifData == 'description')
				{
					if ($image->description != '')
					{

						$image->description = $image->description."\n".$tmpExifData;
					}
					else
					{
						$image->description = $image->description.$tmpExifData;
					}
				}
				else
				{

				}
				$arrayImage[] = $image;
			}

			$imagesObj->{'image'} = $arrayImage;
			// end images object

		$showlistObj->{'images'} 	= $imagesObj;
		$dataObj->{'showlist'} 		= $showlistObj;
		// end show list

		return $dataObj;
	}

	function insertHitsShowlist($showListID)
	{
		$query 	= 'UPDATE #__imageshow_showlist SET hits = hits + 1 WHERE showlist_id = '.(int)$showListID;
		$this->_db->setQuery($query);
		$this->_db->query();
	}

	function getShowlistID()
	{
		$arrayID = array();
		$query	= 'SELECT showlist_id FROM #__imageshow_showlist';

		$this->_db->setQuery( $query );
		$result = $this->_db->loadAssocList();

		if(count($result))
		{
			foreach ($result as $value)
			{
				$arrayID[] = $value['showlist_id'];
			}

			return $arrayID;
		}
		return false;
	}

	function checkShowlistLimition()
	{
		$objJSNUtils 		= JSNISFactory::getObj('classes.jsn_is_utils');
		$limitStatus		= $objJSNUtils->checkLimit();
		$count 				= $this->countShowlist();

		if(@$count[0] >= 3 && $limitStatus == true)
		{
			$msg = JText::_('SHOWLIST_YOU_HAVE_REACHED_THE_LIMITATION_OF_3_SHOWLISTS_IN_FREE_EDITION');
			JError::raiseNotice(100, $msg);
		}
	}

	function getLastestShowlist($limit = 1)
	{
		$query 	= 'SELECT showlist_title, showlist_id  FROM #__imageshow_showlist ORDER BY date_modified DESC';
		$this->_db->setQuery($query, 0, $limit);
		return $this->_db->loadObjectList();
	}

	function getListShowlistBySource($sourceID, $sourceName)
	{
		$query 	= 'SELECT * FROM #__imageshow_showlist sl
				   INNER JOIN #__imageshow_source_profile p
				   ON p.external_source_profile_id = sl.image_source_profile_id
				   WHERE external_source_id ='.(int)$sourceID .'
				   AND sl.image_source_name = '.$this->_db->quote($sourceName);

		$this->_db->setQuery( $query );
		return $this->_db->loadObjectList();
	}

	function checkRecordShowlist()
	{
		$db 	=& JFactory::getDBO();
		$query 	= 'SELECT COUNT(showlist_id) FROM #__imageshow_showlist';
		$db->setQuery($query);
		$result    =  $db->loadRow();
		if(count($result))
		{
			if($result[0] != 0)
			{
				return true;
			}
		}
		return false;
	}

	function getShowlistIDs($ids)
	{
		$query	= 'SELECT * FROM #__imageshow_showlist WHERE showlist_id IN ('.$ids.') AND published=1 ORDER BY ordering ASC';
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}
}