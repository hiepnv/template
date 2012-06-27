<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: footer.php 13274 2012-06-13 04:19:51Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
global $mainframe, $option, $componentVersion;
$doc = JFactory::getDocument();
$doc->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_imageshow.js?v='.$componentVersion);
$doc->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_checkupdate.js?v='.$componentVersion);
$objJSNUtils  		= JSNISFactory::getObj('classes.jsn_is_utils');
$objJSNXML 	  		= JSNISFactory::getObj('classes.jsn_is_readxmldetails');
$objJSNJSON     	= JSNISFactory::getObj('classes.jsn_is_json');
$shortEdition 		= $objJSNUtils->getEdition();

$componentInfo 		= $objJSNUtils->getComponentInfo();
$componentData = $objJSNJSON->decode($componentInfo->manifest_cache);
$componentVersion = trim($componentData->version);

$exts 				= array();
$exts[] 			= "{name: '".strtolower(@$componentData->name)."', id: '".strtolower(@$componentData->name)."', version: '".$componentVersion."', edition: '".strtolower($shortEdition)."'}";
$modelThemePlugin	= JModel::getInstance('plugins', 'imageshowmodel');
$themeItems			= $modelThemePlugin->getFullData();

if (count($themeItems))
{
	for($i = 0, $count = count($themeItems); $i < $count; $i++)
	{
		$themeItem = $themeItems[$i];
		$exts[] = "{name: '".strtolower($themeItem->name)."', id: '".strtolower($themeItem->element)."', version: '".$themeItem->version."', edition:''}";
	}
}

$objJSNSource = JSNISFactory::getObj('classes.jsn_is_source');
$listSource = $objJSNSource->getListSources();

foreach ($listSource as $source)
{
	if ($source->identified_name != 'folder') {
		$manifestCachce = json_decode($source->pluginInfo->manifest_cache);
		$exts[] = "{name: '".strtolower($source->title)."', id: '".strtolower($source->identified_name)."', version: '".$manifestCachce->version."', edition:''}";
	}
}


$doc->addScriptDeclaration("
		var jsn_checked_extensions = [ ".implode( ', ', $exts )." ];

		window.addEvent( 'domready', function() {
			JSNISCheckUpdate.load_extensions('".JText::_('FOOTER_SEE_UPDATE_INSTRUCTIONS', true)."');
		});

	");
?>
<div id="jsn-footer">
	<div>
		<ul class="jsn-footer-menu">
			<li>
				<a target="_blank" href="http://www.joomlashine.com/joomla-extensions/jsn-imageshow-docs.zip">
					<?php echo JText::_('FOOTER_DOCUMENTATION');?></a>
			</li>
			<li>
				<a target="_blank" href="http://www.joomlashine.com/contact-us/get-support.html">
					<?php echo JText::_('FOOTER_SUPPORT')?></a>
			</li>
			<li>
				<a target="_blank" href="http://www.joomlashine.com/joomla-extensions/jsn-imageshow-on-jed.html">
					<?php echo JText::_('FOOTER_VOTE_ON_JED')?></a>
			</li>
			<li class="last">
                <strong><?php echo JText::_('FOOTER_FOLLOW_US'); ?></strong>&nbsp;&nbsp;
				<a href="http://www.facebook.com/joomlashine" target="_blank"><img src="components/com_imageshow/assets/images/icon-uni-16/icon-facebook-16.png" width="16" height="16" alt="<?php echo htmlspecialchars(JText::_('FOOTER_FIND_US_ON_FACEBOOK')); ?>" /></a>&nbsp;&nbsp;
				<a href="http://twitter.com/joomlashine" target="_blank"><img src="components/com_imageshow/assets/images/icon-uni-16/icon-twitter-16.png" width="16" height="16" alt="<?php echo htmlspecialchars(JText::_('FOOTER_FOLLOW_US_ON_TIWTTER')); ?>" /></a>
            </li>
		</ul>
	</div>
	<div>
		<ul class="jsn-footer-menu">
			<li id="jsn-footer-version-info" class="<?php echo (($shortEdition != 'free' || $shortEdition != 'pro standard') ? 'last' : ''); ?>">
				<?php
				echo '<a href="http://www.joomlashine.com/joomla-extensions/jsn-imageshow.html" target="_blank">JSN '. @$componentData->name . ' ' .strtoupper($shortEdition). ' v'.@$componentData->version.'</a> ';
				echo JText::_('FOOTER_BY') . ' <a target="_blank" href="'.((stripos(@$componentData->authorUrl, 'http') !== false) ? @$componentData->authorUrl : 'http://'.@$componentData->authorUrl).'">' . @$componentData->author. '</a>';
				?>
			</li>
			<li style="display: none;" id="jsn-global-check-version-result" class="<?php echo (($shortEdition != 'free' || $shortEdition != 'pro standard') ? 'last' : '')?>"></li>
			<?php if($shortEdition == 'free' || $shortEdition == 'pro standard'): ?>
				<li class="last jsn-text-attention">
					<a class="link-item link-pro" href="index.php?option=com_imageshow&controller=upgrader">
					<strong class="jsn-text-attention"><?php echo ($shortEdition == 'free')?JText::_('FOOTER_UPGRADE_TO_PRO'):JText::_('FOOTER_UPGRADE_TO_PRO_UNLIMITED');?></strong></a>
				</li>
			<?php endif;?>
		</ul>
	</div>
</div>
