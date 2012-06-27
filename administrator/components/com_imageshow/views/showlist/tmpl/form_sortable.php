<?php
	defined('_JEXEC') or die('Restricted access');
	$task 		= JRequest::getWord('task','','post');
	$showlistID = JRequest::getVar('cid');
	$showlistID = $showlistID[0];
	$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
	$baseURL 	= $objJSNUtils->overrideURL();
	$url 		= $baseURL.'components/com_imageshow/assets/swf';
	$objJSNFlex = JSNISFactory::getObj('classes.jsn_is_flex');
	$token 		= $objJSNFlex->getToken();
	$user 		= JFactory::getUser();

	$showlistTable = JTable::getInstance('showlist', 'Table');
	$showlistTable->load($showlistID);
	if ($showlistTable->image_source_name != '') {
		$this->imageSource->loadScript();
		$sync = $this->imageSource->getShowlistMode();
	}else{
		$sync = false;
	}
	if ($this->selectMode != ''){
			$selectMode = ' '.$this->selectMode;
	}else{
		$selectMode	='';
	}
?>
<script type="text/javascript">
	JSNISImageShow.checkThumbCallBack = function()
	{
		$("showlist-videos").style.display = "block";
		//JSNISImageShow.initShowlist();
	}

	window.addEvent('domready', function()
	{
		//JSNISImageShow.loadCookieSettingFlex();
		JSNISImageShow.getScriptCheckThumb(<?php echo (int) $showlistID; ?>);
	});
</script>
<div class="jsn-showlist-video" id="showlist-video-layout">
	<div class="ui-layout-west" id="panel-west">
		<div class="sourcevideo-header"><h3><?php echo JText::_('SHOWLIST_SHOWLIST_SOURCE_IMAGES'); ?></h3></div>
		<div class="sourcevideo-panel-container">
			<div class="panel-right width-70">
				<div class="panel-header" id="source-video-header">
					<button class="video-show-grid active" title="<?php echo JText::_('SHOWLIST_IMAGE_MANAGER_SELECT_THUMB_PRESENTATION_MODE'); ?>"></button>
					<button class="video-show-list" title="<?php echo JText::_('SHOWLIST_IMAGE_MANAGER_SELECT_DETAILS_PRESENTATION_MODE'); ?>"></button>
					<button class="video-move" id="move-selected-video-source" title="<?php echo JText::_('SHOWLIST_IMAGE_MANAGER_ADD_SELECTED_IMAGE'); ?>"></button>
				</div>
				<div class="clr"></div>
				<div class="sourcevideo-container" id="sourcevideo-container">
					<div class="videos showgrid" id="source-images">
					</div>
			   </div>
			</div>

			<?php

			$imageFolder = $this->imageSource->getCategories();
			$folderlists = json_decode(json_encode((array) simplexml_load_string('<nodes>'.$imageFolder.'</nodes>')),1);
			$imageFolder  =  @$this->imageSource->convertXmlToTreeMenu($folderlists['node'],$this->catSelected);

			//echo '<pre>'.print_r($folderlists).'</pre>';
			//$imageFolder  =  $imageSource->convertXmlToTreeMenu($folderlists);
			?>
			<div class="panel-left width-30">
				<div class="panel-header">
					<h3><?php echo $showlistTable->image_source_name;?></h3>
				</div>
				<div class="sourcevideo-container">
					<div class="jsn-header-tree-control" id="jsn-header-tree-control">
						<button class="expand"></button>
						<button class="collapse"></button>
						<?php if($showlistTable->image_source_type != 'external'):?>
						<button class="sync <?php if ($sync=='sync'){ echo 'active';}?>"></button>
						<?php endif;?>
					</div>
					 <div class="jsn-jtree" id="jsn-jtree-categories">
						<ul>
							<?php echo @$imageFolder;?>
						</ul>
					</div>
				</div>
			</div>
			<div class="clr"></div>
		</div>
	</div>


	<div class="ui-layout-center" id="panel-center">
		<div class="sourcevideo-header"><h3><?php echo JText::_('SHOWLIST_SHOWLIST_S_IMAGES');?></h3></div>
		<div class="sourcevideo-panel-container">
		<div class="panel-left width-100">
			<div class="panel-header" id="showlist-video-header">
				<button class="video-show-grid active" title="<?php echo JText::_('SHOWLIST_IMAGE_MANAGER_SELECT_THUMB_PRESENTATION_MODE'); ?>"></button>
				<button class="video-show-list" title="<?php echo JText::_('SHOWLIST_IMAGE_MANAGER_SELECT_DETAILS_PRESENTATION_MODE'); ?>"></button>
				<button class="delete-video" id="delete-video-showlist"  <?php if ($sync=='sync'){ echo 'style="display:none;"';}?> title="<?php echo JText::_('SHOWLIST_IMAGE_MANAGER_DELETE_SELECTED_IMAGE'); ?>"></button>
				<button class="edit-video" id="edit-video-showlist"  <?php if ($sync=='sync'){ echo 'style="display:none;"';}?> title="<?php echo JText::_('SHOWLIST_IMAGE_MANAGER_EDIT_SELECTED_IMAGE'); ?>"></button>
			</div>
			<div class="sourcevideo-container" id="showlistvideo-container">
				<?php
					$config = array('showlist_id'=>$showlistID);
					if(trim($selectMode)=='sync')
					{
						$imagesStored = $this->imageSource->getSyncImages($config);
					}
					else
					{
						$imagesStored = $this->imageSource->getImages($config);
					}
				?>
				<div class="videos showlist-videos showgrid <?php if (count($imagesStored) == 0){ echo 'empty-video'; }?>" id="showlist-videos">

				<?php
				if ( count($imagesStored) > 0 ){
					foreach ($imagesStored as $image)
					{
						$image = (array) $image;
						$processedImage = array(
								'image_id'			=> (string) $image['image_id'],
								'image_title'		=> (string) $image['image_title'],
								'image_small'		=> (string) $image['image_small'],
								'image_medium'		=> (string) $image['image_medium'],
								'image_big'			=> (string) $image['image_big'],
								'image_description'	=> (string) $image['image_description'],
								'image_link'		=> (string) $image['image_link'],
								'image_extid'		=> (string) $image['image_extid'],
								'album_extid'		=> (string) $image['album_extid'],
								'image_size'		=> (string) $image['image_size'],
								'custom_data'		=> (string) $image['custom_data'],
								'exif_data'			=> (string) $image['exif_data'],
								'original_title'	=> (string) $image['original_title'],
								'original_description'		=> (string) $image['original_description'],
								'original_link'		=> (string) $image['original_link']);
						?>
						<div class="video-item" id="<?php echo urlencode($image['image_extid']);?>">
								<input type="hidden" value="<?php echo $image['original_link']; ?>" id="linkcheck" name="linkcheck" />
								<input class="img_extid" type="hidden" value="<?php echo urlencode($image['album_extid']);?>">
								<input class="img_detail" type="hidden" value="<?php echo htmlspecialchars(json_encode($processedImage), ENT_COMPAT, 'UTF-8');?>" />
								<div class="video-index">&nbsp;</div>
								<?php echo (isset($image['custom_data']) && $image['custom_data']==1)?'<div class="modified"></div>':'';?>
								<div class="video-thumbnail loaded">
									<a class="image_link" title="<?php echo $image['image_title'];?>">
										<?php
										$baseurl = ($showlistTable->image_source_type=='external')?'':JURI::root();
										?>
										<img src="<?php echo  $baseurl.$image['image_small'];?>" style="max-height:60px; max-width: 80px;"/>
									</a>
								</div>
								<div id="<?php echo $image['album_extid'];?>"  class="video-info">
									<p><b><?php echo $image['image_title'];?></b></p>
									<p>
									<?php
										if ( strlen($image['image_description']) > 100 ){
											$i = 99;
											while( $image['image_description'][$i] != ' ' && $i < strlen($image['image_description']) - 1){
												$i++;
											}
											echo substr($image['image_description'], 0, $i).'...';
										}else{
											echo $image['image_description'];
										}
									?>
									</p>
									<p><?php echo $image['image_link'];?></p>
								</div>
								<div class="clr"></div>
							</div>
						<?php
					}
				}else{
					?>
						<div class="showlist-drag-drop-video-notice">
							<?php echo JText::_('IMAGE_SHOWLIST_NOTICE_DRAG_AND_DROP');?>
						</div>
					<?php
				}
				?>
				<div class="clr"></div>
				</div>
			</div>
		</div>
		<div class="clr"></div>
	</div>
</div>

<input type="hidden" value="" id="start" name="start" />
<input type="hidden" value="" id="stop" name="stop" />
<input type="hidden" value="" id="start_image_showlist" name="start" />
<input type="hidden" value="" id="stop_image_showlist" name="stop" />
</div>