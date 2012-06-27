<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: form.php 13385 2012-06-18 11:29:33Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.html.pane');
$task 				= JRequest::getVar('task');
$showlistID 		= JRequest::getVar('cid');
$showlistID 		= $showlistID[0];
$pane 				= JPane::getInstance('Sliders', array('allowAllClose' => true));
$msgChangeSource	= JText::_('SHOWLIST_MSG_CHANGE_SOURCE', true);
$changeSource 		= (!empty($this->items->image_source_name)) ? "<a href=\"javascript: void(0);\" onclick=\"JSNISImageShow.confirmChangeSource('".$msgChangeSource."', ".(int)$showlistID.", ".(int) $this->countImage.");\" class=\"btn\"><i class=\"icon-folder-open\"></i> ".JText::_('SHOWLIST_CHANGE_SOURCE', true)."</a>" : "";
$text = '';
if (isset($this->items->image_source_name) && $this->items->image_source_name != '')
{
	$text = JText::_('SHOWLIST_TITLE_SHOWLIST_IMAGES');
}
else
{
	$text = JText::_('SHOWLIST_TITLE_SHOWLIST_SOURCES');
}
?>
<!--[if IE]>
	<link href="<?php echo JURI::base();?>components/com_imageshow/assets/css/fixie.css" rel="stylesheet" type="text/css" />
<![endif]-->
<?php
echo $this->loadTemplate('showlist');
echo '<div class="jsn-showlist-images"><h3 class="jsn-element-heading jsn-bootstrap">'.$text.$changeSource.'</h3></div>';
		if($task == 'add'){
			echo '
				<div id="jsn-no-showlist">
					<p class="jsn-showlist-empty-warning">'.JText::_('SHOWLIST_PLEASE_SAVE_THIS_SHOWLIST_BEFORE_SELECTING_IMAGES').'</p>
					<div class="jsn-button-container jsn-bootstrap">
						<a id="jsn-go-link" class="btn" href="javascript: javascript:Joomla.submitbutton('."'apply'".');">'.JText::_('SHOWLIST_SAVE_SHOWLIST').'</a>
					</div>
				</div>
				';
		}
		else
		{
			if (isset($this->items->image_source_name) && $this->items->image_source_name != '') {
				echo $this->loadTemplate('sortable');
			} else {
				echo '<div class="jsn-showlist-source-wrapper">';
				echo $this->loadTemplate('sources');
				echo $this->loadTemplate('install_sources');
				echo '</div>';
			}
		}

?>

<?php
include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'footer.php');
?>
<!-- <div id="dialogbox" style="display:none;" class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-window-site-manager ui-draggable"> -->
<div id="dialogbox" style="background:white;">

</div>

<div id="dialogbox2" style="background: white;display:none;">
<div class="jsn-bootstrap">
	<style>
		#categories{
			width: 360px;
			float: left;
		}
		#article-cate{
			width: 360px;
			float: left;
		}
		#menu-list{
			width: 360px;
			float: left;
		}
		#menu-item{
			width: 360px;
			float: left;
			margin-left: 5px;
		}
		.clr{
			clear: both;
		}
		.selected{
			background: #ccc;
		}
	</style>

	<div class="demo" style="min-width: 300px; min-height: 300px;">

	<div id="tabs">
		<ul>
			<li><a href="#tabs-1"><?php echo JText::_('SHOWLIST_POPUP_IMAGE_ARTICLE');?></a></li>
			<li><a href="#tabs-2"><?php echo JText::_('SHOWLIST_POPUP_IMAGE_MENU');?></a></li>
		</ul>
		<div id="tabs-1">
			<div id="categories">
					<?php echo JText::_('SHOWLIST_POPUP_IMAGE_CATEGORIES');?> <br />
					<div style="border: 1px solid #ccc;margin-right: 5px;height:300px; overflow:auto;">
					<?php
					echo $this->articles_catgories;
					?>
					</div>
			</div>
			<div id="article-cate">
					<?php echo JText::_('SHOWLIST_POPUP_IMAGE_ARTICLES');?> <br />
					<div id="article-cate-list" style="border: 1px solid #ccc;height:300px; overflow:auto;">

					</div>
			</div>
			<div class="clr"></div>
		</div>
		<div id="tabs-2">
			<div id="menu-list">
					<?php echo JText::_('SHOWLIST_POPUP_IMAGE_MENU');?> <br />
					<?php
					// echo '<pre>';
					// print_r($this->categories);
					// echo '</pre>';
					?>
					<div style="border: 1px solid #CCC;height:300px; overflow:auto;">
						<ul id="navigation">
						<?php
							foreach($this->categories as $cat){
								echo '<li><a class="catlink" id="'.$cat->data.'" href="javascript:void(0);"><span>'.$cat->title.'</span></a></li>';
							}
						?>
						</ul>
					</div>
			</div>
			<div id="menu-item">
				<?php echo JText::_('SHOWLIST_POPUP_IMAGE_MENU_ITEMS');?><br />
				<div id="article-list" style="border: 1px solid #CCC;height:300px; overflow:auto;">
				</div>
			</div>
			<div class="clr"></div>
		</div>
		<div style="align:center !important;margin-left: 300px; padding: 10px;">
			<input type="button" disabled="disabled" id="savelink" class="btn" value="Save">
			<input class="btn" type="button" value="Cancel" name="Cancel" id="bt_close_popup2" />
		</div>
	</div>
	</div>
</div>
</div>
<div id="dialogboxdetailimage">
</div>
<div>
<input type="hidden" value="" id="linkchild" name="linkchild" />
</div>
<div class="contextMenu" id="sourceimage_contextmenu" style="display:none;">
	<ul>
		<span class="gutterLine"></span>
		<li id="selectallimage"><a><?php echo JText::_('CONTEXT_MENU_JSN_IS_SELECT_ALL_IMAGES', true); ?></a></li>
		<li id="deselectall"><a><?php echo JText::_('CONTEXT_MENU_JSN_IS_DESELECT_ALL', true); ?></a></li>
		<li id="revertselection"><a><?php echo JText::_('CONTEXT_MENU_JSN_IS_REVERT_SELECTION', true); ?></a></li>
	</ul>
</div>

<div class="contextMenu" id="showlist_menucontext" style="display:none;">
	<ul>
		<div class="gutterLine"></div>
		<li id="selectallimage"><a><?php echo JText::_('CONTEXT_MENU_JSN_IS_SELECT_ALL_IMAGES', true); ?></a></li>
		<li id="deselectall"><a><?php echo JText::_('CONTEXT_MENU_JSN_IS_DESELECT_ALL', true); ?></a></li>
		<li id="revertselection"><a><?php echo JText::_('CONTEXT_MENU_JSN_IS_REVERT_SELECTION', true); ?></a></li>
		<li class="divider"></li>
		<li id="purgeabsoleteimage"><a><?php echo JText::_('CONTEXT_MENU_JSN_IS_PURGE_ABSOLETE_IMAGES', true); ?></a></li>
		<li id="resetselectedimagedetail"><a><?php echo JText::_('CONTEXT_MENU_JSN_IS_RESET_SELECTED_IMAGES_DETAILS', true); ?></a></li>
	</ul>
</div>
