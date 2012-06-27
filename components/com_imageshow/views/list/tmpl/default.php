<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default.php 11062 2012-02-07 08:03:34Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl_2.0.html
 */
defined('_JEXEC') or die('Restricted access');

$objUtils	= JSNISFactory::getObj('classes.jsn_is_utils');

if(!count($this->showlists))
{
	echo  $objUtils->displayShowlistMissingMessage();
	return false;
}

if(is_null($this->showcase))
{
	echo  $objUtils->displayShowcaseMissingMessage();
	return false;
}
$rel	= '';
$class 	= '';
$tmpl   = '';
$itemid = '&amp;Itemid='.(int) $this->itemid;
if ($this->viewType == 'modal-window')
{
	$rel 		= 'rel="{handler: \'iframe\', size: {x: '.(int) $this->width.', y: '.(int) $this->height.'}}"';
	$class 		= ' modal ';
	$tmpl		= '&amp;tmpl=component';
	$itemid     = '';
}
$config 		=& JFactory::getConfig();
$sef 			= $config->get('sef_rewrite');
$prelink 		= ($sef) ? '' : 'index.php';
$objJSNShowlist	= JSNISFactory::getObj('classes.jsn_is_showlist');
$URL = $objUtils->overrideURL();

?>
<div id="jsn_is_list_container">
	<div id="jsn_is_list_wapper">
    <?php if ($this->menuParams->get('show_page_heading', 1)) { ?>
    	<h1 class="componentheading<?php echo $this->escape($this->menuParams->get('pageclass_sfx')); ?>"><?php echo $this->escape($this->menuParams->get('page_title')); ?></h1>
    <?php } ?>
		<?php if (count($this->showlists)) { ?>
			<?php for($i=0, $count=count($this->showlists); $i<$count;$i++) {
				$item = $this->showlists[$i];
				$dataObj = $objJSNShowlist->getShowlist2JSON($URL, $item->showlist_id);
				$images  = @$dataObj->showlist->images->image;
				@shuffle($images);
				if ($this->viewType == 'modal-window')
				{
					$link = $prelink.'?option=com_imageshow'.$tmpl.'&amp;view=show&amp;showlist_id='.$item->showlist_id.'&amp;showcase_id='.$this->showcase->showcase_id.'&amp;w='.$this->width.'&amp;h='.$this->height.$itemid.'&amp;rand='.$objUtils->randSTR(5);
				}
				else
				{
					$link = $prelink.'?option=com_imageshow'.$tmpl.'&amp;view=show&amp;showlist_id='.$item->showlist_id.'&amp;showcase_id='.$this->showcase->showcase_id.$itemid.'&amp;rand='.$objUtils->randSTR(5);
				}
			?>
				<?php if ($this->menuLayout == 'details') { ?>
					<div class="jsn_is_list_item_container">
						<div class="item-thumb-bg <?php echo ((@$images[0]->thumbnail == '') ? 'item-thumb-bg-empty' : ''); ?>">
							<a href="<?php echo $link; ?>" <?php echo $rel; ?>  class="view_gallery<?php echo $class; ?>">
								<div class="item_thumb_wrapper">
									<div class="item_thumb" style="background-image: url(<?php echo @$images[0]->thumbnail; ?>)">
										<?php
										if (@$images[0]->thumbnail == '')
										{
											echo '<span>'.JText::_('SITE_LIST_EMPTY_GALLERY').'</span>';
										}
										?>
									</div>
								</div>
							</a>
						</div>
						<div class="item_content">
							<div class="title">
								<h3><?php echo $item->showlist_title; ?></h3>
							</div>
							<?php if ($item->description != '') { ?>
								<div class="description">
									<?php echo $item->description; ?>
								</div>
							<?php } ?>
						</div>
					</div>
				<?php } else {?>
					<div class="jsn-is-list-thumbnail">
						<div class="item-thumb-bg <?php echo ((@$images[0]->thumbnail == '') ? 'item-thumb-bg-empty' : ''); ?>">
							<a href="<?php echo $link; ?>" <?php echo $rel; ?>  class="view_gallery<?php echo $class; ?>">
								<div class="item-thumb-loading-container">
									<div class="item-thumb-img" style="background-image: url(<?php echo @$images[0]->thumbnail; ?>)">
										<?php
											if (@$images[0]->thumbnail == '')
											{
												echo '<span>'.JText::_('SITE_LIST_EMPTY_GALLERY').'</span>';
											}
										?>
									</div>
								</div>
							</a>
						</div>
						<h3><?php echo $item->showlist_title; ?></h3>
					</div>
				<?php }?>
			<?php } ?>
		<?php } ?>
	</div>
</div>