<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_edit_source_profile.php 11822 2012-03-21 05:10:45Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
JHTML::_('behavior.tooltip');
$externalSourceID = JRequest::getInt('external_source_id');
$sourceType = JRequest::getString('source_type');
?>
<script>
	window.addEvent('domready', function()
	{
		JSNISImageShow.profileShowHintText();
	});
</script>
<div id="jsn-image-source-profile-details">
	<h3 class="jsn-element-heading"><?php echo JText::_('MAINTENANCE_SOURCE_PROFILE_SETTINGS'); ?></h3>
	<form name='adminForm' id='adminForm' action="index.php" method="post" onsubmit="return false;">
		<div id="jsn-showlist-profile-params">
			<?php echo $this->loadTemplate('profile_'.$sourceType);?>
		</div>
		<div id="jsn-showlist-profile-button" class="jsn-button-container">
			<a onclick="return onSubmit();" id="submit-new-profile-form" class="link-button"><?php echo JText::_('SAVE'); ?></a>
			<span class="jsn-source-icon-loading" id="jsn-create-source"></span>
		</div>
	</form>
</div>
