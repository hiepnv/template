<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_banners
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder	= $user->authorise('core.edit.state', 'com_banners.category');
$saveOrder	= $listOrder=='ordering';
$params		= (isset($this->state->params)) ? $this->state->params : new JObject();

if($saveOrder) {	
	$saveOrderingUrl = 'index.php?option=com_banners&task=banners.saveOrderAjax&tmpl=component';
	JHtml::_('dndlist.sortable','bannerList','adminForm',$saveOrderingUrl);
}
?>

<form action="<?php echo JRoute::_('index.php?option=com_banners&view=banners'); ?>" method="post" name="adminForm" id="adminForm">
	<div id="filter-bar" class="btn-toolbar">
		<div class="btn-group pull-right">
			<a data-toggle="collapse" data-target="#filters" class="btn"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?> <span class="caret"></span></a>
		</div>
		<div class="filter-search btn-group pull-left">
			<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('COM_BANNERS_SEARCH_IN_TITLE'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_BANNERS_SEARCH_IN_TITLE'); ?>" />
		</div>
		<div class="btn-group pull-left">
			<button type="submit" class="btn tip" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
			<button type="button" class="btn tip" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
		</div>
	</div>
	<div class="clearfix"> </div>
	<div class="collapse" id="filters">
		<div class="filter-select well">
			<select name="filter_state" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true);?>
			</select>
			<select name="filter_client_id" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_BANNERS_SELECT_CLIENT');?></option>
				<?php echo JHtml::_('select.options', BannersHelper::getClientOptions(), 'value', 'text', $this->state->get('filter.client_id'));?>
			</select>
			<select name="filter_category_id" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_banners'), 'value', 'text', $this->state->get('filter.category_id'));?>
			</select>
			<select name="filter_language" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_LANGUAGE');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'));?>
			</select>
		</div>
	</div>
	<table class="table table-striped" id="bannerList">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th>
					<?php echo JHtml::_('grid.sort',  'COM_BANNERS_HEADING_NAME', 'a.name', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_BANNERS_HEADING_STICKY', 'a.sticky', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_BANNERS_HEADING_CLIENT', 'client_name', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ORDERING', 'ordering', $listDirn, $listOrder); ?>
					<?php if ($canOrder && $saveOrder): ?>
						<?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'banners.saveorder'); ?>
					<?php endif;?>
				</th>
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_BANNERS_HEADING_IMPRESSIONS', 'impmade', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_BANNERS_HEADING_CLICKS', 'clicks', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo JText::_('COM_BANNERS_HEADING_METAKEYWORDS'); ?>
				</th>
				<th>
					<?php echo JText::_('COM_BANNERS_HEADING_PURCHASETYPE'); ?>
				</th>
				<th>
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'a.language', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th>
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="13">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) :
			$ordering	= ($listOrder == 'ordering');
			$item->cat_link = JRoute::_('index.php?option=com_categories&extension=com_banners&task=edit&type=other&cid[]='. $item->catid);
			$canCreate	= $user->authorise('core.create',		'com_banners.category.'.$item->catid);
			$canEdit	= $user->authorise('core.edit',			'com_banners.category.'.$item->catid);
			$canCheckin	= $user->authorise('core.manage',		'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
			$canChange	= $user->authorise('core.edit.state',	'com_banners.category.'.$item->catid) && $canCheckin;
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td class="nowrap">
					<?php echo JHtml::_('jgrid.published', $item->state, $i, 'banners.', $canChange, 'cb', $item->publish_up, $item->publish_down); ?>
					<?php if ($item->checked_out) : ?>
						<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'banners.', $canCheckin); ?>
					<?php endif; ?>
					<?php if ($canEdit) : ?>
						<a href="<?php echo JRoute::_('index.php?option=com_banners&task=banner.edit&id='.(int) $item->id); ?>">
							<?php echo $this->escape($item->name); ?></a>
					<?php else : ?>
							<?php echo $this->escape($item->name); ?>
					<?php endif; ?>
					<span class="small">
						<?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias));?>
					</span>
					<div class="small">
						<?php echo $this->escape($item->category_title); ?>
					</div>
				</td>
				<td class="center">
					<?php echo JHtml::_('jgrid.published', $item->sticky, $i, 'banners.sticky_', $canChange);?>
				</td>
				<td class="small">
					<?php echo $item->client_name;?>
				</td>
				<td class="nowrap order">
					<?php if ($canChange) : ?>
						<div class="input-prepend">
							<?php if ($saveOrder) : ?>
								<?php if ($listDirn == 'asc') : ?>
									<span class="add-on"><?php echo $this->pagination->orderUpIcon($i, (@$this->items[$i-1]->catid == $item->catid), 'banners.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span><span class="add-on"><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, (@$this->items[$i+1]->catid == $item->catid), 'banners.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
								<?php elseif ($listDirn == 'desc') : ?>
									<span class="add-on"><?php echo $this->pagination->orderUpIcon($i, (@$this->items[$i-1]->catid == $item->catid), 'banners.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span><span class="add-on"><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, (@$this->items[$i+1]->catid == $item->catid), 'banners.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
								<?php endif; ?>
							<?php endif; ?>
							<?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
							<?php if(!$disabled = $saveOrder) : echo "<span class=\"add-on tip\" title=\"".JText::_('JDISABLED')."\"><i class=\"icon-ban-circle\"></i></span>"; endif;?><input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled; ?> class="width-20 text-area-order" />
						</div>
					<?php else : ?>
						<?php echo $item->ordering; ?>
					<?php endif; ?>
				</td>
				<td class="small">
					<?php echo JText::sprintf('COM_BANNERS_IMPRESSIONS', $item->impmade, $item->imptotal ? $item->imptotal : JText::_('COM_BANNERS_UNLIMITED'));?>
				</td>
				<td class="center small">
					<?php echo $item->clicks;?> -
					<?php echo sprintf('%.2f%%', $item->impmade ? 100 * $item->clicks/$item->impmade : 0);?>
				</td>
				<td>
					<?php echo $item->metakey; ?>
				</td>
				<td class="small">
					<?php if ($item->purchase_type < 0):?>
						<?php echo JText::sprintf('COM_BANNERS_DEFAULT', ($item->client_purchase_type > 0) ? JText::_('COM_BANNERS_FIELD_VALUE_'.$item->client_purchase_type) : JText::_('COM_BANNERS_FIELD_VALUE_'.$params->get('purchase_type')));?>
					<?php else:?>
						<?php echo JText::_('COM_BANNERS_FIELD_VALUE_'.$item->purchase_type);?>
					<?php endif;?>
				</td>
				<td class="small nowrap">
					<?php if ($item->language=='*'):?>
						<?php echo JText::alt('JALL', 'language'); ?>
					<?php else:?>
						<?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
					<?php endif;?>
				</td>
				<td class="center">
					<?php echo $item->id; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php //Load the batch processing form. ?>
	<?php echo $this->loadTemplate('batch'); ?>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
