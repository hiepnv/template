<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_step1.php 13228 2012-06-12 09:18:53Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
echo JText::_('UPDATER_BASIC_INFO');
echo JText::_('UPDATER_IMPORTANT_INFO');
echo '<hr class="jsn-horizontal-line" />';
$elementID 		= JRequest::getInt('element_id');
$type 			= JRequest::getVar('type');
$objVersion		= new JVersion();
$authentication =  false;

if ($this->imageshowCore->commercial && $this->imageshowCore->needUpdate ) {
	$authentication = true;
}

$dataUpdate = array();
$core 		= array();
$themes 	= array();
$sources 	= array();
$html = '';
foreach ($this->themes as $key => $theme)
{
	if ($theme->needUpdate)
	{
		if ($theme->authentication) $authentication = true;
		$themeInfoUpdate 					= new stdClass();
		$themeInfoUpdate->elementID 		= 'jsn-update-element-theme-'.$key;
		$themeInfoUpdate->identify_name 	= $theme->identified_name;
		$themeInfoUpdate->edition 			= '';
		$themeInfoUpdate->joomla_version 	= $objVersion->RELEASE;
		$themes[] 							= $themeInfoUpdate;
		$html .= '<li id="jsn-update-element-theme-'.$key.'">'.JText::_('UPDATER_THEME').' "'. $theme->name .'"</li>';
	}
}

foreach ($this->sources as $key => $source)
{
	if ($source->needUpdate)
	{
		if ($source->authentication) $authentication = true;
		$sourceInfoUpdate 					= new stdClass();
		$sourceInfoUpdate->elementID 		= 'jsn-update-element-source-'.$key;
		$sourceInfoUpdate->identify_name 	= $source->identified_name;
		$sourceInfoUpdate->edition 			= '';
		$sourceInfoUpdate->joomla_version 	= $objVersion->RELEASE;
		$sources[] 							= $sourceInfoUpdate;
		$html .= '<li id="jsn-update-element-source-'.$key.'">'.JText::_('UPDATER_SOURCE').' "'. $source->name.'"</li>';
	}
}
?>
<?php if (count($sources) || count($themes) || $this->imageshowCore->needUpdate){ ?>
<p><strong><?php echo JText::_('UPDATER_UPDATE_DESCRIPTION'); ?></strong></p>
<ul>
	<?php if ($this->imageshowCore->needUpdate): ?>
		<?php
			$coreInfoUpdate 				= new stdClass();
			$coreInfoUpdate->elementID 		= 'jsn-update-element-core';
			$coreInfoUpdate->identify_name 	= $this->imageshowCore->id;
			$coreInfoUpdate->edition 		= $this->imageshowCore->edition;
			$coreInfoUpdate->joomla_version = $objVersion->RELEASE;
			$core[] 						= $coreInfoUpdate;
		?>
		<li  id="jsn-update-element-core"><?php echo JText::_('UPDATER_JSN_IMAGESHOW_CORE');?></li>
	<?php endif;?>
	<?php
		echo $html;
	?>
</ul>
<form method="POST" action="index.php?option=com_imageshow&controller=updater&step=<?php echo ($authentication)?'2':'3';?>" id="frm_updateinfo" name="frm_updateinfo" class="upgrader-from" autocomplete="off">
	<div class="jsn-upgrader-step1">
		<a class="link-button" href="javascript: void(0);" onclick="document.frm_updateinfo.submit();" id="jsn-proceed-button">
		<?php echo JText::_('UPDATER_BUTTON_UPDATE'); ?></a>
	</div>
	<input type="hidden" name="task" value="update_proceeded" />
</form>
<?php } else if (!count($sources) && !count($themes) && !$this->imageshowCore->needUpdate && $this->canAutoUpdate) { ?>
	<p><strong><?php echo JText::_('UPDATER_UPDATE_NO_UPDATE_FOUND'); ?></strong></p>
<?php } else {?>
	<p><?php echo JText::_('UPDATER_UPDATE_FAILED_TO_CONTACT_TO_VERSIONING_SERVER'); ?></p>
<?php }?>
