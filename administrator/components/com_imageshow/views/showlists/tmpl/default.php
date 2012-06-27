<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default.php 13385 2012-06-18 11:29:33Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
JHTML::_('behavior.tooltip');
JToolBarHelper::title( JText::_('JSN_IMAGESHOW').': '.JText::_('SHOWLIST_SHOWLISTS_MANAGER'), 'showlist');
JToolBarHelper::addNew();
JToolBarHelper::editList();
JToolBarHelper::divider();
JToolBarHelper::publishList();
JToolBarHelper::unpublishList();
JToolBarHelper::divider();
JToolBarHelper::deleteList();
JToolBarHelper::divider();
$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
$objJSNUtils->callJSNButtonMenu();
$ordering	= true;
$session 		= JFactory::getSession();
$identifier		= md5('jsn_imageshow_downloasource_identify_name');
$session->set($identifier, '', 'jsnimageshowsession');
?>
<div id="jsn-showlists-container">
<div class="jsn-bootstrap">
<form action="index.php?option=com_imageshow&controller=showlist" method="post" name="adminForm" id="adminForm" class="well form-search">
<fieldset>
	<div class="filter-search fltlft">
		<label><?php echo JText::_('FILTER'); ?> :</label>
		<span class="button-wrapper"><input type="text" class="input-xlarge" name="showlist_stitle" id="showlist_stitle" value="<?php echo $this->lists['showlistTitle'];?>" /></span>
		<span class="button-wrapper"><button class="btn" onclick="this.form.submit();"><?php echo JText::_('GO'); ?></button></span>
		<span class="button-wrapper"><button class="btn" onclick="document.getElementById('showlist_stitle').value=''; document.adminForm.filter_access.value=''; document.adminForm.filter_state.value='';this.form.submit();"><?php echo JText::_('RESET'); ?></button></span>
	</div>
	<div class="filter-select fltrt">
 		<select name="filter_access" class="inputbox" onchange="this.form.submit()">
			<option value=""><?php echo JText::_('JOPTION_SELECT_ACCESS');?></option>
			<?php echo JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->lists['access']);?>
		</select>
		<?php echo $this->lists['state'];?>
	</div>
</fieldset>
<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th width="10" class="center">#</th>
			<th width="5" class="center">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
			</th>
			<th width="55%">
				<?php echo JHTML::_('grid.sort',  JText::_('SHOWLIST_TITLE'), 'sl.showlist_title', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="70" nowrap="nowrap" class="center">
				<?php echo JHTML::_('grid.sort', JText::_('SHOWLIST_PUBLISHED'), 'sl.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th nowrap="nowrap" width="100" class="jsn-order center">
				<?php echo JHTML::_('grid.sort',   'SHOWLIST_ORDER', 'sl.ordering', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				<?php
					if ($ordering) echo JHTML::_('grid.order',  $this->items );
				?>
			</th>
			<th width="70" nowrap="nowrap" class="center">
				<?php echo JText::_('SHOWLIST_ACCESS_LEVEL'); ?>
			</th>
			<th width="15%" nowrap="nowrap" class="center">
				<?php echo JText::_('SHOWLIST_IMAGE_SOURCE'); ?>
			</th>
			<th width="50" nowrap="nowrap" class="center">
				<?php echo JText::_('SHOWLIST_IMAGES'); ?>
			</th>
			<th width="20" nowrap="nowrap" class="center">
				<?php echo JHTML::_('grid.sort', JText::_('SHOWLIST_HITS'), 'sl.hits', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="20" nowrap="nowrap" class="center">
				<?php echo JHTML::_('grid.sort', JText::_('ID'), 'sl.showlist_id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$k 		= 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row 			= $this->items[$i];
		$checked 		= JHTML::_('grid.id', $i, $row->showlist_id );
		$link 			= JRoute::_( 'index.php?option=com_imageshow&controller=showlist&task=edit&cid[]='.$row->showlist_id);

		$sourceTitle = '<br/>'.JText::_('N/A');

		if ($row->image_source_name)
		{
			$imageSource 	= JSNISFactory::getSource($row->image_source_name, $row->image_source_type, $row->showlist_id);
			$sourceTitle 	= $imageSource->getProfileTitle();

			if ($sourceTitle != '') {
				$sourceTitle = '<em>['.$imageSource->_source['sourceIdentify'].']</em><br/>'.$this->escape($sourceTitle);
			} else {
				$sourceTitle = '<br/>'.JText::_('N/A');
			}
		}

		$published 		= JHTML::_('grid.published', $row, $i );
	?>
		<tr class="<?php echo "row$k"; ?>">
			<td class="center">
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			<td class="center"><?php echo $checked; ?></td>
			<td>
				<a href="<?php echo $link; ?>" title="<?php echo htmlspecialchars(JText::_('SHOWLIST_EDIT_SHOWLIST_DETAILS')); ?>">
					<?php echo $this->escape($row->showlist_title); ?>
				</a>
			</td>
			<td class="center"><?php echo $published; ?></td>
			<td class="list-order center">
				<span><?php echo $this->pagination->orderUpIcon( $i, true, 'orderup', 'Move Up', $ordering ); ?></span>
				<span><?php echo $this->pagination->orderDownIcon( $i, $n, true, 'orderdown', 'Move Down', $ordering ); ?></span>
				<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" <?php echo $disabled ?> class="span1" style="text-align: center" />
			</td>
			<td class="center"><?php echo $row->access_level;?></td>
			<td class="center"><?php echo $sourceTitle; ?></td>
			<td class="center"><?php echo $row->totalimage?></td>
			<td class="center"><?php echo $row->hits;?></td>
			<td class="center"><?php echo $row->showlist_id;?></td>
		</tr>
	<?php
		$k = 1 - $k;
	}
	?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="10">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
</table>
<input type="hidden" name="option" value="com_imageshow" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="showlist" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
</div>
<?php include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'footer.php'); ?>