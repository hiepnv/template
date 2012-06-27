<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_themes.php 13385 2012-06-18 11:29:33Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
?>
<script>
function submitform()
{
	document.getElementById('frm_themes').submit();
}
</script>

<div id="jsn-main-content">
	<div id="jsn-themes-manager" class="jsn-bootstrap">
		<form action="index.php?option=com_imageshow&controller=maintenance&type=themes" method="POST" name="adminForm" id="frm_themes">
			<table class="table table-bordered" border="0">
				<thead>
					<tr>
						<th width="10" class="center">#</th>
						<th width="20%" style="display:none;"> <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->listJSNPlugins);?>);" /> </th>
						<th class="title" nowrap="nowrap" width="80%"> <?php echo JText::_('MAINTENANCE_THEME_THEME_NAME'); ?> </th>
						<th width="5%" class="center"> <?php echo JText::_('MAINTENANCE_THEME_THEME_VERSION'); ?> </th>
						<th width="10%" nowrap="nowrap" class="center"> <?php echo JText::_('MAINTENANCE_THEME_ACTIONS'); ?> </th>
					</tr>
				</thead>
				<tbody>
					<?php
				$k 	= 0;
				$n	= count($this->listJSNPlugins);
				for ($i=0 ; $i < $n; $i++)
				{
					$row = &$this->listJSNPlugins[$i];

					?>
					<tr class="<?php //echo "row$k"; ?>">
						<td align="center" class="center"><?php echo $this->pagination->getRowOffset( $i ); ?></td>
						<td><?php echo $this->escape($row->name); ?></td>
						<td align="center" class="center"><?php echo $row->version;?></td>
						<td align="center" class="actionprofile center">
							<?php if(JFile::exists(JPATH_PLUGINS.DS.'jsnimageshow'.DS.$row->element.DS.'views'.DS.'maintenance'.DS.'tmpl'.DS.'default_theme_parameters.php')) { ?>
								<a rel="{handler: 'iframe', size: {x: 400, y: 500}}" href="index.php?option=com_imageshow&controller=maintenance&type=themeparameters&theme_name=<?php echo $row->element; ?>&tmpl=component" class="action-edit jsn-modal" title="<?php echo htmlspecialchars(JText::_('MAINTENANCE_THEME_EDIT_SETTINGS'))?>"></a>
								&nbsp;
							<?php } ?>
							<?php if($n > 1) { ?>
								<a href="<?php echo JRoute::_('index.php?option=com_imageshow&controller=maintenance&type=themes&task=deleteTheme&plugin_theme_id='.$row->extension_id .'&plugin_theme_name='.$row->element);?>" class="action-delete"> </a>
							<?php } else { ?>
								<a class="action-delete-disabled" title="<?php echo htmlspecialchars(JText::_('MAINTENANCE_THEME_YOU_CAN_NOT_DELETE_THE_ONLY_THEME_IN_THE_LIST')); ?>"></a>
							<?php }?>
						</td>
					</tr>
					<?php
					$k = 1 - $k;
				}
				?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4" align="center"><?php echo $this->pagination->getListFooter(); ?></td>
					</tr>
				</tfoot>
			</table>
			<input type="hidden" name="option" value="com_imageshow" />
			<input type="hidden" name="controller" value="maintenance" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
			<?php echo JHTML::_('form.token'); ?>
		</form>
	</div>
</div>
