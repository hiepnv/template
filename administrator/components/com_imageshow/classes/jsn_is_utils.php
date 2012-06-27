<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_utils.php 12008 2012-04-03 11:24:16Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );

class JSNISUtils
{
	var $_db = null;

	function JSNISUtils()
	{
		if ($this->_db == null)
		{
			$this->_db = JFactory::getDBO();
		}
	}

	public static function getInstance()
	{
		static $instanceUtils;
		if ($instanceUtils == null)
		{
			$instanceUtils = new JSNISUtils();
		}
		return $instanceUtils;
	}

	function getParametersConfig()
	{
		$query = $this->_db->getQuery(true);
 		$query->select('*');
 		$query->from('#__imageshow_parameters');
 		$this->_db->setQuery($query);
		return $this->_db->LoadObject();
	}

	function overrideURL()
	{
		$pathURL 			= array();
		$uri				= JURI::getInstance();
		$pathURL['prefix'] 	= $uri->toString( array('scheme', 'host', 'port'));

		if (strpos(php_sapi_name(), 'cgi') !== false && !ini_get('cgi.fix_pathinfo') && !empty($_SERVER['REQUEST_URI']))
		{
			$pathURL['path'] =  rtrim(dirname(str_replace(array('"', '<', '>', "'"), '', $_SERVER["PHP_SELF"])), '/\\');
		}
		else
		{
			$pathURL['path'] =  rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
		}

		return $pathURL['prefix'].$pathURL['path'].'/';
	}

	function checkSupportLang()
	{
		$objLanguage 			= JFactory::getLanguage();
		$language           	= $objLanguage->getTag();
		$supportLang 			= (array) json_decode(JSN_LIST_LANGUAGE_SUPPORTED);
		if (@in_array($language, $supportLang))
		{
    		return true;
		}
		return false;
	}

	function getAlterContent()
	{
		$script = "\n<script type='text/javascript'>\n";
		$script .= "window.addEvent('domready', function(){
						JSNISImageShow.alternativeContent();
					});";
		$script .= "\n</script>\n";
		return $script;
	}


	/*
	 *  encode url with special character
	 *
	 */
	function encodeUrl($url, $replaceSpace = false)
	{
		$encodeStatus = $this->encodeStatus($url);

		if ($encodeStatus == false)
		{
			$url = rawurlencode($url);
		}

		$url = str_replace('%3B', ";", $url);
	    $url = str_replace('%2F', "/", $url);
	    $url = str_replace('%3F', "?", $url);
	    $url = str_replace('%3A', ":", $url);
	    $url = str_replace('%40', "@", $url);
	    $url = str_replace('%26', "&", $url);
	    $url = str_replace('%3D', "=", $url);
	    $url = str_replace('%2B', '+', $url);
	    $url = str_replace('%24', "$", $url);
	    $url = str_replace('%2C', ",", $url);
	    $url = str_replace('%23', "#", $url);
	    $url = str_replace('%2D', "-", $url);
	    $url = str_replace('%5F', "_", $url);
	    $url = str_replace('%2E', ".", $url);
	    $url = str_replace('%21', "!", $url);
	    $url = str_replace('%7E', "~", $url);
	    $url = str_replace('%2A', "*", $url);
	    $url = str_replace('%27', "'", $url);
	    $url = str_replace('%22', "\"", $url);
	    $url = str_replace('%28', "(", $url);
	    $url = str_replace('%29', ")", $url);
		$url = str_replace('%5D', "]", $url);
	    $url = str_replace('%5B', "[", $url);

	    if ($replaceSpace == true)
	    {
	    	$url = str_replace('%20', " ", $url);
	    }
	    return $url;
	}

	/*
	 * encode array url
	 *
	 */
	function encodeArrayUrl($urls, $replaceSpace = false)
	{
		$arrayUrl =  array();
		foreach ($urls as $key => $value )
		{
			$url = $this->encodeUrl($value, $replaceSpace);
			$arrayUrl[$key] = $url;
		}

		return $arrayUrl;
	}

	//decode url that was encoded by encodeUrl()
	function decodeUrl($url)
	{
		$url = rawurldecode($url);
		return $url;
	}

	// check string was encoded
	function encodeStatus($string)
	{
		$regexp  = "/%+[A-F0-9]{2}/";
		if (preg_match($regexp,$string))
		{
			return true;
		}
		return false;
	}

	function getIDComponent()
	{
		$query = $this->_db->getQuery(true);
 		$query->select('id');
 		$query->from('#__extensions');
 		$query->where('element=\'com_imageshow\'');
 		$this->_db->setQuery($query);
		$result = $this->_db->loadAssoc();
		return $result;

	}

	function insertMenuSample($menuType)
	{
		$comID 	= $this->getIDComponent();
		$query 	= "INSERT INTO
						#__menu
						(menutype, name, alias, link, type, published, parent, componentid, sublevel, ordering, checked_out, checked_out_time, pollid, browserNav, access, utaccess, params, lft, rgt, home)
				   VALUES
				  		('".$menuType."', 'JSN ImageShow', 'imageshow', 'index.php?option=com_imageshow&view=show', 'component', '1', '0', '".$comID['id']."', '0', '0', '0', '0000-00-00 00:00:00', '0', '0', '0', '0', 'showlist_id=1\nshowcase_id=1', '0', '0', '0')";
		$this->_db->setQuery($query);
		$this->_db->query();
	}

	function checkComInstalled($comName)
	{
		$query = $this->_db->getQuery(true);
 		$query->select('COUNT(*)');
 		$query->from('#__extensions');
 		$query->where('element=\''.$comName.'\'');
 		$this->_db->setQuery($query);
		$result = $this->_db->loadRow();
		if ($result[0] > 0) {
			return true;
		}
		return false;

	}

	function checkIntallModule()
	{
		$query = $this->_db->getQuery(true);
 		$query->select('COUNT(*)');
 		$query->from('#__extensions');
 		$query->where('element=\'mod_imageshow\' AND type=\'module\'');
 		$this->_db->setQuery($query);
		$result = $this->_db->loadRow();
		if ($result[0] > 0) {
			return true;
		}
		return false;
	}

	function checkIntallPluginContent()
	{
		$query = $this->_db->getQuery(true);
 		$query->select('COUNT(*)');
 		$query->from('#__extensions');
 		$query->where('element=\'imageshow\' AND type=\'plugin\' AND folder=\'content\'');
 		$this->_db->setQuery($query);
		$result = $this->_db->loadRow();
		if ($result[0] > 0) {
			return true;
		}
		return false;
	}

	function checkIntallPluginSystem()
	{
		$query = $this->_db->getQuery(true);
 		$query->select('COUNT(*)');
 		$query->from('#__extensions');
 		$query->where('element=\'imageshow\' AND type=\'plugin\' AND folder=\'system\'');
 		$this->_db->setQuery($query);
		$result = $this->_db->loadRow();
		if ($result[0] > 0) {
			return true;
		}
		return false;

	}

	function getPluginContentInfo()
	{
		$query = $this->_db->getQuery(true);
 		$query->select('*');
 		$query->from('#__extensions');
 		$query->where('element=\'imageshow\' AND type=\'plugin\' AND folder=\'content\'');
 		$this->_db->setQuery($query);
		$result = $this->_db->loadObject();
		return $result;

	}

	function getModuleInfo()
	{
		$query = $this->_db->getQuery(true);
 		$query->select('*');
 		$query->from('#__extensions');
 		$query->where('element=\'mod_imageshow\' AND type=\'module\'');
 		$this->_db->setQuery($query);
		$result = $this->_db->loadObject();
		return $result;
	}

	function getComponentInfo()
	{
		$query = $this->_db->getQuery(true);
 		$query->select('*');
 		$query->from('#__extensions');
 		$query->where('element=\'com_imageshow\' AND type=\'component\'');
 		$this->_db->setQuery($query);
		$result = $this->_db->loadObject();
		return $result;

	}

	function clearData()
	{
		$queries [] = 'TRUNCATE TABLE #__imageshow_configuration';
		$queries [] = 'TRUNCATE TABLE #__imageshow_showlist';
		$queries [] = 'TRUNCATE TABLE #__imageshow_showcase';
		$queries [] = 'TRUNCATE TABLE #__imageshow_images';

		foreach ($queries as $query)
		{
			$query = trim($query);
			if ($query != '') {
				$this->_db->setQuery($query);
				$this->_db->query();
			}
		}
		return true;
	}

//	function getTotalProfile()
//	{
//		$query 	= 'SELECT COUNT(*) FROM #__imageshow_configuration WHERE source_type <> 1';
//		$this->_db->setQuery($query);
//		return $this->_db->loadRow();
//	}

	function getImageInPath($path = null)
	{
		jimport( 'joomla.filesystem.file' );

		if ($path == null ) return false;

		$arrayImage = array();

		if (!JFolder::exists($path))
		{
			return false;
		}

		$dir = @opendir($path);


		$data 				= new stdClass();
		$arrayImage 		= array();

		while (false !== ($file = @readdir($dir)))
		{
			if (JFile::exists($path.DS.$file))
			{
				$fileInfo = pathinfo($path.DS.$file);

				if (preg_match('(png|jpg|jpeg|gif)',strtolower($fileInfo['extension']))) {
					$arrayImage[] = str_replace(DS, '/', $path.DS.$file);
				}
			}
        }

		$data->images		    = $arrayImage;
		natcasesort($arrayImage);
		$data->images		    = $arrayImage;
      	return $data;
    }

	function checkValueArray($arrayList, $index)
	{
		if (!array_key_exists($index, $arrayList)) {
   			return false;
		}

		if ($arrayList[$index] != '') {
			return $arrayList[$index];
		} else {
			$index = $index - 1;
			return $this->checkValueArray($arrayList,$index);
		}
	}

	function wordLimiter($str, $limit = 100, $end_char = '&#8230;')
	{
		if (trim($str) == '')
			return $str;
		preg_match('/\s*(?:\S*\s*){'. (int) $limit .'}/', $str, $matches);
		if (strlen($matches[0]) == strlen($str))
			$end_char = '';
		return rtrim($matches[0]).$end_char;
	}

	function checkTmpFolderWritable()
	{
		$foldername = 'tmp';
		$folderpath = JPATH_ROOT.DS.$foldername;

		if (is_writable($folderpath) == false)
		{
			JError::raiseWarning(100, JText::sprintf('Folder "%s" is Unwritable. Please set Writable permission (CHMOD 777) for it before performing maintenance operations', DS.$foldername));
		}
		return true;
	}

	function renderMenuComboBox($ID, $elementText, $elementName, $parameters = '')
	{
		$query = $this->_db->getQuery(true);
 		$query->select('menutype AS value, title AS text');
 		$query->from('#__menu_types');
 		$this->_db->setQuery($query);
		$data = $this->_db->loadObjectList();

		array_unshift($data, JHTML::_('select.option', '', '- '.JText::_($elementText).' -', 'value', 'text'));
		return JHTML::_('select.genericlist', $data, $elementName, $parameters, 'value', 'text', $ID);
	}

	function randSTR($length = 32, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890')
	{
	    $charsLength 	= (strlen($chars) - 1);
	    $string 		= $chars{rand(0, $charsLength)};

	    for ($i = 1; $i < $length; $i = strlen($string))
	    {
	        $r = $chars{rand(0, $charsLength)};
	        if ($r != $string{$i - 1}) $string .=  $r;
	    }

	    return $string;
	}

	function getEdition()
	{
		$coreData 	  			= $this->getComponentInfo();
		$coreInfo				= json_decode($coreData->manifest_cache);
		$description			= $coreInfo->description;
		$tmpDescription			= explode(' ', $description);
		$edition				= @$tmpDescription[2].' '.@$tmpDescription[3];
		return trim(strtolower($edition));
	}

	function getShortEdition()
	{
		$arrayStr = explode(' ', $this->getEdition());

		if (count($arrayStr) > 0)
		{
			return $arrayStr[0];
		}

		return null;
	}

	function callJSNButtonMenu()
	{
		jimport('joomla.html.toolbar');
		$path = JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers';
		$toolbar = JToolBar::getInstance('toolbar');
		$toolbar->addButtonPath($path);
		$toolbar->appendButton('JSNMenuButton');
	}

	function checkLimit()
	{
		$edition = $this->getShortEdition();

		if ($edition == 'pro')
		{
			return false;
		}

		return true;
	}

	/*function checkVersion()
	{
		$jsnProductInfo = 'http://www.joomlashine.com/joomla-extensions/jsn-imageshow-version-check.html';
		$objJSNHTTP   	= JSNISFactory::getObj('classes.jsn_is_httprequest', null, $jsnProductInfo);
		$result   		= $objJSNHTTP->DownloadToString();

		if (!$result) {
			return false;
		} else {
			$stringExplode = explode("\n", $result);
			return @$stringExplode[2];
		}
	}*/

	function getModuleInformation($moduleName)
	{
		$query = $this->_db->getQuery(true);
 		$query->select('*');
 		$query->from('#__modules');
 		$query->where('module='. $this->_db->Quote($moduleName, false));
 		$this->_db->setQuery($query);
		$result = $this->_db->loadObject();
		return $result;
	}

	function approveModule($moduleName, $publish = 1)
	{
		$query = $this->_db->getQuery(true);
		$query->update('#__modules');
		$query->set('published = '.(int) $publish);
		$query->where('module = '.$this->_db->Quote($moduleName, false));
		$this->_db->setQuery($query);
		if (!$this->_db->query())
		{
			return false;
		}

		return true;
	}


	function getJoomlaLevelName()
	{
		$query = $this->_db->getQuery(true);
 		$query->select('*');
 		$query->from('#__viewlevels');
 		$this->_db->setQuery($query);
		$items = $this->_db->loadObjectlist();

		$count  = count($items);
		$result = array();
		if($count)
		{
			for($i = 0; $i < $count; $i++)
			{
				$item = $items[$i];
				$result[$item->id] = strtolower($item->title);
			}
		}
		return $result;
	}

	function convertJoomlaLevelFromIDToName($data, $id)
	{
		$count = count($data);
		if($count)
		{
			if(!$id) $id = 1;
			return $data[$id];
		}
		return $id;
	}

	function convertJoomlaLevelFromNameToID($data, $name)
	{
		$count   = count($data);
		$default = '';
		$index   = 0;
		if ($count)
		{
			foreach ($data as $key => $value)
			{
				if (!$index)
				{
					$default = $key;
					$index   = 1;
				}
				if ($name == $value)
				{
					return $key;
				}
			}
			return $default;
		}
		return '';
	}

	function displayShowcaseMissingMessage()
	{
		$string = '<div class="jsn-missing-data-alert-box">';
		$string .= '<div class="header"><span class="icon-warning"></span><span class="message">'.JText::_('SITE_SHOWCASE_DATA_IS_NOT_CONFIGURED').'</span></div>';
		$string .= '<div class="footer"><span class="link-to-more">'.JText::_('SITE_SHOWCASE_CLICK_TO_LEARN_MORE').'</span></div></div>';
		return $string;
	}

	function displayShowlistMissingMessage()
	{
		$string = '<div class="jsn-missing-data-alert-box">';
		$string .= '<div class="header"><span class="icon-warning"></span><span class="message">'.JText::_('SITE_SHOWLIST_DATA_IS_NOT_CONFIGURED').'</span></div>';
		$string .= '<div class="footer"><span class="link-to-more">'.JText::_('SITE_SHOWLIST_CLICK_TO_LEARN_MORE').'</span></div></div>';
		return $string;
	}

	function displayShowlistNoImages()
	{
		$string = '<div class="jsn-missing-data-alert-box no-image">';
		$string .= '<div class="header"><span class="icon-warning"></span><span class="message">'.JText::_('SITE_SHOWLIST_NO_IMAGE').'</span></div>';
		$string .= '</div>';
		return $string;
	}

	function displayThemeMissingMessage()
	{
		$string = '<div class="jsn-missing-data-alert-box">';
		$string .= '<div class="header"><span class="icon-warning"></span><span class="message">'.JText::_('SITE_THEME_DATA_IS_NOT_CONFIGURED').'</span></div>';
		$string .= '<div class="footer"><span class="link-to-more">'.JText::_('SITE_THEME_CLICK_TO_LEARN_MORE').'</span></div></div>';
		return $string;
	}

	function renderListItems($arrayItems, $type = "showlist")
	{
		$itemID 		 = $type.'_id';
		$itemTitle 		 = $type.'_title';
		$showlistAddText = JText::_('JSN_MENU_CREATE_NEW_SHOWLIST');
		$showcaseAddText = JText::_('JSN_MENU_CREATE_NEW_SHOWCASE');

		$html 		= '';
		$html = '<ul class="jsn-list-items">';

		if (count($arrayItems) > 0)
		{
			foreach ($arrayItems as $item):
				$html .= '<li class="jsn-list-item"><a href="index.php?option=com_imageshow&controller='.$type.'&task=edit&cid[]='.$item->$itemID.'">
						  	'.htmlspecialchars($item->$itemTitle).'
						  </a></li>';
			endforeach;
			$html .= '<li class="separator"></li>';
		}

		$html .= '<li><a class="additem icon-16" href="index.php?option=com_imageshow&controller='.$type.'&task=add" title="'.htmlspecialchars(${$type.'AddText'}).'"><span>'.${$type.'AddText'}.'</span></a></li>';
		$html .= '</ul>';

		return $html;
	}

	function getExtensionInfoByID($id)
	{
		$query = $this->_db->getQuery(true);
 		$query->select('*');
 		$query->from('#__extensions');
 		$query->where('extension_id='.(int) $id);
 		$this->_db->setQuery($query);
		$result = $this->_db->loadObject();
		return $result;
	}

	function getRemoteElementInfor($name, $edition)
	{
		$objJSNUtil     = JSNISFactory::getObj('classes.jsn_is_utils');

		$link		 	= JSN_IMAGESHOW_INFO_URL.'&identified_name='.urlencode($name).'&edition='.urlencode($edition);
		$objJSNHTTP   	= JSNISFactory::getObj('classes.jsn_is_httprequest', null, $link);
		$objJSNJSON     = JSNISFactory::getObj('classes.jsn_is_json');
		$result    		= $objJSNHTTP->DownloadToString();
		$data			= array();
		if (!$result)
		{
			$data = array('connection' => false, 'version' => '', 'commercial'=>'', 'description'=>'', 'url'=>'');
		}
		else
		{
			$parse = $objJSNJSON->decode($result);
			$data  = array('connection' => true, 'version' => @$parse->version, 'commercial' => @$parse->authentication, 'description'=>@$parse->description, 'url'=>@$parse->url);
		}

		return $data;
	}

	function getCoreInfo()
	{
		$data				= new stdClass();
		$objJSNJSON     	= JSNISFactory::getObj('classes.jsn_is_json');
		$coreData 	  		= $this->getComponentInfo();
		$coreInfo			= json_decode($coreData->manifest_cache);
		$description		= $coreInfo->description;
		$tmpDescription		= explode(' ', $description);
		$edition			= @$tmpDescription[2].' '.@$tmpDescription[3];
		$data->version 	 	= trim($coreInfo->version);
		$data->edition		= strtolower(trim($edition));
		$data->name 		= $coreInfo->name;
		$data->id 			= strtolower($coreInfo->name);

		$remoteInfo = $this->getRemoteElementInfor($data->name, $data->edition);

		if (version_compare($data->version, $remoteInfo['version']) >= 0) {
			$data->needUpdate = false;
		} else {
			$data->needUpdate = true;
		}
		$data->commercial = $remoteInfo['commercial'];

		return $data;
	}

	function parseVersionString ($str)
	{
		return explode('.', $str);
	}

	function compareVersion($runningVersionParam, $latestVersionParam)
	{
		$check	= false;
		$runningVersion 		= $this->parseVersionString($runningVersionParam);
		$countRunningVersion 	= count($runningVersion);
		$latestVersion 			= $this->parseVersionString($latestVersionParam);
		$countLatestVersion 	= count($latestVersion);
		$count 					= 0;
		if	($countRunningVersion > $countLatestVersion)
		{
			$count = $latestVersion;
		}
		else
		{
			$count = $countRunningVersion;
		}

		$minIndex = $count - 1;

		for($i = 0; $i < $count; $i++)
		{
			if ($runningVersion[$i] < $latestVersion[$i])
			{
				$check = true;
				break;
			}
			elseif($runningVersion[$i] == $latestVersion[$i] && $i == $minIndex && $countRunningVersion < $countLatestVersion)
			{
				$check = true;
				break;
			}
			elseif($runningVersion[$i] == $latestVersion[$i])
			{
				continue;
			}
			else
			{
				break;
			}
		}

		return $check;
	}

	function runSQLFile($file)
	{
		jimport('joomla.filesystem.file');

		if (JFile::exists($file))
		{
			$buffer = $this->readFileToString($file);

			if ($buffer === false) {
				return false;
			}

			jimport('joomla.installer.helper');
			$queries = JInstallerHelper::splitSql($buffer);

			if (count($queries) == 0)
			{
				JError::raiseWarning(100, $sqlFile . JText::_(' not exits'));
				return 0;
			}

			foreach ($queries as $query)
			{
				$query = trim($query);
				if ($query != '' && $query{0} != '#')
				{
					$this->_db->setQuery($query);

					if (!$this->_db->query())
					{
						JError::raiseWarning(100, 'JInstaller::install: '.JText::_('SQL Error')." ".$this->_db->stderr(true));
						return false;
					}
				}
			}

			return true;
		}
		else
		{
			JError::raiseWarning(100, $file . JText::_(' not exits'));
			return false;
		}
	}

	function checkSupportedFlashPlayer()
	{
		$userAgent	= $_SERVER['HTTP_USER_AGENT'];
		$deviceName = '';
		switch(true)
		{
			case (preg_match('/ipod/i', $userAgent)):
				$deviceName = 'ipod';
			break;
			case (preg_match('/iphone/i', $userAgent)):
				$deviceName = 'iphone';
			break;
			case (preg_match('/ipad/i', $userAgent)):
				$deviceName = 'ipad';
			break;
			case (preg_match('/android/i', $userAgent)):
				$deviceName = 'android';
			break;
		}
		return $deviceName;
	}

	function downloadArchiveFile($fileName, $type = 'zip', $basePath = '')
	{
		jimport('joomla.filesystem.file');
		if ($basePath == '') {
			$basePath = JPATH_ROOT.DS.'tmp';
		}
		$filePath 	= $basePath.DS.$fileName;
		$fileSize 	= filesize($filePath);

		switch ($type)
		{
			case "zip":
				header("Content-Type: application/zip");
				break;
			case "bzip":
				header("Content-Type: application/x-bzip2");
				break;
			case "gzip":
				header("Content-Type: application/x-gzip");
				break;
			case "tar":
				header("Content-Type: application/x-tar");
		}
		$header = "Content-Disposition: attachment; filename=\"";
		$header .= $fileName;
		$header .= "\"";
		header($header);
		header('Content-Description: File Transfer');
		header("Content-Length: " . $fileSize);
		header("Content-Transfer-Encoding: binary");
		header("Cache-Control: no-cache, must-revalidate, max-age=60");
		header("Expires: Sat, 01 Jan 2000 12:00:00 GMT");
		ob_clean();
   	 	flush();
		@readfile($filePath);
	}

	/**
	 * check support CURL
	 * @return true/false
	 */
	function checkCURL()
	{
		 if (!function_exists("curl_init") &&
		 		!function_exists("curl_setopt") &&
		 		!function_exists("curl_exec") &&
		 		!function_exists("curl_close")) {
		 			return false;
		 };

	  	 return true;
	}

	/**
	 * check accessing URL when use fopen
	 * @return true/false
	 */
	function checkFOPEN() {
		return (boolean) ini_get('allow_url_fopen');
	}

	/**
	 * convert a file to read string
	 * @param file to read
	 * @return return read string or false on failure
	 */
	function readFileToString($file)
	{
		if (!JFile::exists($file)) return false;

		$file = @fopen($file, 'r');

		$contents = '';

		while (!feof($file))
		{
			$contents .= fread($file, 8192);

			if ($contents === false) {
				return false;
			}
		}

		fclose($file);

		return $contents;
	}

	/**
	 * check necessary ext for download
	 * @return true/false
	 */
	function checkEnvironmentDownload()
	{
		if ($this->checkCURL() || $this->checkFOPEN() || function_exists('fsockopen')) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * check necessary ext for install package
	 * @return true/false
	 */
	function checkEnvironmentInstall()
	{
		if (!(bool) ini_get('file_uploads')) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('WARNINSTALLFILE'));
			return false;
		}

		if (!extension_loaded('zlib')) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('WARNINSTALLZLIB'));
			return false;
		}
	}
}
?>