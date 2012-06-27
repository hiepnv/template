<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_configs.php 13385 2012-06-18 11:29:33Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
?>

<div id="jsn-main-content">
	<div id="jsn-configuration">
		<form action="index.php?option=com_imageshow&controller=maintenance&type=msgs" method="POST" name="adminForm" id="frm_param" class="well">
			<table class="admintable" border="0" width=" 100%">
				<tbody>
					<tr>
						<td class="key"><span class="editlinktip hasTip" title="<?php echo htmlspecialchars(JText::_('MAINTENANCE_SHOW_QUICK_ICONS'));?>::<?php echo htmlspecialchars(JText::_('MAINTENANCE_SHOW_QUICK_ICONS_DES')); ?>"><?php echo JText::_('MAINTENANCE_SHOW_QUICK_ICONS');?></span></td>
						<td><?php echo $this->lists['showQuickIcons']; ?></td>
					</tr>
				</tbody>
			</table>
			<input type="hidden" name="option" value="com_imageshow" />
			<input type="hidden" name="controller" value="maintenance" />
			<input type="hidden" name="task" value="saveparam" id="task" />
			<?php echo JHTML::_('form.token'); ?>
			<div class="jsn-button-container jsn-bootstrap">
				<button class="btn" type="submit" value="<?php echo JText::_('SAVE'); ?>"><?php echo JText::_('SAVE'); ?></button>
			</div>
		</form>
	</div>
</div>