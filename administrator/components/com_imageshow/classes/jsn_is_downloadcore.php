<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_downloadcore.php 10168 2011-12-09 11:08:16Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file');
include_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'classes'.DS.'jsn_is_downloadpackageauth.php');
class JSNISDownloadCore extends JSNISDownloadPackageAuth
{
	function setOptions($options = null)
	{
		if (isset($options->upgrade)) {
			$this->_getFields .= '&upgrade=' .urlencode($options->upgrade);
		}

		parent::setOptions($options);
	}
}