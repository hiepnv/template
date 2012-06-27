<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_modules
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$client		= $this->state->get('filter.client_id') ? 'administrator' : 'site';
$user		= JFactory::getUser();
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder	= $user->authorise('core.edit.state', 'com_modules');
$saveOrder	= $listOrder == 'ordering';
?>
<form action="<?php echo JRoute::_('index.php?option=com_modules'); ?>" method="post" name="adminForm" id="adminForm">
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
				<ul id="submenu" class="nav nav-list">
					<li class="<?php if(($this->state->get('filter.client_id')) == 0): echo "active"; endif;?>">
						<a href="index.php?option=com_modules&filter_client_id=0">
							<?php echo JText::_('JSITE'); ?>
						</a>
					</li>
					<li class="<?php if(($this->state->get('filter.client_id')) == 1): echo "active"; endif;?>">
						<a href="index.php?option=com_modules&filter_client_id=1">
							<?php echo JText::_('JADMINISTRATOR'); ?>
						</a>
					</li>
				</ul>
				<hr />
				<div class="filter-select">
					<h4 class="page-header"><?php echo JText::_('JSEARCH_FILTER_LABEL');?></h4>
					<select name="filter_client_id" class="span12 small" onchange="this.form.submit()">
						<?php echo JHtml::_('select.options', ModulesHelper::getClientOptions(), 'value', 'text', $this->state->get('filter.client_id'));?>
					</select>
					<hr class="hr-condensed" />
		             <select name="filter_state" class="span12 small" onchange="this.form.submit()">
						<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
						<?php echo JHtml::_('select.options', ModulesHelper::getStateOptions(), 'value', 'text', $this->state->get('filter.state'));?>
					</select>
					<hr class="hr-condensed" />
					<select name="filter_position" class="span12 small" onchange="this.form.submit()">
						<option value=""><?php echo JText::_('COM_MODULES_OPTION_SELECT_POSITION');?></option>
						<?php echo JHtml::_('select.options', ModulesHelper::getPositions($this->state->get('filter.client_id')), 'value', 'text', $this->state->get('filter.position'));?>
					</select>
					<hr class="hr-condensed" />
		            <select name="filter_module" class="span12 small" onchange="this.form.submit()">
						<option value=""><?php echo JText::_('COM_MODULES_OPTION_SELECT_MODULE');?></option>
						<?php echo JHtml::_('select.options', ModulesHelper::getModules($this->state->get('filter.client_id')), 'value', 'text', $this->state->get('filter.module'));?>
					</select>
					<hr class="hr-condensed" />
					<select name="filter_access" class="span12 small" onchange="this.form.submit()">
						<option value=""><?php echo JText::_('JOPTION_SELECT_ACCESS');?></option>
						<?php echo JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'));?>
					</select>
					<hr class="hr-condensed" />
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
					<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('COM_MODULES_MODULES_FILTER_SEARCH_DESC'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_MODULES_MODULES_FILTER_SEARCH_DESC'); ?>" />
				</div>
				<div class="btn-group pull-left">
					<button class="btn" rel="tooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
					<button class="btn" rel="tooltip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
				</div>
			</div>
			<div class="clearfix"> </div>
			<table class="table table-striped" id="modules-mgr">
				<thead>
					<tr>
						<th width="1%">
							<input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
						</th>
						<th class="title">
							<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
						</th>
						<th width="15%" class="left">
							<?php echo JHtml::_('grid.sort',  'COM_MODULES_HEADING_POSITION', 'position', $listDirn, $listOrder); ?>
						</th>
		                <th width="15%">
							<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ORDERING', 'ordering', $listDirn, $listOrder); ?>
							<?php if ($canOrder && $saveOrder) :?>
								<?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'modules.saveorder'); ?>
							<?php endif; ?>
						</th>
		                <th width="10%" class="left" >
							<?php echo JHtml::_('grid.sort', 'COM_MODULES_HEADING_MODULE', 'name', $listDirn, $listOrder); ?>
						</th>
						<th width="10%">
							<?php echo JHtml::_('grid.sort',  'COM_MODULES_HEADING_PAGES', 'pages', $listDirn, $listOrder); ?>
						</th>
						<th width="10%">
							<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ACCESS', 'a.access', $listDirn, $listOrder); ?>
						</th>
						<th width="5%">
							<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'language_title', $listDirn, $listOrder); ?>
						</th>
						<th width="1%" class="nowrap">
							<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="10">
							<?php echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
				<?php foreach ($this->items as $i => $item) :
					$ordering	= ($listOrder == 'ordering');
					$canCreate	= $user->authorise('core.create',		'com_modules');
					$canEdit	= $user->authorise('core.edit',			'com_modules');
					$canCheckin	= $user->authorise('core.manage',		'com_checkin') || $item->checked_out==$user->get('id')|| $item->checked_out==0;
					$canChange	= $user->authorise('core.edit.state',	'com_modules') && $canCheckin;
				?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center">
							<?php echo JHtml::_('grid.id', $i, $item->id); ?>
						</td>
						<td>
							<?php echo JHtml::_('modules.state', $item->published, $i, $canChange, 'cb'); ?>
							<?php if ($item->checked_out) : ?>
								<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'modules.', $canCheckin); ?>
							<?php endif; ?>
							<?php if ($canEdit) : ?>
								<a href="<?php echo JRoute::_('index.php?option=com_modules&task=module.edit&id='.(int) $item->id); ?>">
									<?php echo $this->escape($item->title); ?></a>
							<?php else : ?>
									<?php echo $this->escape($item->title); ?>
							<?php endif; ?>
		
							<?php if (!empty($item->note)) : ?>
							<p class="smallsub">
								<?php echo JText::sprintf('JGLOBAL_LIST_NOTE', $this->escape($item->note));?></p>
							<?php endif; ?>
						</td>
						<td class="small">
							<?php if ($item->position) : ?>
								<span class="label label-info">
									<?php echo $item->position; ?>
								</span>
							<?php else : ?>
								<span class="label">
									<?php echo JText::_('JNONE'); ?>
								</span>
							<?php endif; ?>
						</td>
						<td class="order">
							<?php if ($canChange) : ?>
								<div class="input-prepend">
									<?php if ($saveOrder) :?>
										<?php if ($listDirn == 'asc') : ?>
											<span class="add-on"><?php echo $this->pagination->orderUpIcon($i, (@$this->items[$i-1]->position == $item->position), 'modules.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span><span class="add-on"><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, (@$this->items[$i+1]->position == $item->position), 'modules.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
										<?php elseif ($listDirn == 'desc') : ?>
											<span class="add-on"><?php echo $this->pagination->orderUpIcon($i, (@$this->items[$i-1]->position == $item->position), 'modules.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span><span class="add-on"><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, (@$this->items[$i+1]->position == $item->position), 'modules.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
										<?php endif; ?>
									<?php endif; ?>
									<?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
									<?php if(!$disabled = $saveOrder) : echo "<span class=\"add-on\" rel=\"tooltip\" title=\"".JText::_('JDISABLED')."\"><i class=\"icon-ban-circle\"></i></span>"; endif;?><input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="width-20 text-area-order" />
								</div>
							<?php else : ?>
								<?php echo $item->ordering; ?>
							<?php endif; ?>
						</td>
		                <td class="small">
							<?php echo $item->name;?>
						</td>
						<td class="small">
							<?php echo $item->pages; ?>
						</td>
		
						<td class="small">
							<?php echo $this->escape($item->access_level); ?>
						</td>
						<td class="small">
							<?php if ($item->language==''):?>
								<?php echo JText::_('JDEFAULT'); ?>
							<?php elseif ($item->language=='*'):?>
								<?php echo JText::alt('JALL', 'language'); ?>
							<?php else:?>
								<?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
							<?php endif;?>
						</td>
						<td class="center">
							<?php echo (int) $item->id; ?>
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
		</div>
		<!-- End Content -->
	</div>
</form>
