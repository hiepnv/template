<?php
defined('_JEXEC') or die('Restricted access');
$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
$baseURL 	= $objJSNUtils->overrideURL();
$showlistTable = JTable::getInstance('showlist', 'Table');
$showlistID = JRequest::getVar('showListID');
$showlistTable->load($showlistID);
$baseurl = '';
if(isset($images->images)){
	$totalimage = count($images->images);
	if ( $totalimage > 0){
		if ( $selectMode != ''){
			$selectMode = ' '.$selectMode;
		}
		$i = 1;
		foreach ($images->images as $image)
		{
			$image = (array) $image;
			$checked 							= $this->imageSource->checkImageSelected($image['image_extid']);
			//$checked  = 1;
			$imageObj['isSelected']				= $checked;
			if ( $checked){
				$itemClass = 'image-item-is-selected';
			}else{
				$itemClass = 'video-item';
			}
			?>
			<div class="<?php echo $itemClass;?><?php echo $selectMode;?>" id="<?php echo $image['image_extid'];?>">
				<?php
				$imageinfo = array(
								'image_id'			=> $image['image_extid'],
								'image_extid'		=> $image['album_extid'],
								'image_small'		=> $image['image_small'],
								'image_medium'		=> $image['image_medium'],
								'image_big'			=> $image['image_big'],
								'image_link'		=> $image['image_link'],
								'album_extid'		=> $image['album_extid'],
								'image_description'	=> $image['image_description'],
									);
				?>
				<div class="image_extid" name="image_extid" id="cat_<?php echo $image['album_extid'];?>"></div>
				<input class="img_detail" type="hidden" value="<?php echo  urlencode(json_encode($imageinfo));?>" />
				<div class="video-index"><?php echo $i.'/'.$totalimage;?>
					<?php if ( $selectMode != 'sync') {?>
						<button class="move-to-showlist">&nbsp;</button>
					<?php }?>
				</div>
				<div class="video-thumbnail">
					<a title="<?php echo $image['image_title'];?>">
						<?php
							$baseurl = ($showlistTable->image_source_type == 'external')?'':JURI::root();
						?>
						<img src="<?php echo $baseurl.$image['image_small'];?>" width="80" style="max-height:60px; max-width: 80px;" alt="image thumbnail"/>
					</a>
				</div>
				<div id="video-info-<?php echo $image['image_extid'];?>" class="video-info">
					<p><?php echo $image['image_title'];?></p>
					<p>
					<?php
						if ( strlen($image['image_description']) > 100 ){
							$i = 99;
							while($image['image_description'][$i] != ' ' && $i < strlen($image['image_description']) - 1){
								$i++;
							}
							echo substr($image['image_description'], 0, $i).'...';
						}else{
							echo $image['image_description'];
						}
					?>
					</p>
				</div>
				<?php
				//echo $image['isSelected'];
				if ( $checked){
					?>
						<div class="clr"></div>
						<div class="img-mark-isselected">&nbsp;</div>
					<?php
				}
				?>
			</div>
			<?php
			$i++;
		}
	}elseif($selectMode !='sync'){
		?>
			<div class="video-no-found">
				No Images found
			</div>
		<?php
	}
	?>
	<div class="clr"></div>
<?php } ?>
