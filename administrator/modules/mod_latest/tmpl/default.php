<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	mod_latest
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>

<div class="row-striped">
	<?php if (count($list)) : ?>
		<?php foreach ($list as $i=>$item) : ?>
			<div class="row-fluid">
				<div class="span9">
					<h5>
						<?php echo JHtml::_('jgrid.published', $item->state, $i, '', false); ?> 
						<?php if ($item->checked_out) : ?>
								<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time); ?>
						<?php endif; ?>
		
						<?php if ($item->link) :?>
							<a href="<?php echo $item->link; ?>">
								<?php echo htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8');?></a>
						<?php else :
							echo htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8');
						endif; ?>
						<small class="small" rel="tooltip" title="<?php echo JText::_('MOD_LATEST_CREATED_BY');?>">
							<?php echo $item->author_name;?>
						</small>
					</h5>
				</div>
				<div class="span3">
					<span class="small"><i class="icon-calendar"></i> <?php echo JHtml::_('date', $item->created, 'Y-m-d'); ?></span>
				</div>
			</div>
		<?php endforeach; ?>
	<?php else : ?>
		<div class="row-fluid">
			<div class="span12">
				<div class="alert"><?php echo JText::_('MOD_LATEST_NO_MATCHING_RESULTS');?></div>
			</div>
		</div>
	<?php endif; ?>
</div>
