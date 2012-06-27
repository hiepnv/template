<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_backup.php 12339 2012-04-26 08:48:31Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.filesystem.file');
jimport('joomla.utilities.simplexml');

class JSNISBackup
{
	var $_xml 		= null;
	var $_db  		= null;
	var $_xmlString = '';
	var $_addElements = array();

	public static function getInstance()
	{
		static $instanceJSNISBackup;

		if ($instanceJSNISBackup == null)
		{
			$instanceJSNISBackup = new JSNISBackup();
		}

		return $instanceJSNISBackup;
	}

	function JSNISBackup()
	{
	}

	function createBackUpFileForMigrate()
	{
		$session 	= JFactory::getSession();
		$preVersion = (float) str_replace('.', '', $session->get('preversion', null, 'jsnimageshow'));
		$version400 = (float) str_replace('.', '', '4.0.0');
		//$preVersion = 313;
		if (!$preVersion) return;

		if ($preVersion < $version400) // old backup flow
		{
			$objJSNISMaintenance = JSNISFactory::getObj('classes.jsn_is_maintenance313', null, 'database');
		}
		else if ($preVersion >= $version400 ) // new backup flow
		{
			$objJSNISMaintenance = JSNISFactory::getObj('classes.jsn_is_maintenance', null, 'database');
		}
		$objectReadxmlDetail 	= JSNISFactory::getObj('classes.jsn_is_readxmldetails');
		$infoXmlDetail 			= $objectReadxmlDetail->parserXMLDetails();

		$xmlString  	= $objJSNISMaintenance->renderXMLData(true, true);
		$fileBackupName = "jsn_".JString::strtolower(@$infoXmlDetail['realName']).'_backup_db.xml';
		$fileZipName 	= 'jsn_is_backup_for_migrate_'.$preVersion.'_'.date('YmdHis').'.zip';

		if (JFile::write(JPATH_ROOT.DS.'tmp'.DS.$fileBackupName, $xmlString))
		{
			$config = JPATH_ROOT.DS.'tmp'.DS. $fileZipName;
			$zip 	= JSNISFactory::getObj('classes.jsn_is_archive', 'JSNISZIPFile', $config);
			$zip->setOptions(array('inmemory' => 1, 'recurse' => 0, 'storepaths' => 0));
			$zip->addFiles(JPATH_ROOT.DS.'tmp'.DS. $fileBackupName);
			$zip->createArchive();
			$zip->writeArchiveFile();
			$FileDelte = JPATH_ROOT.DS.'tmp'.DS. $fileBackupName;
			$session->set('jsn_is_backup_for_migrate', $fileZipName, 'jsnimageshow');
			return true;
		}
		return false;
	}

	function setSourceFromVersion3xx()
	{
		$session 	= JFactory::getSession();
		$preVersion = (float) str_replace('.', '', $session->get('preversion', null, 'jsnimageshow'));
		$version400 = (float) str_replace('.', '', '4.0.0');

		if ($preVersion && $preVersion < $version400)
		{
			$db =  JFactory::getDBO();
			$query = 'SELECT * FROM #__imageshow_showlist';
			$db->setQuery($query);
			$results = $db->loadObjectList();

			$sources = array('picasa' => false, 'flickr' => false, 'phoca' => false, 'joomgallery' => false);

			foreach ($results as $result)
			{
				switch ($result->showlist_source)
				{
					case '1': //folder
						//
					break;
					case '2': //flickr
						$sources['flickr'] = true;
					break;
					case '3': //picasa
						$sources['picasa'] = true;
					break;
					case '4': //phoca
						$sources['phoca'] = true;
					break;
					case '5': //joomgallery
						$sources['joomgallery'] = true;
					break;
				}
			}

			$requiredSource = array();

			foreach ($sources as $key => $value)
			{
				if ($value == true) {
					$requiredSource[] = $key;
				}
			}
			$session = JFactory::getSession();
			$session->set('JSNISImageSourceRequired3xxVersion', $requiredSource);
			return $requiredSource;
		}
	}
}