<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow - Image Source Flickr
 * @version $Id: define.php 8988 2011-10-17 08:13:18Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
$jsnImageSourceFolder = array(
	'description' => 'Local image folder',
	'thumb' => 'administrator/components/com_imageshow/imagesources/source_folder/assets/images/thumb-folder.png'
);

define('JSN_IS_SOURCEFOLDER', json_encode($jsnImageSourceFolder));
