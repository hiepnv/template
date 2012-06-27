<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_showcase.php 11375 2012-02-24 10:19:14Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );

class JSNISShowcase
{
	var $_db = null;

	function JSNISShowcase()
	{
		if ($this->_db == null)
		{
			$this->_db = JFactory::getDBO();
		}
	}

	public static function getInstance()
	{
		static $instanceShowcase;
		if ($instanceShowcase == null)
		{
			$instanceShowcase = new JSNISShowcase();
		}
		return $instanceShowcase;
	}

	function getShowCaseTitle($showCaseID)
	{
		$query	= 'SELECT showcase_title FROM #__imageshow_showcase WHERE showcase_id='.(int)$showCaseID;
		$this->_db->setQuery( $query );
		return $this->_db->loadAssoc();
	}

	function getShowcaseID()
	{
		$arrayID 	= array();
		$query		= 'SELECT showcase_id FROM #__imageshow_showcase';
		$this->_db->setQuery( $query );
		$result 	= $this->_db->loadAssocList();

		if (count($result))
		{
			foreach ($result as $value)
			{
				$arrayID[] = $value['showcase_id'];
			}
			return $arrayID;
		}

		return false;
	}

	function countShowcase()
	{
		$query	= 'SELECT COUNT(*) FROM #__imageshow_showcase';
		$this->_db->setQuery( $query );
		return $this->_db->loadRow();
	}

	function getTotalShowcase()
	{
		$query 	= 'SELECT COUNT(*) FROM #__imageshow_showcase';
		$this->_db->setQuery($query);
		return $this->_db->loadRow();
	}

	function getLastestShowcase($limit = 1)
	{
		$query 	= 'SELECT showcase_title, showcase_id  FROM #__imageshow_showcase ORDER BY date_modified DESC';
		$this->_db->setQuery($query, 0, $limit);
		return $this->_db->loadObjectList();
	}

	function renderShowcaseComboBox($ID, $elementText, $elementName, $parameters = '')
	{
		$this->_db 	= JFactory::getDBO();
		$query	= 'SELECT showcase_title AS text, showcase_id AS value
				   FROM #__imageshow_showcase
				   ORDER BY showcase_title ASC';
		$this->_db->setQuery($query);
		$data 	= $this->_db->loadObjectList();

		array_unshift($data, JHTML::_('select.option', '0', '- '.JText::_($elementText).' -', 'value', 'text'));
		return JHTML::_('select.genericlist', $data, $elementName, $parameters, 'value', 'text', $ID);
	}

	// prepare data showcase for encode to json data
	function getShowcase2JSON ($data, $URL)
	{
		$document = JFactory::getDocument();
		$document->setMimeEncoding( 'application/json' );

		$dataObj 		= new stdClass();
		$showcaseObject = new stdClass();

		// general
		//$generalObj 							= new stdClass();
		//$generalObj->{'round-corner'} 			= $data->general_round_corner_radius;
		//$generalObj->{'border-stroke'} 			= $data->general_border_stroke;
		//$generalObj->{'background-color'} 		= $data->background_color;
		//$generalObj->{'border-color'} 			= $data->general_border_color;
		//$generalObj->{'number-images-preload'} 	= $data->general_number_images_preload;
		//$generalObj->{'images-order'}			= $data->general_images_order;
		//$showcaseObject->general	 			= $generalObj;
		//end general

		$objJSNShowcaseTheme = JSNISFactory::getObj('classes.jsn_is_showcasetheme');
		$showcaseTheme 		 = $objJSNShowcaseTheme->getShowcaseThemeByShowcaseID($data->showcase_id, $URL);

		if ($showcaseTheme == false)
		{
			$showcaseTheme = $objJSNShowcaseTheme->getDefaultThemeByThemeName($data->theme_name, $URL);
		}

		if (!empty($showcaseTheme))
		{
			foreach ($showcaseTheme as $key => $value)
			{
				$showcaseObject->$key = $value;
			}
		}

		$dataObj->{'showcase'} = $showcaseObject;

		return $dataObj;
	}

	function getShowCaseByID($showcaseID, $published = true,  $resultType = 'loadObject')
	{
		$condition = '';

		if ($published == true)
		{
			$condition = ' published = 1 AND ';
		}

		$query 	= 'SELECT * FROM #__imageshow_showcase WHERE '.$condition.' showcase_id = '.(int)$showcaseID;
		$this->_db->setQuery($query);

		return $this->_db->$resultType();
	}

	function checkShowcaseLimition()
	{
		$objJSNUtils 		= JSNISFactory::getObj('classes.jsn_is_utils');
		$limitStatus		= $objJSNUtils->checkLimit();
		$count 				= $this->countShowcase();

		if (@$count[0] >= 3 && $limitStatus == true)
		{
			$msg = JText::_('SHOWCASE_YOU_HAVE_REACHED_THE_LIMITATION_OF_3_SHOWCASES_IN_FREE_EDITION');
			JError::raiseNotice(100, $msg);
		}
	}

	function checkRecordShowcase()
	{
		$db 	=& JFactory::getDBO();
		$query 	= 'SELECT COUNT(showcase_id) FROM #__imageshow_showcase';
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
