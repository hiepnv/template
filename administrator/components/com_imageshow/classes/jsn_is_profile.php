<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_profile.php 11375 2012-02-24 10:19:14Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );

class JSNISProfile
{
	var $_db = null;

	function JSNISProfile()
	{
		if ($this->_db == null)
		{
			$this->_db = JFactory::getDBO();
		}
	}

	public static function getInstance()
	{
		static $instanceProfile;
		if ($instanceProfile == null)
		{
			$instanceProfile = new JSNISProfile();
		}
		return $instanceProfile;
	}

	function getProfiles($title, $name)
	{
		JPluginHelper::importPlugin('jsnimageshow');
		$dispatcher = JDispatcher::getInstance();
		$agr 		= array(array('title' => $title, 'name' => $name));
		$queries 	= $dispatcher->trigger('onGetQueryProfile' , $agr);

		if (count($queries))
		{
			$runQuery = array();

			foreach ($queries as $value)
			{
				if (!empty($value)) {
					$runQuery[] = $value;
				}
			}

			$query = implode(' UNION ALL ', $runQuery);

			if (empty($query)) { return array();}

			$this->_db->setQuery($query);
			$result = $this->_db->loadObjectList();

			if (!is_array($result)) {
				$result = array();
			}

			$data = array();

			foreach ($result as $item)
			{
				$query = 'SELECT count(p.external_source_profile_id) as total, p.external_source_profile_id
						  FROM #__imageshow_source_profile p
						  INNER JOIN #__imageshow_external_source_'.$item->image_source_name.' source
						  	ON source.external_source_id = p.external_source_id
						  INNER JOIN #__imageshow_showlist sl
						  	ON sl.image_source_profile_id = p.external_source_profile_id
		    			  WHERE
		    			  		sl.image_source_name = '.$this->_db->quote($item->image_source_name).'
		    			  	AND
		    			  		p.external_source_id = '.(int)$item->external_source_id.'
	    			  		GROUP BY
	    			  			p.external_source_profile_id
		    			  		';
				$this->_db->setQuery($query);
				$result = $this->_db->loadObject();

				if ($result) {
					$item->totalshowlist 			  = $result->total;
					$item->external_source_profile_id = $result->external_source_profile_id;
				} else {
					$item->totalshowlist 			  = 0;
					$item->external_source_profile_id = null;
				}

				$data[] = $item;
			}

			return $data;
		}
		return array();
	}

	function deleteProfile($sourceID, $sourceName)
	{
		$query = 'SELECT sl.showlist_id FROM #__imageshow_source_profile p
				  INNER JOIN #__imageshow_showlist sl
				  	ON sl.image_source_profile_id = p.external_source_profile_id
				  WHERE
				  		p.external_source_id = '.(int)$sourceID.'
				  	AND
				  		sl.image_source_name = '.$this->_db->quote($sourceName);

		$this->_db->setQuery($query);

		$result = $this->_db->loadObjectList();

		if (count($result))
		{
			$showlistTable = JTable::getInstance('showlist', 'Table');

			foreach ($result as $showlist)
			{
				if ($showlistTable->load($showlist->showlist_id))
				{
					$imageSource = JSNISFactory::getSource($showlistTable->image_source_name, $showlistTable->image_source_type, $showlistTable->showlist_id);

					$imageSource->removeAllImages(array('showlist_id' => $showlistTable->showlist_id));
					$imageSource->_source['profileTable']->delete();

					$showlistTable->image_source_name = '';
					$showlistTable->image_source_type = '';
					$showlistTable->image_source_profile_id = 0;
					$showlistTable->store();
				}
			}
		}

		//remove source
		$query = 'DELETE FROM #__imageshow_external_source_'.$sourceName.'
				  WHERE external_source_id = '.(int)$sourceID;

		$this->_db->setQuery($query);
		$this->_db->query();

	}

	function getParameters()
	{
		$query 	= 'SELECT * FROM #__imageshow_parameters';
		$this->_db->setQuery($query);
		return $this->_db->loadObject();
	}

	function saveParameters($post)
	{
		$query 	= 'SELECT * FROM #__imageshow_parameters';
		$this->_db->setQuery( $query );
		$resultCheck = $this->_db->loadAssoc($query);
		if (count($resultCheck) > 0)
		{
			$query = 'UPDATE #__imageshow_parameters
					  SET
					  	show_quick_icons 	= '.(int) $post['show_quick_icons'].'
					  WHERE id = '.$resultCheck['id'];

			$this->_db->setQuery( $query );
			$result = $this->_db->query();

			if ($result)
			{
				return true;
			}
		}
		else
		{
			$query = 'INSERT INTO #__imageshow_parameters (show_quick_icons) VALUES ('.(int) $post['show_quick_icons'].')';
			$this->_db->setQuery($query);
			$result = $this->_db->query();

			if ($result)
			{
				return true;
			}
		}
		return false;
	}

	function getProfileInfo($id)
	{
		$query 	= 'SELECT * FROM #__imageshow_configuration WHERE configuration_id='. (int)$id;
		$this->_db->setQuery($query);
		return $this->_db->loadObject();
	}

	function checkExternalProfileExist($title, $source, $ignoreSourceID = 0)
	{
		$condition = '';

		if ($ignoreSourceID > 0) {
			$condition = ' AND external_source_id <> '.(int)$ignoreSourceID . ' ';
		}

		//$query = 'SELECT * FROM #__imageshow_external_source_'.$source.' WHERE BINARY external_source_profile_title LIKE '.$this->_db->quote($title).$condition;
		$query = 'SELECT * FROM #__imageshow_external_source_'.$source.' WHERE external_source_profile_title LIKE '.$this->_db->quote($title).$condition;
		$this->_db->setQuery($query);

		$result = $this->_db->loadResult();

		return ($result) ? true : false;
	}

	function countShowlistBaseOnProfileID($profileID)
	{
		$query = 'SELECT COUNT(showlist_id) FROM #__imageshow_showlist WHERE image_source_profile_id ='. (int)$profileID;
		$this->_db->setQuery($query);
		return $this->_db->loadResult();
	}

	function checkRecordConfiguration()
	{
		$db 	=& JFactory::getDBO();
		$query 	= 'SELECT COUNT(configuration_id) FROM #__imageshow_configuration';
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

}