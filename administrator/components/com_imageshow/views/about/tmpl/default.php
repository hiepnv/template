<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default.php 13385 2012-06-18 11:29:33Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
JHTML::_('behavior.modal','a.jsn-modal');
JToolBarHelper::title( JText::_('JSN_IMAGESHOW').': '.JText::_( 'ABOUT_ABOUT' ), 'about' );
$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
$objJSNUtils->callJSNButtonMenu();
$edition 	= @$this->infoXmlDetail['edition'];
$objJSNMsg 	= JSNISFactory::getObj('classes.jsn_is_displaymessage');
$explodeEdition =  explode(' ', $edition);
echo $objJSNMsg->displayMessage('ABOUT');
?>
<div id="jsn-imageshow-about">
	<div class="jsn-product-about jsn-bootstrap">
		<div class="jsn-product-intro">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="10" valign="top"><div class="jsn-product-thumbnail"><?php echo JHTML::_('image.administrator', 'components/com_imageshow/assets/images/product-thumbnail.png',''); ?></div></td>
					<td align="left">
						<h2><?php echo JText::_('JSN') .' '. @$this->componentData->name.'&nbsp;'.strtoupper($this->edition); ?>
						<?php if (strtolower($this->edition) == 'free') { ?>
							<a href="index.php?option=com_imageshow&controller=upgrader" class="link-button head-link">
								<span class="icon-upgrade-to-pro"><?php echo JText::_('ABOUT_UPGRADE_NOW'); ?></span>
							</a>
						<?php }?>
						</h2>
						<?php if(strtolower($this->edition) == 'pro standard')
						{
						?>
							<p class="jsn-message-upgrade"><?php echo JText::_('ABOUT_UPGRADE_TO_UNLIMITED'); ?></p>
						<?php
						}
						elseif (strtolower($this->edition) == 'free')
						{
						?>
							<p class="jsn-message-upgrade"><?php echo JText::_('ABOUT_UPGRADE_TO_PRO'); ?></p>
						<?php
						}
						?>
						<div style="clear: both;"></div>
						<hr />

						<dl>
							<dt><?php echo JText::_('ABOUT_VERSION'); ?>:</dt><dd><strong class="jsn-current-version"><?php echo @$this->componentData->version; ?></strong>&nbsp;-&nbsp;<span id="jsn-check-version-result"></span></dd>
							<dt><?php echo JText::_('ABOUT_AUTHOR'); ?>:</dt><dd><a href="http://<?php echo @$this->componentData->authorUrl; ?>"><?php echo @$this->componentData->author; ?></a></dd>
							<dt><?php echo JText::_('ABOUT_COPYRIGHT'); ?>:</dt><dd><?php echo @$this->componentData->copyright; ?></dd>
						</dl>
					</td>
				</tr>
			</table>
		</div>
		<div class="jsn-product-cta">
			<div style="float: left; width: 75%">
				<ul class="list-horizontal">
					<li>

						<a href="http://www.joomlashine.com/joomla-extensions/jsn-imageshow-on-jed.html" target="_blank" class="btn">
							<i class="icon-comment"></i> <?php echo JText::_('ABOUT_VOTE_FOR_THIS_PRODUCT_ON_JED'); ?>
						</a>

					</li>
					<li><a class="btn jsn-modal" target="_blank" href="http://www.joomlashine.com/free-joomla-templates-promo.html" rel="{handler: 'iframe', size: {x: 640, y: 510}}"><i class="icon-briefcase"></i> <?php echo JText::_('ABOUT_SEE_OTHER_PRODUCTS');?></a></li>
				</ul>
			</div>
			<div style="float: right; width: 24%; text-align: right">
			<ul class="list-horizontal">
				<li>
					<a href="http://www.facebook.com/joomlashine" class="link-icon" title="<?php echo htmlspecialchars(JText::_('ABOUT_CONNECT_WITH_US_ON_FACEBOOK')); ?>" target="_blank">
						<img src="components/com_imageshow/assets/images/icon-uni-24/icon-facebook.png" width="24" height="24" alt="<?php echo htmlspecialchars(JText::_('ABOUT_CONNECT_WITH_US_ON_FACEBOOK')); ?>" />
					</a>
				</li>
				<li>
					<a href="http://twitter.com/joomlashine" class="link-icon" title="<?php echo htmlspecialchars(JText::_('ABOUT_FOLLOW_US_ON_TWITTER')); ?>" target="_blank">
						<img src="components/com_imageshow/assets/images/icon-uni-24/icon-twitter.png" width="24" height="24" alt="<?php echo htmlspecialchars(JText::_('ABOUT_FOLLOW_US_ON_TWITTER')); ?>" />
					</a>
				</li>
			</ul>
			</div>
			<div class="clearbreak"></div>
		</div>
	</div>
	<?php
		echo JText::_('ABOUT_COMPONENT_DESCRIPTION_PRO');
	?>
</div>
