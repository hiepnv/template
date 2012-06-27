<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: flex.php 8411 2011-09-22 04:45:10Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
?>
<div id="image_detail">
<div class="jsn-bootstrap">
<form name="editForm" method="post" action="" class="form-horizontal">
<?php
$sourceType =JRequest::getVar('sourceType');
$baseurl = ($sourceType=='external')?'':JURI::root();
if(count($this->image)>1){
?>
	<div id="edit-image-wrapper">
<?php
	for($i=0;$i<count($this->image);$i++){
?>
		<div class="edit-image-item">
			<div class="edit-image-container">
				<div>
					<img src="<?php echo $baseurl.$this->image[$i]->image_small;?>" name="image" />
				</div>
			</div>
			<div class="edit-image-description">
				<input type="text" name="title[]" id="video-title" value="<?php echo htmlspecialchars($this->image[$i]->image_title);?>" />
				<input type="hidden" name="originalTitle[]" value="<?php echo htmlspecialchars($this->image[$i]->image_title);?>"/>
				<textarea rows="3" id="video-description" name="description[]"><?php echo htmlspecialchars($this->image[$i]->image_description);?></textarea>
				<input type="hidden" name="originalDescription[]" value="<?php echo htmlspecialchars($this->image[$i]->image_description);?>"/>
				<input class="edit-image-link" id="image_link_<?php echo $this->image[$i]->image_id;?>" value="<?php echo $this->image[$i]->image_link;?>" name="image_link[]"/>
				<input type="hidden" name="originalLink[]" value="<?php echo $this->image[$i]->image_link;?>"/>
				<input class="btn btn-warning select-link-edit" type="button" name="<?php echo $this->image[$i]->image_id;?>" value="Select" /><br />
				<input type="hidden" name="imageID[]" value="<?php echo $this->image[$i]->image_id;?>" />
				<input type="hidden" name="image_extid[]" value="<?php echo $this->image[$i]->image_extid;?>" />
			</div>
			<div style="clear:both;"></div>
		</div>
<?php
	}
?>
	</div>
	<input type="hidden" name="numberOfImages" value="<?php echo count($this->image);?>"/>
	<input type="hidden" name="showlistID" value="<?php echo $this->image[0]->showlist_id ;?>" />
	<input type="hidden" name="option" value="com_imageshow" />
	<input type="hidden" name="controller" value="image" />
	<input type="hidden" name="task" value="apply" />
	<div class="button-container" style="text-align:center">
		<input class="btn btn-warning" type="submit" value="Save" name="Save" />
		<input class="btn" type="button" value="Cancel" name="Cancel" id="bt_close_popup" />
	</div>
<?php
}else{
?>
	<div class="jsn-image-details">
		<div class="img-box" align="center">
			<img style="max-height: 120px;" src="<?php echo $baseurl.$this->image->image_small;?>" name="image" />
		</div>
		<div>
			<b><?php echo JText::_('SHOWLIST_EDIT_IMAGE_TITLE');?></b><br />
			<input type="text" class="title" name="title" id="video-title" value="<?php echo htmlspecialchars($this->image->image_title);?>" />
			<input type="hidden" name="originalTitle" value="<?php echo htmlspecialchars($this->image->image_title);?>" />
		</div>
		<div>
			<b><?php echo JText::_('SHOWLIST_EDIT_IMAGE_DESCRIPTION');?></b><br />
			<textarea class="description" rows="3" id="video-description" name="description"><?php echo htmlspecialchars($this->image->image_description);?></textarea>
			<input type="hidden" name="originalDescription" value="<?php echo htmlspecialchars($this->image->image_description);?>" />
		</div>
		<div>
			<b><?php echo JText::_('SHOWLIST_EDIT_IMAGE_LINK');?></b><br />
			<input id="image_link" value="<?php echo $this->image->image_link;?>" name="link" style="width: 480px" />
			<input type="hidden" name="originalLink" value="<?php echo $this->image->image_link;?>" />
			<input class="btn btn-warning select-link-edit" type="button" name="" value="Select" /><br />
		</div>
	<input type="hidden" name="numberOfImages" value="1"/>
	<input type="hidden" name="option" value="com_imageshow" />
	<input type="hidden" name="controller" value="image" />
	<input type="hidden" name="task" value="apply" />
	<input type="hidden" name="imageID" value="<?php echo $this->image->image_id;?>" />
	<input type="hidden" name="image_extid" value="<?php echo $this->image->image_extid;?>" />
	<input type="hidden" name="showlistID" value="<?php echo $this->image->showlist_id ;?>" />
	<div class="button-container">
		<input class="btn btn-warning" type="submit" value="Save" name="Save" />
		<input class="btn" type="button" value="Cancel" name="Cancel" id="bt_close_popup" />
	</div>
	</div>
<?php }?>
</form>
</div>
</div>
