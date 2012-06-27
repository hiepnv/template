<style>
	#categories{
		width: 300px;
		float: left;
	}
	#article-cate{
		width: 300px;
		float: left;
	}
	#menu-list{
		width: 300px;
		float: left;
	}
	#menu-item{
		width: 300px;
		float: left;
		margin-left: 5px;
	}
	.clr{
		clear: both;
	}
</style>

<div class="demo" style="min-width: 300px; min-height: 300px;">

<div style="border: none !important;" id="tabs">
	<ul style="background: none !important;">
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
	<div style="align:center !important;margin-left: 300px">
		<button class="buttonpopup-disable" disabled="disabled" id="savelink">Save</button>
		<input class="buttonpopup" type="button" value="Cancel" name="Cancel" id="bt_close_popup2" />
	</div>
</div>
</div>