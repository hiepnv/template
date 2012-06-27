<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_downloadjsnplugin.php 10481 2011-12-26 02:06:01Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file');
include_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'classes'.DS.'jsn_is_downloadpackageauth.php');
class JSNISDownloadJsnPlugin extends JSNISDownloadPackageAuth
{
	function setOptions($options = null)
	{
		$this->_getFields .= '&based_identified_name=imageshow&upgrade=yes';

		parent::setOptions($options);
	}
}