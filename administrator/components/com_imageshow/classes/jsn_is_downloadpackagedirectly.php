<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_downloadpackagedirectly.php 10229 2011-12-14 04:17:40Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file');
include_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'classes'.DS.'jsn_is_downloadpackage.php');
class JSNJSDownloadPackageDirectly extends JSNISDownloadPackage
{
	var $_tmpPackagePath = '';

	function JSNJSDownloadPackageDirectly($downloadURL, $packageName = '')
	{
		if ($packageName != '')
		{
			$this->_tmpPackageName 	= $packageName;
		}
		else
		{
			$this->_tmpPackageName = $this->_getFilenameFromURL($downloadURL);
		}
		$this->_downloadURL		= $downloadURL;
		$this->_tmpFolder		= JPATH_ROOT.DS.'tmp'.DS;
		$this->_tmpPackagePath  = $this->_tmpFolder.$this->_tmpPackageName;
	}

	function download()
	{
		if (function_exists('fsockopen')) {
			return $this->fsocketopenDownload();
		} else if ($this->_cURLCheckFunctions()) {
			return $this->cURLdownload();
		} elseif ($this->_fOPENCheck()) {
			return $this->fOPENdownload();
		}

		return false;
	}

	function cURLdownload()
	{
		@set_time_limit(ini_get('max_execution_time'));
		$path = $this->_tmpFolder.$this->_tmpPackageName;
		$cp = curl_init($this->_downloadURL);
		$fp = fopen($path, "w");
		curl_setopt($cp, CURLOPT_FILE, $fp);
		curl_setopt($cp, CURLOPT_HEADER, 0);
		$result = curl_exec($cp);
		curl_close($cp);
		fclose($fp);
		if ($result)
		{
			return basename($path);
		}
		else
		{
			if (JFile::exists($path))
			{
				JFile::delete($path);
			}
			return $result;
		}
	}

	function fOPENdownload()
	{
		$target 	= false;
		$handle 	= @fopen($this->_downloadURL, 'r');
		$filename	= '';
		if (!$handle)
		{
			return false;
		}
		$metaData = stream_get_meta_data($handle);

		foreach ($metaData['wrapper_data'] as $wrapperData)
		{

			if (substr($wrapperData, 0, strlen("Content-Disposition")) == "Content-Disposition")
			{
				$fileName 	= explode ("\"", $wrapperData);
				$target 	= $fileName[1];
			}
		}
		if (!$target)
		{
			$filename = $this->_tmpPackageName;
		}
		else
		{
			$filename = basename($target);
		}
		$target = $this->_tmpFolder.$filename;
		$contents = null;

		while (!feof($handle))
		{
			$contents .= fread($handle, 8192);
			if ($contents === false)
			{
				return false;
			}
		}

		JFile::write($target, $contents);
		@set_time_limit(ini_get('max_execution_time'));
		fclose($handle);

		return basename($target);
	}

	function fsocketopenDownload()
	{
		$objJSNHTTP = JSNISFactory::getObj('classes.jsn_is_httprequest', null, $this->_downloadURL);
		$result = $objJSNHTTP->DownloadToString();

		if (!$result) return false;

		JFile::write($this->_tmpPackagePath, $result);
		@set_time_limit(ini_get('max_execution_time'));

		return basename($this->_tmpPackagePath);
	}
}