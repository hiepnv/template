<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$user		= JFactory::getUser();
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder	= $user->authorise('core.edit.state', 'com_content.article');
$saveOrder	= $listOrder == 'fp.ordering';
?>
<form action="<?php echo JRoute::_('index.php?option=com_content&view=featured');?>" method="post" name="adminForm" id="adminForm">
<div class="row-fluid">
	<!-- Begin Sidebar -->
	<div id="sidebar" class="span2">
		<div class="sidebar-nav">
			<?php
				// Display the submenu position modules
				$this->modules = JModuleHelper::getModules('submenu');
				foreach ($this->modules as $module) {
					$output = JModuleHelper::renderModule($module);
					$params = new JRegistry;
					$params->loadString($module->params);
					echo $output;
				}
			?>
			<hr />
			<div class="filter-select">
				<h4 class="page-header"><?php echo JText::_('JSEARCH_FILTER_LABEL');?></h4>
				<select name="filter_published" class="span12 small" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
					<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true);?>
				</select>
				<select name="filter_access" class="span12 small" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('JOPTION_SELECT_ACCESS');?></option>
					<?php echo JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'));?>
				</select>
				<select name="filter_language" class="span12 small" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('JOPTION_SELECT_LANGUAGE');?></option>
					<?php echo JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'));?>
				</select>
			</div>
		</div>
	</div>
	<!-- End Sidebar -->
	<!-- Begin Content -->
	<div class="span10">
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" />
			</div>
			<div class="btn-group">
				<button class="btn tip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button class="btn tip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
			</div>
		</div>
		<div class="clearfix"> </div>
		<table class="table table-striped">
			<thead>
				<tr>
					<th width="1%">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th class="title">
						<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
					</th>
					<th width="15%">
						<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ORDERING', 'fp.ordering', $listDirn, $listOrder); ?>
						<?php if ($canOrder && $saveOrder) :?>
							<?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'featured.saveorder'); ?>
						<?php endif; ?>
					</th>
					<th width="10%">
						<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ACCESS', 'a.access', $listDirn, $listOrder); ?>
					</th>
					<th width="10%">
						<?php echo JHtml::_('grid.sort', 'JAUTHOR', 'a.created_by', $listDirn, $listOrder); ?>
					</th>
					<th width="5%">
						<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'a.language', $listDirn, $listOrder); ?>
					</th>
					<th width="5%">
						<?php echo JHtml::_('grid.sort', 'JDATE', 'a.created', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" class="nowrap">
						<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="15">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php
			foreach ($this->items as $i => $item) :
				$item->max_ordering = 0; //??
				$ordering	= ($listOrder == 'fp.ordering');
				$assetId	= 'com_content.article.'.$item->id;
				$canCreate	= $user->authorise('core.create',		'com_content.category.'.$item->catid);
				$canEdit	= $user->authorise('core.edit',			'com_content.article.'.$item->id);
				$canCheckin	= $user->authorise('core.manage',		'com_checkin') || $item->checked_out==$user->get('id')|| $item->checked_out==0;
				$canChange	= $user->authorise('core.edit.state',	'com_content.article.'.$item->id) && $canCheckin;
				?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>
					<td>
						<?php echo JHtml::_('jgrid.published', $item->state, $i, 'articles.', $canChange, 'cb', $item->publish_up, $item->publish_down); ?>
						<?php if ($item->checked_out) : ?>
							<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'featured.', $canCheckin); ?>
						<?php endif; ?>
						<?php if ($canEdit) : ?>
						<a href="<?php echo JRoute::_('index.php?option=com_content&task=article.edit&return=featured&id='.$item->id);?>" rel="popover" title="<?php echo JText::_('JDETAILS');?>" data-content="<?php echo JText::_('JFIELD_ALIAS_LABEL') . " " . $this->escape($item->alias) . "<br />\n" . JText::_('JGLOBAL_HITS') . " " . (int) $item->hits . " \n";?>" class="pop">
							<?php echo $this->escape($item->title); ?></a>
						<?php else : ?>
							<?php echo $this->escape($item->title); ?>
						<?php endif; ?>
						<div class="small">
							<?php echo JText::_('JCATEGORY') . ": " . $this->escape($item->category_title); ?>
						</div>
					</td>
					<td class="order">
						<?php if ($canChange) : ?>
							<div class="input-prepend">
								<?php if ($saveOrder) :?>
									<?php if ($listDirn == 'asc') : ?>
										<span class="add-on"><?php echo $this->pagination->orderUpIcon($i, true, 'featured.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span><span class="add-on"><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'featured.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
									<?php elseif ($listDirn == 'desc') : ?>
										<span class="add-on"><?php echo $this->pagination->orderUpIcon($i, true, 'featured.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span><span class="add-on"><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'featured.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
									<?php endif; ?>
								<?php endif; ?>
								<?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
								<?php if(!$disabled = $saveOrder) : echo "<span class=\"add-on tip\" title=\"".JText::_('JDISABLED')."\"><i class=\"icon-ban-circle\"></i></span>"; endif;?><input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="width-20 text-area-order" />
							</div>
						<?php else : ?>
							<?php echo $item->ordering; ?>
						<?php endif; ?>
					</td>
					<td class="small">
						<?php echo $this->escape($item->access_level); ?>
					</td>
					<td class="small">
						<?php echo $this->escape($item->author_name); ?>
					</td>
					<td class="small">
						<?php if ($item->language=='*'):?>
							<?php echo JText::alt('JALL', 'language'); ?>
						<?php else:?>
							<?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
						<?php endif;?>
					</td>
					<td class="small nowrap">
						<?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC4')); ?>
					</td>
					<td class="center">
						<?php echo (int) $item->id; ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	
			<div>
				<input type="hidden" name="task" value="" />
				<input type="hidden" name="featured" value="1" />
				<input type="hidden" name="boxchecked" value="0" />
				<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
				<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
				<?php echo JHtml::_('form.token'); ?>
			</div>
		</div>
		<!-- End Content -->
	</div>
</form>