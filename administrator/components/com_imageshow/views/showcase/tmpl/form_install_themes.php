<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: form_install_themes.php 11579 2012-03-07 04:21:07Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
JHTML::script('jsn_is_contentclip.js', 'administrator/components/com_imageshow/assets/js/');
$objJSNTheme	  = JSNISFactory::getObj('classes.jsn_is_themes');
$objJSNUtils	  = JSNISFactory::getObj('classes.jsn_is_utils');
$objJSNLightCart  = JSNISFactory::getObj('classes.jsn_is_lightcart');
$errorCode		  = $objJSNLightCart->getErrorCode('customer_verification');
$baseURL 		  = $objJSNUtils->overrideURL();
$lists	     	  = $this->needInstallList;
$random			  = uniqid('').rand(1, 99);
$divTabID         = 'mod-jsncc-sliding-tab-new-theme'.$random;
$moduleID         = 'mod-jsncc-container-new-theme'.$random;
$buttonPreviousID = 'mod-jsncc-button-previous-new-theme'.$random;
$buttonNextID     = 'mod-jsncc-button-next-new-theme'.$random;
$colStyle 		  = null;
$itemPerSlide 	  = 3;
$uri			  = JFactory::getURI();
$return 		  = base64_encode($uri->toString());
if(count($lists))
{
	$modContentClipsSlidingTab = 'modContentClipsSlidingTabNewTheme'.$random;
?>
<style>#toolbar-save,#toolbar-apply{display:inline;}</style>
<div class="jsn-showcase-install-themes">
<h3 class="jsn-element-heading"><?php echo JText::_('SHOWCASE_INSTALL_NEW_THEME'); ?></h3>
<div id="<?php echo $moduleID; ?>">
<?php if (count($lists) > $itemPerSlide) { ?>
<script type="text/javascript" charset="utf-8">
	window.addEvent('domready', function () {
		var <?php echo $modContentClipsSlidingTab; ?> = new JSNISContentClip('', '<?php echo $divTabID; ?>', '<?php echo $buttonPreviousID; ?>', '<?php echo $buttonNextID; ?>', {slideEffect: {duration: 300}});
		$('<?php echo $buttonPreviousID; ?>').addEvent('click', <?php echo $modContentClipsSlidingTab; ?>.previous.bind(<?php echo $modContentClipsSlidingTab; ?>));
		$('<?php echo $buttonNextID; ?>').addEvent('click', <?php echo $modContentClipsSlidingTab; ?>.next.bind(<?php echo $modContentClipsSlidingTab; ?>));
		window.addEvent('resize', <?php echo $modContentClipsSlidingTab; ?>.recalcWidths.bind(<?php echo $modContentClipsSlidingTab; ?>));
	});
</script>
<?php } ?>
<div class="jsn-showlist-source-slide jsn-showlist-source-classic-bright">
	<div class="navigation-button clearafter">
		<span id="<?php echo $buttonPreviousID; ?>" class="jsn-showlist-source-slide-arrow <?php echo (count($lists) > $itemPerSlide)?'slide-arrow-pre':'';?>"></span>
		<span id="<?php echo $buttonNextID; ?>" class="jsn-showlist-source-slide-arrow <?php echo (count($lists) > $itemPerSlide)?'slide-arrow-next':'';?>"></span>
	</div>
	<div id="<?php echo $divTabID; ?>" class="sliding-content">

    	<div>
        <?php
            $index = 0;
            $j	   = 0;
            $itemLayout = 'horizontal';

            if(count($lists) < $itemPerSlide)
            {
                $itemPerSlide = count($lists);
            }
            if($itemLayout == 'horizontal')
            {
	            $objContentClip = JSNISFactory::getObj('classes.jsn_is_contentclip');
				$colStyle		= $objContentClip->calColStyle($itemPerSlide);
            }

			$countLists = count($lists);
            for($i = 0; $i < $countLists; $i++)
            {
                $rows = $lists[$i];
                $downloadElementID = 'jsn-showcasetheme-install-showcasetheme-process-'.$i;
				if ($i==$countLists-1 || ($index+1)%$itemPerSlide == 0) $itemOrderClass = ' last';
					else $itemOrderClass = '';

        ?>
        	<?php if($index%$itemPerSlide == 0) { $j = 0; ?>
        	<div class="sliding-pane <?php echo $itemLayout; ?> clearafter">
        	<?php } ?>
        		<div class="jsn-item jsn-item<?php echo $colStyle[$j]['class']; ?><?php echo $itemOrderClass; ?>" style="width:<?php echo $colStyle[$j]['width']; ?>">
					<?php
						$objInfoUpdate = new stdClass();
						$objInfoUpdate->identify_name 		= $rows->identified_name;
						$objInfoUpdate->edition 			= '';
						$objInfoUpdate->update 				= false;
						$objInfoUpdate->install 			= true;
						$objInfoUpdate->error_code 			= $errorCode;
						$objInfoUpdate->wait_text 			= JText::_('SHOWCASE_INSTALL_THEME_WAIT_TEXT', true);
						$objInfoUpdate->process_text 		= JText::_('SHOWCASE_INSTALL_THEME_PROCESS_TEXT', true);
						$objInfoUpdate->download_element_id	= $downloadElementID;
						$objInfoUpdate = json_encode($objInfoUpdate);
						$addHTML = '';
						$itemClass = ' jsn-item-container ';
						if (@$rows->authentication != true)
						{
							$actionLink  = 'javascript:void(0);';
							$actionClass = ' jsn-showcase-theme-install ';
							$actionRel 	= '';
							$onclick 	= ' onclick="JSNISInstallShowcaseThemes.install(this, '.$this->escape($objInfoUpdate).'); return false;" ';

							if (!$this->canAutoDownload) {
								$actionLink  = 'index.php?option=com_imageshow&controller=installer&task=manualInstall&layout=form_manual_install&identify_name='.$rows->identified_name.'&name='.$rows->name.'&tmpl=component&type=theme';
								$actionClass = ' jsn-showcase-theme-install modal ';
								$actionRel 	 = '{handler: \'iframe\', size: {x: 450, y: 330}}';
								$onclick 	 = '';
							}
						}
						else
						{
							$actionLink 	= 'index.php?option=com_imageshow&controller=showcase&task=authenticate&layout=form_login&identify_name='.$rows->identified_name.'&tmpl=component&return='.$return;
							$actionClass 	= ' modal jsn-showcase-theme-install ';
							$actionRel 		= '{handler: \'iframe\', size: {x: 450, y: '.((count($rows->related_products) > 0) ? '500' : '300').'}}';
							$onclick 		= ' onclick="JSNISInstallShowcaseThemes.setOptions(this, '.$this->escape($objInfoUpdate).');" ';
						}
						$overlayTextClass = 'jsn-showcasetheme-install-overlay-install';
					?>
					<div class="jsn-item-inner<?php echo $itemClass;?>">
						<a href="<?php echo $actionLink; ?>" class="<?php echo $actionClass; ?>" <?php echo $onclick; ?> rel="<?php echo $actionRel; ?>">
							<img class="jsn-showcasetheme-install-thumb" src="<?php echo $rows->thumbnail ;?>"/>
							<div class="jsn-showcasetheme-install-overlay <?php echo $overlayTextClass; ?>">
								<span class="jsn-showcasetheme-install-loading"><img id="jsn-install-theme-ajax-loader-lite" src="<?php echo dirname($baseURL).'/administrator/components/com_imageshow/assets/images/ajax-loader-lite.gif';?>"/></span>
								<p class="jsn-showcasetheme-install-overlay-text jsn-showcasetheme-install-showcasetheme"><?php echo JText::_('SHOWCASE_INSTALL_THEME');?></p>
								<p id="<?php echo $downloadElementID;?>" class="jsn-showcasetheme-install-overlay-text jsn-showcasetheme-install-download"><?php echo JText::_('SHOWCASE_INSTALL_THEME_DOWNLOAD');?><br/><span></span></p>
								<p class="jsn-showcasetheme-install-overlay-text jsn-showcasetheme-install-installing"><?php echo JText::_('SHOWCASE_INSTALL_THEME_INSTALLING');?></p>
							</div>
						</a>
						<?php echo $addHTML; ?>
					</div>
					<div class="jsn-source-name">
					<?php
						echo ($rows->name) ? $rows->name : JText::_('N/A');
					?>
					</div>
        	    </div>
        	<?php
        	$index++;
        	if($index%$itemPerSlide == 0) {
        	?>
        		</div>
        	<?php
        	}
        	?>
        <?php
        		$j++;
            }
        ?>
        </div>
	</div>
</div>
<?php
if(count($lists)%3 != 0 && $itemPerSlide%3 == 0)
{
   echo '</div>';
}
?>
<?php
if(count($lists)%3 == 0 && $itemPerSlide%3 != 0 && $itemPerSlide != 1 )
{
  // echo '</div>';
}
?>
</div>
</div>
<?php
}
?>