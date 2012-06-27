<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_maintenance.php 11762 2012-03-15 08:47:44Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.filesystem.file');
jimport('joomla.utilities.simplexml');
require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'classes'.DS.'jsn_is_upgradedbutil.php';
require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'classes'.DS.'jsn_is_showcasetheme.php';
class JSNISMaintenance
{
	var $_xml 			= null;
	var $_db  			= null;
	var $_xmlString 	= '';
	var $_header		= '';
	var $_tagRoot  		= '';
	var $_objectManifest = null;

	function JSNISMaintenance($tag)
	{
		$this->_setTagRoot($tag);
		$this->_setObjManifest();
		//$this->_setHeader();
		$this->_xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><'.$this->_tagRoot.'></'.$this->_tagRoot.'>');
		$this->_setAttributteTagRoot();
		$this->_db  = JFactory::getDBO();
	}

	function _setTagRoot($tag)
	{
		$this->_tagRoot = $tag;
	}

	function _setObjManifest()
	{
		$this->_objectManifest 	= JSNISFactory::getObj('classes.jsn_is_readxmldetails');
	}

	function _setAttributteTagRoot()
	{
		$objConfig 		   	= JFactory::getConfig();
		$database	   		= $objConfig->getValue('config.db');
		$manifestInfo 		= $this->_objectManifest->parserXMLDetails();
		$this->_xml->addAttribute('name', $database);
		$this->_xml->addAttribute('version', @$manifestInfo['version']);
		$this->_xml->addAttribute('joomla_version', JVERSION);

		$objJSNplugin = JSNISFactory::getObj('classes.jsn_is_plugins');
		$themes 	  = $objJSNplugin->getListPluginElement('theme');
		$sources 	  = $objJSNplugin->getListPluginElement('source');
		$this->_xml->addAttribute('sources', implode(',', $sources));
		$this->_xml->addAttribute('themes', implode(',', $themes));
	}

	function _setHeader()
	{
		$objConfig 		   	= JFactory::getConfig();
		$database	   		= $objConfig->getValue('config.db');
		$this->_header  	= '<?xml version="1.0" encoding="UTF-8"?>'."\n";
		$this->_header 		.= '<!--'.'-'."\n" .'JSN ImageShow Backup File' . "\n" .
			                  '-'."\n" .
			                  '- Database: ' . $database . "\n" .
			                  '- Database Server: ' . $database . "\n" .
			                  '-'."\n" .
			                  '- Backup Date: ' . date("F j, Y, g:i a") . "\n\n".
			                  '-->';
	}

	function _renderTableData($tables, $root = true)
	{
		foreach ($tables as $tagName => $table)
		{
			$tableInfo 	 = $this->_db->getTableFields($table, false);
			$countField  = count($tableInfo[$table]);
			$fields		 = array();
			if(count($countField))
			{
				foreach ($tableInfo[$table] as $value) {
					$fields [] = $value->Field;
				}

				$query  = 'SELECT ' . implode(',', $fields) . ' FROM ' .$table;
				$this->_db->setQuery($query);
				$datas  = $this->_db->loadAssocList();

				if(count($datas))
				{
					if ($root) {
						$root = $this->_xml->addChild($tagName.'s');
					} else {
						$root = $this->_xml;
					}

					foreach ($datas as $data)
					{
						$subroot = $root->addChild($tagName);
						reset($fields);

						foreach ($fields as $fieldValue) {
							$subroot->addAttribute($fieldValue, $data[$fieldValue]);
						}
					}
				}
				else
				{
					return false;
				}

			}
			else
			{
				return false;
			}
		}
	}

	function renderXMLData($showlist, $showcase)
	{
		$this->_xmlString  = $this->_header;

		if ($showlist) {
			$this->_renderShowListData();
			$this->_renderSourceProfileData();
			$this->_renderSourceData();
		}

		if ($showcase) {
			$this->_renderShowcaseData();
			$this->_renderThemeData();
			$this->_renderThemeProfileData();
		}

		$this->_renderParameterData();
		$this->_xmlString .= $this->_xml->asXML();
		return $this->_xmlString;
	}

	function _renderTableThemeData($datas)
	{
		$rootTheme		=  $this->_xml->addChild('themes');
		foreach ($datas as $tagName => $tables)
		{
			if (count($tables['tables']))
			{
				$root 		= $rootTheme->addChild($tagName);
				$root->addAttribute('version', $tables['version']);
				$rootTables	= $root->addChild('tables');
				foreach ($tables['tables'] as $table)
				{
					$tableInfo 	 = $this->_db->getTableFields($table, false);
					$countField  = count($tableInfo[$table]);
					$fields		 = array();
					if(count($countField))
					{
						foreach ($tableInfo[$table] as $value)
						{
							$fields [] = $value->Field;
						}

						$query  = 'SELECT ' . implode(',', $fields) . ' FROM ' .$table;
						$this->_db->setQuery($query);
						$datas  = $this->_db->loadAssocList();

						if(count($datas))
						{
							$rootTable 		= $rootTables->addChild('table');
							$rootTable->addAttribute('name', $table);
							$rootRecords 	= $rootTable->addChild('records');
							foreach ($datas as $data)
							{
								$subroot = $rootRecords->addChild('record');
								reset($fields);

								foreach ($fields as $fieldValue) {
									$subroot->addAttribute($fieldValue, $data[$fieldValue]);
								}
							}
						}
					}
				}
			}
		}
	}

	function _renderThemeData()
	{
		$objJSNPlugins			= JSNISFactory::getObj('classes.jsn_is_plugins');
		$objJSNISShowcaseTheme  = JSNISShowcaseTheme::getInstance();
		$themes 				= $objJSNPlugins->getFullData('theme');
		$themeTables 			= array();
		if (count($themes))
		{
			foreach ($themes as $theme)
			{
				$objJSNISShowcaseTheme->loadTheme($theme->element);
				$result	= $objJSNISShowcaseTheme->triggerThemeEvent('list'.ucfirst($theme->element).'Table');

				if (count($result))
				{
					$themeTables [$theme->element]['tables'] = $result[0];
					$themeTables [$theme->element]['version'] = $theme->version;
				}
			}
		}

		if (count($themeTables))
		{
			$this->_renderTableThemeData($themeTables);
		}
	}

	function _renderSourceData()
	{
		$objJSNSource     		= JSNISFactory::getObj('classes.jsn_is_source');
		$sources 				= $objJSNSource->_listSource;
		$sourceTables 			= array();
		$result					= array();
		if (count($sources))
		{
			foreach ($sources as $source)
			{
				if(isset($source->pluginInfo))
				{
					if ($source->type == 'external')
					{
						$objJSNSource->loadSource($source->pluginInfo->element);
						$result		  = $objJSNSource->triggerSourceEvent('list'.ucfirst($source->pluginInfo->element).'Tables');

						if (count($result))
						{
							$sourceTables [$source->pluginInfo->element]['tables']= $result[0];
							$manifestCache = json_decode($source->pluginInfo->manifest_cache);
							$sourceTables [$source->pluginInfo->element]['version']= $manifestCache->version;
						}
					}
				}
			}
		}
		if (count($sourceTables))
		{
			$this->_renderTableSourceData($sourceTables);
		}
	}

	function _renderTableSourceData($datas)
	{
		$rootSource		=  $this->_xml->addChild('sources');
		foreach ($datas as $tagName => $tables)
		{
			if (count($tables['tables']))
			{
				$root 		= $rootSource->addChild($tagName);
				$root->addAttribute('version', $tables['version']);
				$rootTables	= $root->addChild('tables');
				foreach ($tables['tables'] as $table)
				{
					$tableInfo 	 = $this->_db->getTableFields($table, false);
					$countField  = count($tableInfo[$table]);
					$fields		 = array();
					if(count($countField))
					{
						foreach ($tableInfo[$table] as $value)
						{
							$fields [] = $value->Field;
						}

						$query  = 'SELECT ' . implode(',', $fields) . ' FROM ' .$table;
						$this->_db->setQuery($query);
						$datas  = $this->_db->loadAssocList();

						if(count($datas))
						{
							$rootTable 		= $rootTables->addChild('table');
							$rootTable->addAttribute('name', $table);
							$rootRecords 	= $rootTable->addChild('records');
							foreach ($datas as $data)
							{
								$subroot = $rootRecords->addChild('record');
								reset($fields);

								foreach ($fields as $fieldValue) {
									$subroot->addAttribute($fieldValue, $data[$fieldValue]);
								}
							}
						}
					}
				}
			}
		}
	}

	function _renderShowcaseData()
	{
		$table = array('showcase'=> '#__imageshow_showcase');
		$this->_renderTableData($table);
	}

	function _renderSourceProfileData()
	{
		$table = array('source_profile'=> '#__imageshow_source_profile');
		$this->_renderTableData($table);
	}

	function _renderThemeProfileData()
	{
		$table = array('theme_profile'=> '#__imageshow_theme_profile');
		$this->_renderTableData($table);
	}

	function _renderParameterData()
	{
		$table = array('parameter'=> '#__imageshow_parameters');
		$this->_renderTableData($table, false);
	}

	function _renderShowListData()
	{
		$objJSNUtil  	  = JSNISFactory::getObj('classes.jsn_is_utils');
		$joomlaGroupLevel = $objJSNUtil->getJoomlaLevelName();

		$tableInfo 	 = $this->_db->getTableFields('#__imageshow_showlist', false);
		$countField  = count($tableInfo['#__imageshow_showlist']);
		$fields		 = array();
		$showListID	 = 0;
		if(count($countField))
		{
			foreach ($tableInfo['#__imageshow_showlist'] as $value) {
				$fields [] = $value->Field;
			}

			$query  = 'SELECT ' . implode(',', $fields) . ' FROM #__imageshow_showlist';

			$this->_db->setQuery($query);
			$datas  = $this->_db->loadAssocList();

			if(count($datas))
			{
				$root = $this->_xml->addChild('showlists');
				foreach ($datas as $data)
				{
					$subroot = $root->addChild('showlist');
					reset($fields);
					foreach ($fields as $fieldValue)
					{
						if ($fieldValue == 'access') {
							$data[$fieldValue] = $objJSNUtil->convertJoomlaLevelFromIDToName($joomlaGroupLevel, $data[$fieldValue]);
						}

						$subroot->addAttribute($fieldValue, $data[$fieldValue]);
						if ($fieldValue = 'showlist_id') {
							$showListID = $data[$fieldValue];
						}
					}
					$this->_renderImageData($showListID, $subroot);
				}
			}
			else
			{
				return false;
			}

		}
		else
		{
			return false;
		}
	}

	function _renderImageData($showlistID, $root)
	{
		$tableInfo 	 = $this->_db->getTableFields('#__imageshow_images', false);
		$countField  = count($tableInfo['#__imageshow_images']);
		$fields		 = array();
		if(count($countField))
		{
			foreach ($tableInfo['#__imageshow_images'] as $value) {
				$fields [] = $value->Field;
			}
			$query  = 'SELECT ' . implode(',', $fields) . ' FROM #__imageshow_images WHERE showlist_id = '. (int) $showlistID;
			$this->_db->setQuery($query);
			$datas  = $this->_db->loadAssocList();

			if(count($datas))
			{
				foreach ($datas as $data)
				{
					$subroot = $root->addChild('image');
					reset($fields);
					foreach ($fields as $fieldValue) {
						$subroot->addAttribute($fieldValue, $data[$fieldValue]);
					}
				}
			}
			else
			{
				return false;
			}

		}
		else
		{
			return false;
		}

	}

	function _checkTableExist($table)
	{
		$objUpgradeDBAction = new JSNJSUpgradeDBAction();
		return $objUpgradeDBAction->isExistTable($table);
	}
}