<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_theme_config.php 11469 2012-03-01 10:02:43Z thangbh $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
?>
<script>
	window.addEvent('domready', function()
	{
		JSNISImageShow.profileShowHintText();
	});
</script>
<div id="jsn-theme-details">
	<h3 class="jsn-element-heading"><?php echo JText::_('MAINTENANCE_THEME_PARAMETER_SETTINGS'); ?></h3>
	<form action="index.php?option=com_imageshow&controller=maintenance&type=themeparameters&theme_name=themeclassic&tmpl=component" method="POST" name="adminForm" id="frm_theme_param">
		<div id="jsn-showcase-theme-params">
			<?php echo $this->loadTemplate('theme_parameters'); ?>
		</div>
		<div class="jsn-button-container">
			<button type="button" class="link-button" onclick="return submitThemeParameterForm();" value="<?php echo JText::_('PARAMETER_SAVE'); ?>"><?php echo JText::_('PARAMETER_SAVE'); ?></button>
		</div>
	</form>
</div>