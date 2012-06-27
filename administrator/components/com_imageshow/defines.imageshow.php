<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: defines.imageshow.php 12469 2012-05-07 09:30:31Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
define('JSN_IMAGESHOW_ADMIN_PATH', JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow');
define('JSN_IS_PATH_JSN_PLUGIN', JPATH_PLUGINS.DS.'jsnimageshow');
define('JSN_IMAGESHOW_AUTOUPDATE_URL', 'http://www.joomlashine.com/index.php?option=com_lightcart&controller=remoterequestauthentication&task=authenticate&tmpl=component');
define('JSN_IMAGESHOW_INFO_URL', 'http://www.joomlashine.com/index.php?option=com_lightcart&controller=productversioninfo&task=getinfo&tmpl=component');
define('JSN_IS_CUSTOMER_AREA', 'http://www.joomlashine.com/index.php?option=com_lightcart&view=customerarea');

// IMAGESHOW GLOBAL PATH
define('JSN_IMAGESHOW_FILE_URL', 'http://www.joomlashine.com/joomla-extensions/jsn-imageshow-sample-data-j25.zip');
define('CATEGORY_INFO_URL', 'http://www.joomlashine.com/index.php?option=com_lightcart&controller=productcategoryinfo&task=getinfo&tmpl=component');

define('CATEGORY_IMAGESOURCES', 'jsnisimagesources');
define('JSN_LIST_IMAGE_SOURCE_SERVER', CATEGORY_INFO_URL.'&codename='.CATEGORY_IMAGESOURCES);

define('CATEGORY_THEMES', 'jsnisthemes');
define('JSN_LIST_IMAGE_THEME_SERVER', CATEGORY_INFO_URL.'&codename='.CATEGORY_THEMES);

// LIST PLUGIN INSTALLED
$imageSource = array('picasa');
$theme = array('themeclassic');
$pluginInstalledList = array('imageSource' => $imageSource, 'theme' => $theme);
define('PluginInstalledList', json_encode($pluginInstalledList));

//LIST LANGUAGES SUPPORT
$listLanguageSupported = array('en-GB'=>'en-GB', 'de-DE'=>'de-DE', 'pl-PL'=>'pl-PL', 'fr-FR'=>'fr-FR', 'nl-NL'=>'nl-NL', 'pt-PT'=>'pt-PT', 'it-IT' => 'it-IT');
define('JSN_LIST_LANGUAGE_SUPPORTED', json_encode($listLanguageSupported));
define('JSN_IS_BUY_LINK', 'http://www.joomlashine.com/joomla-extensions/jsn-imageshow-buy-now.html');