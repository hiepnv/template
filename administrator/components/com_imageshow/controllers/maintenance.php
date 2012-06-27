<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: maintenance.php 13385 2012-06-18 11:29:33Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.controller' );
require_once(JPATH_COMPONENT.DS.'classes'.DS.'jsn_is_downloadpackagedirectly.php');
class ImageShowControllerMaintenance extends JController
{
	function __construct()
	{
		parent::__construct();
	}

	function display($cachable = false, $urlparams = false)
	{
		switch($this->getTask())
		{
			case 'login':
				JRequest::setVar('layout', 'default_login');
				JRequest::setVar('view', 'maintenance');
				JRequest::setVar('model', 'maintenance');
			break;
			default:
				JRequest::setVar( 'layout', 'default' );
				JRequest::setVar( 'view'  , 'maintenance' );
				JRequest::setVar( 'model'  , 'maintenance' );
		}
		parent::display();
	}

	function backup()
	{
		global $option;
		$model 		= $this->getModel( 'maintenance' );
		$filename	= JRequest::getVar('filename');
		$showLists	= JRequest::getInt('showlists');
		$showCases	= JRequest::getInt('showcases');
		$timestamp	= JRequest::getInt('timestamp');

		if ($showLists == 1)
		{
			$showLists = true;
		}
		else
		{
			$showLists = false;
		}

		if ($showCases == 1)
		{
			$showCases = true;
		}
		else
		{
			$showCases = false;
		}

		if ($timestamp == 1)
		{
			$timestamp = true;
		}
		else
		{
			$timestamp = false;
		}

		$result 	= $model->backup($showLists, $showCases, $timestamp, $filename);
		$link 		= 'index.php?option=com_imageshow&controller=maintenance&type=data&tab=1';

		if ($result == false)
		{
			$msg = JText::_('MAINTENANCE_BACKUP_YOU_MUST_SELECT_AT_LEAST_ONE_TYPE_TO_BACKUP');
			$this->setRedirect($link,$msg);
		}
	}

	function restore($fileHasUploadedInTempFolder = false)
	{
		global $objectLog;
		$user			= JFactory::getUser();
		$userID			= $user->get ('id');
		$file       	= JRequest::getVar( 'filedata', '', 'files', 'array' );
		$extensionFile 	= substr($file['name'], strrpos($file['name'],'.')+1 );
		$session 		= JFactory::getSession();
		$session->set('JSNISRestore', null);

		if ($extensionFile == 'zip')
		{
			$compressType 	= 1;
			$filepath 		= JPATH_ROOT.DS.'tmp';

			$config['path'] 		= $filepath;
			$config['file'] 		= $file;
			$config['compress'] 	= $compressType;
			$config['file_upload']  = $filepath.DS.$file['name'];

			$objJSNRestore 	= JSNISFactory::getObj('classes.jsn_is_restore');

			if ($fileHasUploadedInTempFolder == false) {
				$result = $objJSNRestore->restore($config);
			} else {
				$result = $objJSNRestore->restoreFromFileHasUploaded($config);
			}

			$link 			= 'index.php?option=com_imageshow&controller=maintenance&type=data&tab=1&myaction=restore';
			$requiredInstallData = $objJSNRestore->getListRequiredInstallData();
			$requiredInstallData['backup_file'] = $file;

			if ($result)
			{
				$objectLog->addLog($userID, JRequest::getURI(), $file['name'],'maintenance','restore');
				$session->set('JSNISRestore',
							array(
								'error' => false,
								'extractFile'=> $objJSNRestore->_extractFile,
								'message' => JText::_('MAINTENANCE_BACKUP_RESTORE_SUCCESSFULL'),
								'requiredSourcesNeedInstall' => $objJSNRestore->_requiredSourcesNeedInstall,
								'requiredThemesNeedInstall' => $objJSNRestore->_requiredThemesNeedInstall,
								'requiredInstallData' => $requiredInstallData
							));
				$this->setRedirect($link);
			}
			else
			{
				$session->set('JSNISRestore',
							array(
								'error' => true,
								'extractFile'=> $objJSNRestore->_extractFile,
								'message' => $objJSNRestore->_msgError,
								'requiredSourcesNeedInstall' => $objJSNRestore->_requiredSourcesNeedInstall,
								'requiredThemesNeedInstall' => $objJSNRestore->_requiredThemesNeedInstall,
								'requiredInstallData' => $requiredInstallData
							));
				$this->setRedirect($link);
			}
		}
		else
		{
			$msg = JText::_('MAINTENANCE_BACKUP_FORMAT_FILE_RESTORE_INCORRECT');
			$link = 'index.php?option=com_imageshow&controller=maintenance&type=data&tab=1&myaction=restore';
			$this->setRedirect($link, $msg);
		}
	}

	function cancel()
	{
		$link = 'index.php?option=com_imageshow';
		$this->setRedirect($link);
	}

	function reInstallLang()
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$array_BO		= JRequest::getVar('lang_array_BO', array(), 'post', 'array');
		$array_FO		= JRequest::getVar('lang_array_FO', array(), 'post', 'array');
		$objJSNLang 	= JSNISFactory::getObj('classes.jsn_is_language');
		$msg			= JText::_('MAINTENANCE_LANG_YOU_MUST_SELECT_AT_LEAST_ONE_LANGUAGE_TO_INSTALL');

		if (count($array_BO) > 0)
		{
			$msg = JText::_('MAINTENANCE_LANG_THE_LANGUAGE_HAS_BEEN_SUCCESSFULLY_INSTALLED');
			$objJSNLang->installationFolderLangBO($array_BO);
		}

		if (count($array_FO) > 0)
		{
			$msg = JText::_('MAINTENANCE_LANG_THE_LANGUAGE_HAS_BEEN_SUCCESSFULLY_INSTALLED');
			$objJSNLang->installationFolderLangFO($array_FO);
		}

		$link 	= 'index.php?option=com_imageshow&controller=maintenance&type=inslangs';
		$this->setRedirect($link, $msg);
	}

	function saveMessage()
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$status		= JRequest::getVar( 'status', array(), 'post', 'array' );
		$screen		= JRequest::getString('msg_screen');
		$objJSNMsg 	= JSNISFactory::getObj('classes.jsn_is_displaymessage');
		$objJSNMsg->setMessagesStatus($status, $screen);
		$link 	= 'index.php?option=com_imageshow&controller=maintenance&type=msgs';

		$this->setRedirect($link);
	}

	function refreshMessage()
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$objJSNMsg = JSNISFactory::getObj('classes.jsn_is_displaymessage');
		$objJSNMsg->refreshMessage();
		$link 	= 'index.php?option=com_imageshow&controller=maintenance&type=msgs';
		$this->setRedirect($link);
	}

	function setStatusMsg()
	{
		JRequest::checkToken('get') or jexit( 'Invalid Token' );
		$msgID 		= JRequest::getInt('msg_id');
		$objJSNMsg 	= JSNISFactory::getObj('classes.jsn_is_displaymessage');
		$objJSNMsg->setSeparateMessage($msgID);
	}

	function removeProfile()
	{
		global $mainframe, $objectLog;
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$user		= JFactory::getUser();
		$userID		= $user->get ('id');
		$sourceID 	= JRequest::getInt('external_source_id');
		$sourceName = JRequest::getString('image_source_name');
		$objJSNProfile = JSNISFactory::getObj('classes.jsn_is_profile');
		$objJSNProfile->deleteProfile($sourceID, $sourceName);
		$objectLog->addLog($userID, JRequest::getURI(), '1', 'profile', 'delete');
		exit();
	}

	function saveParam()
	{
		global $mainframe;
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$post 		   = JRequest::get('post');
		$objJSNProfile = JSNISFactory::getObj('classes.jsn_is_profile');
		$objJSNProfile->saveParameters($post);
		$objJSNUtils 		= JSNISFactory::getObj('classes.jsn_is_utils');
		$objJSNUtils->approveModule('mod_imageshow_quickicon', (int) $post['show_quick_icons']);
		$mainframe->redirect('index.php?option=com_imageshow&controller=maintenance&type=configs');
	}

	function saveProfile()
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$post = JRequest::get('post');

		$imageSource = JSNISFactory::getSource($post['source'], 'external');

		$imageSource->_source['sourceTable']->load($post['external_source_id']);

		$imageSource->_source['sourceTable']->bind($post);

		$imageSource->_source['sourceTable']->store();

		jexit();
	}

	function deleteTheme()
	{
		global $mainframe;
		$themeID = array();
		$id 	 = JRequest::getInt('plugin_theme_id', 0);
		$themeName = JRequest::getString('plugin_theme_name', '');

		if($id && $themeName != '')
		{
			$objJSNShowcaseTheme = JSNISFactory::getObj('classes.jsn_is_showcasetheme');
			$objJSNShowcaseTheme->deleteThemeProfileByThemeName($themeName);

			$themeID [] = $id;
			$model	= $this->getModel('installer');
			$model->uninstall($themeID);
		}

		$link = 'index.php?option=com_imageshow&controller=maintenance&type=themes';
		$mainframe->redirect($link);
	}

	function enableDisablePlugin()
	{
		global $mainframe;
		$arrayPluginID = JRequest::getVar('pluginID');
		$publishStatus = JRequest::getInt('publish');

		if (count($arrayPluginID) > 0)
		{
			$pluginTable = JTable::getInstance('extension', 'JTable');
			$pluginTable->publish($arrayPluginID, $publishStatus);
		}

		$link = 'index.php?option=com_imageshow&controller=maintenance&type=themes';
		$mainframe->redirect($link);
	}

	/*function installPluginManager()
	{
		$model	= $this->getModel('installer');
		$model->install();
		$link = 'index.php?option=com_imageshow&controller=maintenance&type=themes';
		$this->setRedirect($link);
	}*/

	/*function installImageSource()
	{
		$model	= $this->getModel('installer');
		$model->installImageSource();
		$link = 'index.php?option=com_imageshow&controller=maintenance&type=profiles';
		$this->setRedirect($link);
	}*/

	function checkEditProfileExist()
	{
		$get 			= JRequest::get('get');
		$objJSNProfile 	= JSNISFactory::getObj('classes.jsn_is_profile');
		$result 		= $objJSNProfile->checkExternalProfileExist(trim($get['external_source_profile_title']), $get['source'], $get['external_source_id']);

		$data['success'] = $result;

		if ($result){
			$data['msg'] = JText::_('MAINTENANCE_SOURCE_REQUIRED_FIELD_PROFILE_TITLE_EXIST');
		}

		$objJSNJSON = JSNISFactory::getObj('classes.jsn_is_json');

		echo $objJSNJSON->encode($data);

		jexit();
	}

	function validateProfile()
	{
		$get = JRequest::get('get');
		$data['success'] = true;
		$imageSource = JSNISFactory::getSource($get['source'], 'external');
		$data['success'] = $imageSource->getValidation($get);
		$data['msg'] = ($data['success'] == false) ? $imageSource->getErrorMsg() : '';
		$objJSNJSON = JSNISFactory::getObj('classes.jsn_is_json');

		echo $objJSNJSON->encode($data);

		jexit();
	}

	function deleteObsoleteThumbnails()
	{
		JRequest::checkToken('get') or jexit('Invalid Token');
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		$objJSNJSON 			= JSNISFactory::getObj('classes.jsn_is_json');
		$objJSNImage 			= JSNISFactory::getObj('classes.jsn_is_images');
		$objJSNImageThumb 		= JSNISFactory::getObj('classes.jsn_is_imagethumbnail');
		$data 					= array();
		$obsoleteImages			= array();
		$thumbnailPath			= JPATH_ROOT . DS . 'images' . DS . 'jsn_is_thumbs' . DS;
		$imagePath				= JPATH_ROOT . DS . 'images' . DS;
		$showlistImages			= array();

		if (JFolder::exists($imagePath) && is_writable($imagePath))
		{
			$dbImages 		= $objJSNImage->getImagesBySourceName('folder');
			$folderImages   = $objJSNImageThumb->getThumnails();

			if (count($dbImages))
			{
				for ($i = 0, $counti = count($dbImages); $i < $counti; $i++)
				{
					$dbImage = $dbImages[$i];
					$objJSNImageThumb->checkImageFolderStatus($dbImage);
					$showlistImages [] = JFile::getName(str_replace('/', DS, $dbImage->image_small));
				}
			}

			if (count($folderImages))
			{
				if (count($showlistImages))
				{
					foreach ($folderImages as $folderImage)
					{
						$isExsited = false;
						foreach ($showlistImages as $showlistImage)
						{
							if ($folderImage == $showlistImage)
							{
								$isExsited = true;
								break;
							}
						}

						if(!$isExsited)
						{
							$obsoleteImages[] = $folderImage;
						}
					}
				}
				else
				{
					$obsoleteImages = $folderImages;
				}
			}

			if (count($obsoleteImages))
			{
				foreach ($obsoleteImages as $obsoleteImage)
				{
					$path = $thumbnailPath.$obsoleteImage;
					if (@is_file($path))
					{
						@JFile::delete($path);
					}
				}
			}
			$data['existed_folder'] = true;
			$data['delete'] 		= true;
			$data['status'] 		= true;
			$data['message'] 		= '';
		}
		else
		{
			$data['existed_folder'] = false;
			$data['delete'] 		= false;
			$data['status'] 		= false;
			$data['message'] 		= JText::sprintf('MAINTENANCE_FOLDER_IS_UNWRITABLE_OR_DOES_NOT_EXIST', 'images');
		}
		echo $objJSNJSON->encode($data);
		jexit();
	}

	function saveThemeParameter()
	{
		JRequest::checkToken() or jexit('Invalid Token');
		$post = JRequest::get('post');
		$objJSNShowcaseTheme = JSNISFactory::getObj('classes.jsn_is_showcasetheme');
		$objJSNShowcaseTheme->importTableByThemeName($post['theme_name']);
		$table = JTable::getInstance($post['theme_name'].$post['theme_table'], 'Table');
		$table->bind($post);
		$table->store();
		jexit();
	}

	function uninstallImageSource()
	{
		JRequest::checkToken() or jexit('Invalid Token');
		$sourceIDs = array();
		$sourceID  = JRequest::getInt('plugin_source_id');

		if($sourceID)
		{
			$objJSNSource	= JSNISFactory::getObj('classes.jsn_is_source');
			$sourceInfo		= $objJSNSource->getSourceInfoByPluginID($sourceID);

			// remove images, profile record if have
			if ($sourceInfo) {
				$objJSNSource->uninstallImageSource($sourceInfo);
			}

			$sourceIDs[] 	= $sourceID;
			$model		  	= $this->getModel('installer');
			$model->uninstall(array($sourceID));
		}
	}

	function downloadSampleDataPackage()
	{
		$perm			= true;
		$foldername 	= 'tmp';
		$folderpath 	= JPATH_ROOT.DS.$foldername;
		$link 			= 'index.php?option=com_imageshow&controller=maintenance&type=data&tab=0';
		if (is_writable($folderpath))
		{
			$objJSNDownloadPackage = new JSNJSDownloadPackageDirectly(JSN_IMAGESHOW_FILE_URL);
			$result = $objJSNDownloadPackage->download();

			if ($result)
			{
				echo json_encode(array('download' => true, 'file_name'=> (string) $result));
			}
			else
			{
				$msg = JText::_('MAINTENANCE_SAMPLE_DATA_CANNOT_DOWNLOAD_INSTALLATION_FILE', true);
				echo json_encode(array('download' => false, 'message'=>$msg, 'redirect_link'=>$link));
			}
		}
		else
		{
			$msg = JText::sprintf('MAINTENANCE_SAMPLE_DATA_FOLDER_%S_MUST_HAVE_WRITABLE_PERMISSION', DS.'tmp');
			echo json_encode(array('download' => false, 'message'=>$msg, 'redirect_link'=>$link));
		}
		exit();
	}

	function installSampledata()
	{
		$objJSNSource	   					= JSNISFactory::getObj('classes.jsn_is_source');
		$objJSNTheme	   					= JSNISFactory::getObj('classes.jsn_is_themes');
		$sampleData 						= JSNISFactory::getObj('classes.jsn_is_sampledata');
		$objReadXmlDetail					= JSNISFactory::getObj('classes.jsn_is_readxmldetails');
		$objJSNJSON 						= JSNISFactory::getObj('classes.jsn_is_json');
		$objJSNUtils      					= JSNISFactory::getObj('classes.jsn_is_utils');

		$task 								= JRequest::getWord('task');
		$fileName							= JRequest::getVar('file_name');
		$link 								= 'index.php?option=com_imageshow&controller=maintenance&type=data&tab=0';
		$perm								= false;
		$foldertmp	 						= JPATH_ROOT.DS.'tmp';
		if (is_writable($foldertmp))
		{
			$perm = true;
		}
		$imageSources			= $objJSNSource->compareSources();
		$uninstalledSources  	= array();
		$sampleSources 	 		= array();
		$errors					= array();
		$themeElements			= array();
		$sourceElements			= array();
		$themes					= $objJSNTheme->compareSources();
		$uninstalledThemes  	= array();
		$sampleThemes 	 		= array();
		$elements				= array();
		$commercial				= false;

		if ($task == 'installSampledata')
		{
			if ($perm)
			{
				$inforPackage 	= $objReadXmlDetail->parserXMLDetails();
				$componentInfo 	= $objJSNUtils->getComponentInfo();
				$componentData 	= null;

				if(!is_null($componentInfo) && isset($componentInfo->manifest_cache) && $componentInfo->manifest_cache != '')
				{
					$componentData  = $objJSNJSON->decode($componentInfo->manifest_cache);
					$currentVersion = $componentData->version;
				}
				else
				{
					$currentVersion = trim(@$inforPackage['version']);;
				}
				$sampleData->getPackageVersion(trim(strtolower($inforPackage['realName'])));
				$path 		= $foldertmp.DS.$fileName;

				if (!JFile::exists($path))
				{
					$msg = JText::_('MAINTENANCE_SAMPLE_DATA_INSTALLATION_FILE_NOT_FOUND', true);
					echo json_encode(array('install' => false, 'message'=>$msg, 'redirect_link'=>$link));
					exit();
				}

				$unpackage = $sampleData->unpackPackage($fileName);

				if ($unpackage)
				{
					$dataInstall = $objReadXmlDetail->parserExtXmlDetailsSampleData($unpackage, $unpackage.DS.FILE_XML);
					$sampleData->deleteTempFolderISD($unpackage);
					if ($dataInstall && is_array($dataInstall))
					{
						if (trim(strtolower($currentVersion)) != trim(strtolower($dataInstall['imageshow']->version)))
						{
							$msg = stripslashes(JText::_('MAINTENANCE_SAMPLE_DATA_ERROR_IMAGESHOW_VERSION', true));
							echo json_encode(array('install' => false, 'message'=>$msg, 'redirect_link'=>$link));
							exit();
						}
						/*Check sources*/
						$sampleSources = explode(',', $dataInstall['imageshow']->sources);
						if (count($imageSources))
						{
							for ($z = 0, $countz=count($imageSources); $z < $countz; $z ++ )
							{
								$zrows 		= $imageSources[$z];

								if ($zrows->needInstall)
								{
									$uninstalledSources['source'.$zrows->identified_name]['name'] = $zrows->name;
									if (isset($zrows->authentication) && $zrows->authentication == true)
									{
										$uninstalledSources['source'.$zrows->identified_name]['commercial'] = true;
									}
									else
									{
										$uninstalledSources['source'.$zrows->identified_name]['commercial'] = false;
									}
								}
							}
						}

						if (count($uninstalledSources) && count($sampleSources))
						{
							foreach ($uninstalledSources as $key=>$uninstalledSource)
							{
								if (in_array($key, $sampleSources))
								{
									$elementID							= 'jsn-download-id-'.$key;
									$errors [] 							= '<div id="'.$elementID.'" class="jsn-sampledata-installation-processing jsn-sampledata-installation-wait"><span class="jsn-sampledata-installation-requried-elements">'.$uninstalledSource['name'].'</span><img src="components/com_imageshow/assets/images/ajax-loader-circle.gif"><span class="jsn-sampledata-install-status jsn-icon jsn-icon-check">&nbsp;</span><p class="jsn-sampledata-install-status-text"></p>';
									$elements[]							= $key;
									$objInfoUpdate 						= new stdClass();
									$objInfoUpdate->identify_name 		= str_replace('source', '', $key);
									$objInfoUpdate->edition 			= '';
									$objInfoUpdate->wait_text 			= JText::_('MAINTENANCE_SAMPLE_DATA_PROCESS_TEXT', true);
									$objInfoUpdate->process_text 		= JText::_('MAINTENANCE_SAMPLE_DATA_WAIT_TEXT', true);
									$objInfoUpdate->download_element_id	= $elementID;
									$objInfoUpdate 						= json_encode($objInfoUpdate);
									$sourceElements []					= $objInfoUpdate;
									if (!$commercial && $uninstalledSource['commercial'])
									{
										$commercial = true;
									}
								}
							}
						}

						/*Check sources*/
						/*Check themes*/
						$sampleThemes = explode(',', $dataInstall['imageshow']->themes);

						if (count($themes))
						{
							for ($q = 0, $countq=count($themes); $q < $countq; $q ++ )
							{
								$qrows 		= $themes[$q];
								if ($qrows->needInstall)
								{
									$uninstalledThemes[$qrows->identified_name]['name'] = $qrows->name;
									if (isset($qrows->authentication) && $qrows->authentication == true)
									{
										$uninstalledThemes[$qrows->identified_name]['commercial'] = true;
									}
									else
									{
										$uninstalledThemes[$qrows->identified_name]['commercial'] = false;
									}
								}
							}
						}

						if (count($uninstalledThemes) && count($sampleThemes))
						{
							foreach ($uninstalledThemes as $key=>$uninstalledTheme)
							{
								if (in_array($key, $sampleThemes))
								{
									$elementID							= 'jsn-download-id-'.$key;
									$errors [] 							= '<div id="'.$elementID.'" class="jsn-sampledata-installation-processing jsn-sampledata-installation-wait"><span class="jsn-sampledata-installation-requried-elements">'.$uninstalledTheme['name'].'</span><img src="components/com_imageshow/assets/images/ajax-loader-circle.gif"><span class="jsn-sampledata-install-status jsn-icon jsn-icon-check">&nbsp;</span><p class="jsn-sampledata-install-status-text"></p>';
									$objInfoUpdate 						= new stdClass();
									$elements[]							= $key;
									$objInfoUpdate->identify_name 		= $key;
									$objInfoUpdate->edition 			= '';
									$objInfoUpdate->wait_text 			= JText::_('MAINTENANCE_SAMPLE_DATA_PROCESS_TEXT', true);
									$objInfoUpdate->process_text 		= JText::_('MAINTENANCE_SAMPLE_DATA_WAIT_TEXT', true);
									$objInfoUpdate->download_element_id	= $elementID;
									$objInfoUpdate 						= json_encode($objInfoUpdate);
									$themeElements []					= $objInfoUpdate;
									if (!$commercial && $uninstalledTheme['commercial'])
									{
										$commercial = true;
									}
								}
							}
						}
						/*Check themes*/
						if (count($errors))
						{
							$allElements 		= implode(',', $elements);
							$msg 				= stripslashes(JText::_('MAINTENANCE_SAMPLE_DATA_FOLLOWING_ELEMENTS_ARE_NOT_INSTALLED', true));
							$objJSNLightCart 	= JSNISFactory::getObj('classes.jsn_is_lightcart');
							$lightCartErrorCode = $objJSNLightCart->getErrorCode('DEFAULT', false);
							echo json_encode(array('light_cart_error_code' => $lightCartErrorCode, 'install' => false, 'message' => $msg, 'redirect_link'=>$link, 'warnings'=>$errors, 'sources'=>$sourceElements, 'themes'=>$themeElements, 'elements'=>$allElements, 'total_elements'=>count($elements), 'commercial'=>$commercial));
							exit();
						}
						/*Check version theme*/
						if (count($objReadXmlDetail->_themeVersion))
						{
							$objJSNTheme 	= JSNISFactory::getObj('classes.jsn_is_showcasetheme');
							foreach ($objReadXmlDetail->_themeVersion as $key=>$value)
							{
								$themeInfo 		= $objJSNTheme->getThemeInfo($key);
								if ($themeInfo->version != $value)
								{
									$msg = stripslashes(JText::_('MAINTENANCE_SAMPLE_DATA_THEME_VERSION_ERROR', true));
									echo json_encode(array('install' => false, 'message'=>$msg, 'redirect_link'=>$link));
									exit();
								}
							}
						}
						/*Check version theme*/
						/*Check version source*/
						if (count($objReadXmlDetail->_sourceVersion))
						{
							$objJSNShowlistSource 	= JSNISFactory::getObj('classes.jsn_is_showlistsource');
							foreach ($objReadXmlDetail->_sourceVersion as $key=>$value)
							{
								$sourceInfo 		= $objJSNShowlistSource->getSourceInfo($key);
								if ($sourceInfo->version != $value)
								{
									$msg = stripslashes(JText::_('MAINTENANCE_SAMPLE_DATA_SOURCE_VERSION_ERROR', true));
									echo json_encode(array('install' => false, 'message'=>$msg, 'redirect_link'=>$link));
									exit();
								}
							}
						}
						/*Check version source*/
						if ($fileName != '')
						{
							$sampleData->deleteISDFile($fileName);
						}
						$sampleData->executeInstallSampleData($dataInstall);
						$msg = JText::_('MAINTENANCE_SAMPLE_DATA_INSTALL_SAMPLE_DATA_SUCCESSFULLY', true);
						echo json_encode(array('install' => true, 'message'=>$msg));
						exit();
					}
					else
					{
						$sampleData->returnError('false','');
					}
				}
				else
				{
					$msg = JText::_('MAINTENANCE_SAMPLE_DATA_UNPACK_SAMPLEDATA_FALSE', true);
					echo json_encode(array('install' => false, 'message'=>$msg, 'redirect_link'=>$link));
					exit();
				}
			}
			else
			{
				$msg = JText::sprintf('MAINTENANCE_SAMPLE_DATA_FOLDER_%S_MUST_HAVE_WRITABLE_PERMISSION', DS.'tmp');
				echo json_encode(array('install' => false, 'message'=>$msg, 'redirect_link'=>$link));
				exit();
			}
		}
	}

	function clearSessionRestoreResult()
	{
		$session = JFactory::getSession();
		$session->set('JSNISRestore', null);
		$link = 'index.php?option=com_imageshow&controller=maintenance&type=data&tab=1';
		$this->setRedirect($link);
	}

	function reRestoreDatabase()
	{
		$get 		  = JRequest::get('get');
		$compressType = 1;
		$filepath 	  = JPATH_ROOT.DS.'tmp';

		$config['path'] 	= $filepath;
		$config['file'] 	= array('name' => $get['backup_file']);
		$config['compress'] = $compressType;
		$config['file_upload'] = $filepath.DS.$get['backup_file'];

		$objJSNRestore 	= JSNISFactory::getObj('classes.jsn_is_restore');
		$result 		= $objJSNRestore->restoreBackupForMigrate($config);

		echo json_encode(array('success' => ($result) ? true : false , 'message' => ''));
		exit();
	}

	function installSampledataManually()
	{
		JRequest::checkToken() or jexit('Invalid Token');
		$sampleData 				= JSNISFactory::getObj('classes.jsn_is_sampledata');
		$objReadXmlDetail			= JSNISFactory::getObj('classes.jsn_is_readxmldetails');
		$task 						= JRequest::getWord('task', '', 'POST');
		$post 						= JRequest::get('post');
		$uploadIdentifier 			= md5('upload_sampledata_package');
		$packagenameIdentifier 		= md5('sampledata_package_name');
		$session 					=& JFactory::getSession();
		$session->set($uploadIdentifier, false, 'jsnimageshow');
		$session->set($packagenameIdentifier, '', 'jsnimageshow');
		if ($task == 'installSampledataManually')
		{
			if (!$post['agree_install_sample'])
			{
				$sampleData->returnError('false', JText::_('MAINTENANCE_SAMPLE_DATA_PLEASE_CHECK_I_AGREE_INSTALL_SAMPLE_DATA'));
			}

			$perm = $sampleData->checkFolderPermission();

			if ($perm)
			{
				$inforPackage 	= $objReadXmlDetail->parserXMLDetails();
				$sampleData->getPackageVersion(trim(strtolower($inforPackage['realName'])));

				$package 		= $sampleData->getPackageFromUpload();
				$unpackage 		= $sampleData->unpackPackage($package);

				if ($unpackage)
				{
					$dataInstall = $objReadXmlDetail->parserExtXmlDetailsSampleDataManually($unpackage.DS.FILE_XML);
					$sampleData->deleteTempFolderISD($unpackage);
					if (!$dataInstall && !is_array($dataInstall))
					{
						$sampleData->deleteISDFile($package);
						$sampleData->returnError('false','');
					}
					else
					{
						$session->set($uploadIdentifier, true, 'jsnimageshow');
						$session->set($packagenameIdentifier, $package, 'jsnimageshow');
						$sampleData->returnError('false','');
					}
				}
				else
				{
					$sampleData->deleteISDFile($package);
					$sampleData->returnError('false', JText::_('MAINTENANCE_SAMPLE_DATA_UNPACK_SAMPLEDATA_FALSE'));
				}
			}
			else
			{
				$sampleData->returnError('false', JText::sprintf('MAINTENANCE_SAMPLE_DATA_FOLDER_%S_MUST_HAVE_WRITABLE_PERMISSION', DS.'tmp'));
			}
		}
	}

	function executeInstallSampledataManually()
	{
		$objJSNSource	   					= JSNISFactory::getObj('classes.jsn_is_source');
		$objJSNTheme	   					= JSNISFactory::getObj('classes.jsn_is_themes');
		$sampleData 						= JSNISFactory::getObj('classes.jsn_is_sampledata');
		$objReadXmlDetail					= JSNISFactory::getObj('classes.jsn_is_readxmldetails');
		$objJSNJSON 						= JSNISFactory::getObj('classes.jsn_is_json');
		$objJSNUtils      					= JSNISFactory::getObj('classes.jsn_is_utils');
		$uploadIdentifier 					= md5('upload_sampledata_package');
		$packagenameIdentifier 				= md5('sampledata_package_name');
		$task 								= JRequest::getWord('task');
		$fileName							= JRequest::getVar('file_name');
		$link 								= 'index.php?option=com_imageshow&controller=maintenance&type=data&tab=0';
		$perm								= false;
		$foldertmp	 						= JPATH_ROOT.DS.'tmp';
		$session 							=& JFactory::getSession();
		if (is_writable($foldertmp))
		{
			$perm = true;
		}
		$imageSources			= $objJSNSource->compareLocalSources();
		$installedSources	  	= array();
		$sampleSources 	 		= array();
		$errors					= array();
		$themeElements			= array();
		$sourceElements			= array();
		$themes					= $objJSNTheme->compareLocalSources();
		$installedThemes	  	= array();
		$sampleThemes 	 		= array();
		$elements				= array();
		$commercial				= false;
		$requiredElements		= array();
		if ($task == 'executeInstallSampledataManually')
		{
			if ($perm)
			{
				$inforPackage 	= $objReadXmlDetail->parserXMLDetails();
				$componentInfo 	= $objJSNUtils->getComponentInfo();
				$componentData 	= null;

				if(!is_null($componentInfo) && isset($componentInfo->manifest_cache) && $componentInfo->manifest_cache != '')
				{
					$componentData  = $objJSNJSON->decode($componentInfo->manifest_cache);
					$currentVersion = $componentData->version;
				}
				else
				{
					$currentVersion = trim(@$inforPackage['version']);;
				}
				$sampleData->getPackageVersion(trim(strtolower($inforPackage['realName'])));
				$path 		= $foldertmp.DS.$fileName;

				if (!JFile::exists($path))
				{
					$msg = JText::_('MAINTENANCE_SAMPLE_DATA_INSTALLATION_FILE_NOT_FOUND', true);
					echo json_encode(array('install' => false, 'message'=>$msg, 'redirect_link'=>$link));
					exit();
				}

				$unpackage = $sampleData->unpackPackage($fileName);

				if ($unpackage)
				{
					$dataInstall = $objReadXmlDetail->parserExtXmlDetailsSampleData($unpackage, $unpackage.DS.FILE_XML);
					$sampleData->deleteTempFolderISD($unpackage);
					if ($dataInstall && is_array($dataInstall))
					{
						if (trim(strtolower($currentVersion)) != trim(strtolower($dataInstall['imageshow']->version)))
						{
							$msg = stripslashes(JText::_('MAINTENANCE_SAMPLE_DATA_ERROR_IMAGESHOW_VERSION', true));
							echo json_encode(array('install' => false, 'message'=>$msg, 'redirect_link'=>$link));
							exit();
						}
						/*Check sources*/
						$sampleSources = explode(',', $dataInstall['imageshow']->sources);


						if (count($imageSources))
						{
							for ($z = 0, $countz=count($imageSources); $z < $countz; $z ++ )
							{
								$zrows 		= $imageSources[$z];
								$installedSources['source'.$zrows->identified_name]['name'] = $zrows->name;
							}
						}
						if (count($installedSources) && count($sampleSources))
						{
							foreach ($sampleSources as $sampleSource)
							{

								if (!isset($installedSources[$sampleSource]))
								{
									$elements[]							= $sampleSource;
									$objInfoUpdate 						= new stdClass();
									$objInfoUpdate->identify_name 		= $sampleSource;
									$objInfoUpdate->name 				= ucwords(str_replace('source', 'source ', $sampleSource));
									$objInfoUpdate->text 				= JText::sprintf('MAINTENANCE_SAMPLE_DATA_%S_YOU_CAN_DOWNLOAD_VIA_CUSTOMER_AREA_PAGE', $objInfoUpdate->name, array('jsSafe'=>true, 'interpretBackSlashes'=>true, 'script'=>false));
									$objInfoUpdate->type 				= 'imagesource';
									$requiredElements []					= $objInfoUpdate;
								}
							}
						}
						else
						{
							foreach ($sampleSources as $sampleSource)
							{
								$elements[]							= $sampleSource;
								$objInfoUpdate 						= new stdClass();
								$objInfoUpdate->identify_name 		= $sampleTheme;
								$objInfoUpdate->name 				= ucwords(str_replace('source', 'source ', $sampleSource));
								$objInfoUpdate->text 				= JText::sprintf('MAINTENANCE_SAMPLE_DATA_%S_YOU_CAN_DOWNLOAD_VIA_CUSTOMER_AREA_PAGE', $objInfoUpdate->name, array('jsSafe'=>true, 'interpretBackSlashes'=>true, 'script'=>false));
								$objInfoUpdate->type 				= 'imagesource';
								$requiredElements []					= $objInfoUpdate;
							}
						}
						/*Check sources*/
						/*Check themes*/
						$sampleThemes = explode(',', $dataInstall['imageshow']->themes);

						if (count($themes))
						{
							for ($q = 0, $countq=count($themes); $q < $countq; $q ++ )
							{
								$qrows 		= $themes[$q];
								$installedThemes[$qrows->identified_name]['name'] = $qrows->name;
							}
						}

						if (count($installedThemes) && count($sampleThemes))
						{
							foreach ($sampleThemes as $sampleTheme)
							{
								if (!isset($installedThemes[$sampleTheme]))
								{
									$elements[]							= $sampleTheme;
									$objInfoUpdate 						= new stdClass();
									$objInfoUpdate->identify_name 		= $sampleTheme;
									$objInfoUpdate->name 				= ucwords(str_replace('theme', 'theme ', $sampleTheme));
									$objInfoUpdate->text 				= JText::sprintf('MAINTENANCE_SAMPLE_DATA_%S_YOU_CAN_DOWNLOAD_VIA_CUSTOMER_AREA_PAGE', $objInfoUpdate->name, array('jsSafe'=>true, 'interpretBackSlashes'=>true, 'script'=>false));
									$objInfoUpdate->type 				= 'theme';
									$requiredElements []				= $objInfoUpdate;
								}
							}
						}
						else
						{
							foreach ($sampleThemes as $sampleTheme)
							{
								$elements[]							= $sampleTheme;
								$objInfoUpdate 						= new stdClass();
								$objInfoUpdate->identify_name 		= $sampleTheme;
								$objInfoUpdate->name 				= ucwords(str_replace('theme', 'theme ', $sampleTheme));
								$objInfoUpdate->text 				= JText::sprintf('MAINTENANCE_SAMPLE_DATA_%S_YOU_CAN_DOWNLOAD_VIA_CUSTOMER_AREA_PAGE', $objInfoUpdate->name, array('jsSafe'=>true, 'interpretBackSlashes'=>true, 'script'=>false));
								$objInfoUpdate->type 				= 'theme';
								$requiredElements []				= $objInfoUpdate;
							}
						}


						/*Check themes*/
						if (count($requiredElements))
						{
							$allElements 		= implode(',', $elements);
							$msg 				= stripslashes(JText::_('MAINTENANCE_SAMPLE_DATA_FOLLOWING_ELEMENTS_ARE_NOT_INSTALLED', true));
							$objJSNLightCart 	= JSNISFactory::getObj('classes.jsn_is_lightcart');
							$lightCartErrorCode = $objJSNLightCart->getErrorCode('DEFAULT', false);
							echo json_encode(array('light_cart_error_code' => $lightCartErrorCode, 'install' => false, 'message' => $msg, 'redirect_link'=>$link, 'required_elements'=>$requiredElements, 'elements'=>$allElements, 'total_elements'=>count($elements), 'commercial'=>$commercial));
							exit();
						}
						/*Check version theme*/
						if (count($objReadXmlDetail->_themeVersion))
						{
							$objJSNTheme 	= JSNISFactory::getObj('classes.jsn_is_showcasetheme');
							foreach ($objReadXmlDetail->_themeVersion as $key=>$value)
							{
								$themeInfo 		= $objJSNTheme->getThemeInfo($key);
								if ($themeInfo->version != $value)
								{
									$msg = stripslashes(JText::_('MAINTENANCE_SAMPLE_DATA_THEME_VERSION_ERROR', true));
									echo json_encode(array('install' => false, 'message'=>$msg, 'redirect_link'=>$link));
									exit();
								}
							}
						}
						/*Check version source*/
						if (count($objReadXmlDetail->_sourceVersion))
						{
							$objJSNShowlistSource 	= JSNISFactory::getObj('classes.jsn_is_showlistsource');
							foreach ($objReadXmlDetail->_sourceVersion as $key=>$value)
							{
								$sourceInfo 		= $objJSNShowlistSource->getSourceInfo($key);
								if ($sourceInfo->version != $value)
								{
									$msg = stripslashes(JText::_('MAINTENANCE_SAMPLE_DATA_SOURCE_VERSION_ERROR', true));
									echo json_encode(array('install' => false, 'message'=>$msg, 'redirect_link'=>$link));
									exit();
								}
							}
						}
						/*Check version source*/
						/*Check version theme*/
						if ($fileName != '')
						{
							$sampleData->deleteISDFile($fileName);
						}
						$session->set($uploadIdentifier, false, 'jsnimageshow');
						$session->set($packagenameIdentifier, '', 'jsnimageshow');
						$sampleData->executeInstallSampleData($dataInstall);
						$msg = JText::_('MAINTENANCE_SAMPLE_DATA_INSTALL_SAMPLE_DATA_SUCCESSFULLY', true);
						echo json_encode(array('install' => true, 'message'=>$msg));
						exit();
					}
					else
					{
						$sampleData->returnError('false','');
					}
				}
				else
				{
					$msg = JText::_('MAINTENANCE_SAMPLE_DATA_UNPACK_SAMPLEDATA_FALSE', true);
					echo json_encode(array('install' => false, 'message'=>$msg, 'redirect_link'=>$link));
					exit();
				}
			}
			else
			{
				$msg = JText::sprintf('MAINTENANCE_SAMPLE_DATA_FOLDER_%S_MUST_HAVE_WRITABLE_PERMISSION', DS.'tmp');
				echo json_encode(array('install' => false, 'message'=>$msg, 'redirect_link'=>$link));
				exit();
			}
		}
	}

	function installRequiredPlugin()
	{
		JRequest::checkToken() or jexit('Invalid Token');
		$elementType = JRequest::getVar('element_type');
		$file = JRequest::getVar('pluign_file', null, 'files', 'array');

		if ($elementType == 'imagesource')
		{
			$objInstallSource = JSNISFactory::getObj('classes.jsn_is_installimagesource');
		}
		else
		{
			$objInstallSource = JSNISFactory::getObj('classes.jsn_is_installshowcasetheme');
		}
		$objInstallSource->installManual($file);
		$this->setRedirect('index.php?option=com_imageshow&controller=maintenance&type=data&tab=0&method_install_sample_data=manually');
	}

	/**
	 * Install required plugin when restore database, then run restore database
	 */
	function installJSNPluginForRestore()
	{
		JRequest::checkToken() or jexit('Invalid Token');
		$elementType = JRequest::getVar('element_type');
		$file = JRequest::getVar('pluign_file', null, 'files', 'array');

		if ($elementType == 'imagesource')
		{
			$objInstallSource = JSNISFactory::getObj('classes.jsn_is_installimagesource');
		}
		else
		{
			$objInstallSource = JSNISFactory::getObj('classes.jsn_is_installshowcasetheme');
		}

		$objInstallSource->installManual($file);

		// run restore
		$session 	   = JFactory::getSession();
		$restoreResult = $session->get('JSNISRestore');
		$backupFile = @$restoreResult['requiredInstallData']['backup_file'];

		JRequest::setVar( 'filedata', $backupFile, 'files', 'array' );
		$this->restore(true);
	}
}