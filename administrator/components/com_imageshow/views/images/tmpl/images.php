<?php
defined('_JEXEC') or die('Restricted access');
?>
<?php
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
			$image 	 		= (array) $image;
			$processedImage = array(
								'image_id'			=> (string) $image['image_extid'],
								'image_extid'		=> (string) $image['album_extid'],
								'image_small'		=> (string) $image['image_small'],
								'image_medium'		=> (string) $image['image_medium'],
								'image_big'			=> (string) $image['image_big'],
								'image_link'		=> (string) $image['image_link'],
								'album_extid'		=> (string) $image['album_extid'],
								'image_description'	=> (string) $image['image_description'],
								'image_title'		=> (string) $image['image_title']);
			$checked = $imageSource->checkImageSelected($image['image_extid']);
			//$imageObj['isSelected']				= $checked;
			if ($checked || $syncIsSelected)
			{
				$itemClass = 'image-item-is-selected';
			}
			else
			{
				$itemClass = 'video-item';
			}
			?>
			<div class="<?php echo $itemClass;?><?php echo $selectMode;?>" id="<?php echo urlencode($image['image_extid']);?>">
				<?php
				/*$image_source_type = $showlistTable->image_source_type;
				if($image_source_type == 'external'){
				//if($image_source_type != 'folder'){
					$inforimage  = $imageSource->getOriginalInfoImages(array('album_extid'=>$image['album_extid'], 'image_extid'=>array('0'=>$image['image_extid'])));
					$imageinfo = array(
								'image_id'			=> $image['image_extid'],
								'image_extid'		=> $image['album_extid'],
								'image_small'		=> $image['image_small'],
								'image_medium'		=> $image['image_medium'],
								'image_big'			=> $image['image_big'],
								'image_link'		=> $inforimage[0]->link,
								'album_extid'		=> $image['album_extid'],
								'image_description'	=> $inforimage[0]->description,
								'image_title'		=> $inforimage[0]->title
									);
				}else{
					if($image_source_type == 'folder'){
						$root = JURI::root();
					}else {
						$root = '';
					}
					$imageinfo = array(
							'image_id'			=> $image['image_extid'],
							'image_extid'		=> $image['album_extid'],
							'image_small'		=> $image['image_small'],
							'image_medium'		=> $image['image_medium'],
							'image_big'			=> $image['image_big'],
							'image_link'		=> $root.$image['image_link'],
							'album_extid'		=> $image['album_extid'],
							'image_description'	=> $image['image_description'],
							'image_title'		=> $image['image_title']
								);
				}*/

				?>
				<input class="img_extid" type="hidden" value="<?php echo urlencode($image['album_extid']);?>" />
				<input class="img_detail" type="hidden" value="<?php echo htmlspecialchars(json_encode($processedImage), ENT_COMPAT, 'UTF-8');?>" />
				<div class="video-index"><?php echo $i.'/'.$totalimage;?>
					<?php if ( $selectMode != 'sync') {?>
						<button class="move-to-showlist">&nbsp;</button>
					<?php }?>
				</div>
				<div class="video-thumbnail">
					<a class="image_link" title="<?php echo $image['image_title'];?>">
						<?php
							$baseurl = ($showlistTable->image_source_type == 'external')?'':JURI::root();
						?>
						<img src="<?php echo $baseurl.$image['image_small'];?>" style="max-height:60px; max-width: 80px;" alt=""/>
					</a>
				</div>
				<div id="video-info-<?php echo $image['image_extid'];?>" class="video-info">
					<p><b><?php echo $image['image_title'];?></b></p>
					<p>
					<?php
						if (strlen((string) $image['image_description']) > 100)
						{
							$i = 99;
							while($image['image_description'][$i] != ' ' && $i < strlen($image['image_description']) - 1)
							{
								$i++;
							}
							echo substr($image['image_description'], 0, $i).'...';
						}
						else
						{
							echo $image['image_description'];
						}
					?>
					</p>
					<p><?php echo @$image['image_link'];?></p>
				</div>
				<?php
				if ( $checked || $syncIsSelected ){
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
	}else{
	?>
		<div class="video-no-found">
			<?php echo 'Showlist is in Sync mode';?>
		</div>
	<?php } ?>
	<div class="clr"></div>
<?php } ?>
