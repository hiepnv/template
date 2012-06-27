<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_language.php 12381 2012-05-02 07:23:13Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
class JSNISLanguage
{
	var $_lang = array();
	var $_adminPath;
	var $_sitePath;
	var $_sourcePath;
	var $_langFiles = array();

	function JSNISLanguage()
	{
		$this->setLang();
		$this->setPath();
		$this->setLangFile();
		$this->addLangFromJSNPlugin();
	}

	public static function getInstance()
	{
		static $instanceLang;

		if ($instancelang == null) {
			$instanceLang = new JSNISLanguage();
		}

		return $instanceLang;
	}

	function setLang()
	{
		$this->_lang = (array) json_decode(JSN_LIST_LANGUAGE_SUPPORTED);
	}

	function setPath()
	{
		$this->_path['source']['admin']	= array(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_imageshow'.DS.'languages'.DS.'admin');
		$this->_path['source']['site']	= array(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_imageshow'.DS.'languages'.DS.'site');
		$this->_path['admin'] 	= JPATH_ROOT.DS.'administrator'.DS.'language';
		$this->_path['site'] 	= JPATH_ROOT.DS.'language';
	}

	function addLangSourcePath($position, $path)
	{
		$this->_path['source'][$position][] = $path;
	}

	function addLangSourceFile($position, $type, $path)
	{
		$this->_langFiles[$position][$type][] = $path;
	}

	function setLangFile()
	{
		$this->setLangFileAdmin();
		$this->setLangFileSite();
	}

	function setLangFileAdmin()
	{
		$mod = array('mod_imageshow_quickicon.ini');

		$plugin = array(
			'plg_content_imageshow.ini',
			'plg_system_imageshow.ini'
		);

		$com = array(
			'com_imageshow.ini',
			'com_imageshow.sys.ini'
		);

		$files = array(
			'module' => $mod,
			'plugin' => $plugin,
			'component' => $com
		);

		$this->_langFiles['admin'] = $files;
	}

	function setLangFileSite()
	{
		$mod = array(
			'mod_imageshow.ini',
			'mod_imageshow.sys.ini'
		);

		$plugin = array(
			'plg_content_imageshow.ini'
		);

		$com = array(
			'com_imageshow.ini'
		);

		$files = array(
			'module' => $mod,
			'plugin' => $plugin,
			'component' => $com
		);

		$this->_langFiles['site'] = $files;
	}

	function getFolder($base)
	{
		$folders 		= JFolder::folders($base, '.', false, true);
		$arrayFolder 	= array();

		foreach ($folders as $folder)
		{
			if (basename($folder) != 'pdf_fonts') {
				$arrayFolder[basename($folder)] = basename($folder);
			}
		}

		return $arrayFolder;
	}

	function _getFolderByPostion($position)
	{
		$arrayFolders 	= $this->getFolder($this->_path[$position]);

		if (isset($arrayFolders['overrides'])) {
			unset($arrayFolders['overrides']);
		}

		$arrayMerge	= array_merge($arrayFolders, $this->_lang);
		$newArray	= array();

		foreach ($arrayMerge as $key => $value)
		{
			$newVal = $this->_checkAllInsLangs($value, $position);
			$newArray [$key] = $newVal;
		}

		ksort($newArray);
		return $newArray;
	}

	function megerArrayFolder()
	{
		$arrayMerge 	= array();

		$arrayFO = $this->_getFolderByPostion('site');
		$arrayBO = $this->_getFolderByPostion('admin');

		foreach ($arrayFO as $key=>$value) {
			$arrayMerge[$key]['site'] = $value;
		}

		foreach ($arrayBO as $key=>$value) {
			$arrayMerge[$key]['admin'] = $value;
		}

		ksort($arrayMerge);
		return $arrayMerge;
	}

	function _arrayDiffKey()
	{
		$arrs = func_get_args();
		$result = array_shift($arrs);

		foreach ($arrs as $array)
		{
			foreach ($result as $key => $v)
			{
				if (array_key_exists($key, $array))
				{
					unset($result[$key]);
				}
			}
		}
		return $result;
	}

	function _checkFolderExist($folder, $position)
	{
		if (!JFolder::exists($this->_path[$position].DS.$folder)) {
			return false;
		}

		return true;
	}

	function _checkFolderPermission($folder, $position)
	{
		if(is_writable($this->_path[$position].DS.$folder)) {
			return true;
		}

		return false;
	}

	function _checkFileExist($lang, $position, $type)
	{
		if ($this->_langFiles[$position][$type])
		{
			foreach ($this->_langFiles[$position][$type] as $langFile)
			{
				$file = $this->_path[$position].DS.$lang.DS.$lang.'.'.$langFile;

				if (!JFile::exists($file)) {
					return false;
				}
			}

			return true;
		}

		return false;
	}

	function _checkLangSupport($name)
	{
		if (array_key_exists($name, $this->_lang)) {
			return true;
		}

		return false;
	}

	function _checkAllInsLangs($value, $postion)
	{
		$checkFolderExist 		= $this->_checkFolderExist($value, $postion);
		$checkFolderPermission 	= $this->_checkFolderPermission($value, $postion);
		$checkComponentFileExist = $this->_checkFileExist($value, $postion, 'component');
		$checkPluginFileExist	 = $this->_checkFileExist($value, $postion, 'plugin');
		$checkModuleFileExist	 = $this->_checkFileExist($value, $postion, 'module');

		if ($checkFolderExist == false) {
			$newVal = 1; // folder not exist
		} elseif ($checkFolderPermission == false) {
			$newVal = 2; // not writable
		} elseif ($checkComponentFileExist == true && $checkModuleFileExist == true && $checkPluginFileExist == true) {
			$newVal = 3; // existed
		} elseif ($this->_checkLangSupport($value) == false) {
			$newVal = 4; // not support;
		} else {
			$newVal = 5; // file not exist;
		}

		return $newVal;
	}

	function installationFolderLangBO($arrayFolder)
	{
		foreach ($arrayFolder as $value)
		{
			$this->_copyLangFile('admin', $value, 'component');
			$this->_copyLangFile('admin', $value, 'module');
			$this->_copyLangFile('admin', $value, 'plugin');
		}

		return true;
	}

	function installationFolderLangFO($arrayFolder)
	{
		foreach ($arrayFolder as $value)
		{
			$this->_copyLangFile('site', $value, 'component');
			$this->_copyLangFile('site', $value, 'module');
			$this->_copyLangFile('site', $value, 'plugin');
		}

		return true;
	}

	function _copyLangFile($position, $lang, $type)
	{
		if (isset($this->_langFiles[$position][$type]))
		{
			foreach ($this->_langFiles[$position][$type] as $langFile)
			{
				$dest = $this->_path[$position].DS.$lang.DS.$lang.'.'.$langFile;

				foreach ($this->_path['source'][$position] as $sourcePath)
				{
					$file = $sourcePath.DS.$lang.'.'.$langFile;

					if (JFile::exists($file)) {
						JFile::copy($file, $dest);
					}
				}
			}
		}
	}

	function loadLanguageFlex()
	{
		$objLanguage = JFactory::getLanguage();
		$language    = $objLanguage->getTag();
		$filepath 	 = JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_imageshow'.DS.'languages'.DS.'admin'.DS.'flex'.DS.$language.'.flex.ini';

		if (JFile::exists($filepath) == false)
		{
			$filepath = JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_imageshow'.DS.'languages'.DS.'admin'.DS.'flex'.DS.'en-GB.flex.ini';

			if (JFile::exists($filepath) == false) {
				return false;
			}
		}

		$data = array();

		if (false === $fhandle = fopen($filepath, 'r'))
		{
			JError::raiseWarning(21, 'JFile::read: '.JText::_('Unable to open file') . ": '$filepath'");
			return false;
		}

		clearstatcache();
		$fsize = filesize($filepath);

		if ($fhandle)
		{
		    while (($line = fgets($fhandle, $fsize)) !== false)
		    {
		    	$lineLength  = strlen($line);
				$post 		 = strpos($line, '=');
				$leftString  = substr($line, 0, $post);
				$rightString = substr($line, $post+1, $lineLength);

				if ($leftString != '' && $rightString != '') {
		    		$data[trim($leftString)] = trim($rightString);
		    	}
		    }

		    fclose($fhandle);
		}

		return $data;
	}

	function getFilterLangSystem()
	{
		$app 			= JFactory::getApplication();
		$router 		= $app->getRouter();
		$modeSef 		= ($router->getMode() == JROUTER_MODE_SEF) ? true : false;
		$languageFilter = $app->getLanguageFilter();
		$uri 			= JFactory::getURI();
		$langCode		= JLanguageHelper::getLanguages('lang_code');
		$langDefault	= JComponentHelper::getParams('com_languages')->get('site', 'en-GB');

		$realPath = 'index.php?';

		if ($languageFilter)
		{
			if (isset($langCode[$langDefault]))
			{
				if ($modeSef)
				{
					$realPath = '';
					$realPath .= JFactory::getConfig()->get('sef_rewrite') ? '' : 'index.php/';
					$realPath .= $langCode[$langDefault]->sef.'/?';
				}
				else
				{
					$realPath = 'index.php?lang='.$uri->getVar('lang').'%26';
				}
			}
		}

		return $realPath;
	}

	function addLangFromJSNPlugin()
	{
		//$objJSNPlugin 	= JSNISFactory::getObj('classes.jsn_is_plugins');
		//$listPlugin   	= $objJSNPlugin->getJSNPluginList();

		//foreach ($listPlugin as $plugin) {
			JPluginHelper::importPlugin('jsnimageshow');
		//}

		$dispatcher = JDispatcher::getInstance();
		$plugins 	= $dispatcher->trigger('getLanguageJSNPlugin');

		foreach ($plugins as $plugin)
		{
			foreach ($plugin as $position => $language)
			{
				$this->_langFiles[$position]['plugin'] = array_merge($this->_langFiles[$position]['plugin'], $language['files']);
				$this->_path['source'][$position] = array_merge($this->_path['source'][$position], $language['path']);
			}
		}
	}
}