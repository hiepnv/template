<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_downloadpackage.php 11579 2012-03-07 04:21:07Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file');
class JSNISDownloadPackage
{
	var $_tmpPackageName 	= '';
	var $_downloadURL		= '';
	var $_tmpFolder			= '';

	function _getFilenameFromURL($url)
	{
		if (is_string($url))
		{
			$parts = explode('/', $url);
			return $parts[count($parts) - 1];
		}
		return false;
	}

	function _cURLCheckFunctions()
	{
		$objJSNUtil = JSNISFactory::getObj('classes.jsn_is_utils');

		if ($objJSNUtil->checkCURL()) {
			return true;
		} else {
			return false;
		}
	}

	function _fOPENCheck()
	{
		$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');

		if ($objJSNUtils->checkFOPEN()) {
			return true;
		} else {
			return false;
		}
	}

	function readFileZipToBinaryData($file)
	{
		$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
		$data = $objJSNUtils->readFileToString($file);
		return $data;
	}
}