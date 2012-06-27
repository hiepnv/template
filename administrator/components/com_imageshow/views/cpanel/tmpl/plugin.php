<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: plugin.php 11281 2012-02-19 14:05:48Z thangbh $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
$showlistID = JRequest::getInt('showlist_id');
$showcaseID = JRequest::getInt('showcase_id');
$pluginInfo = $this->pluginContentInfo;
$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
$baseURL 	= $objJSNUtils->overrideURL();
$url 		= $baseURL.'components/com_imageshow/assets/swf';
?>
<script type="text/javascript">
	var clipboard = null;

	window.addEvent('domready', function()
	{
		ZeroClipboard.moviePath = "<?php echo $url; ?>/ZeroClipboard.swf";
		clipboard 		= new ZeroClipboard.Client();
		syntaxPlugin 	= $('syntax-plugin');

		clipboard.addEventListener('complete', function (client, text)
		{
			if (syntaxPlugin.value != '')
			{
				var checkIcon = $$('.jsn-clipboard-checkicon')[0];
				checkIcon.addClass('jsn-clipboard-coppied');
				(function() { checkIcon.removeClass('jsn-clipboard-coppied');  } ).delay(2000);
			}
		});

		clipboard.glue('jsn-clipboard-button', 'jsn-clipboard-container');

		clipboard.setText(syntaxPlugin.value);

		syntaxPlugin.addEvent('change', function(){
			clipboard.setText(syntaxPlugin.value);
		});
	});
</script>
<div class="jsn-plugin-details">
<h3 class="jsn-element-heading"><?php echo JText::_('CPANEL_PLUGIN_SYNTAX_DETAILS'); ?></h3>
<?php
echo JText::_('CPANEL_PLEASE_INSERT_FOLLOWING_TEXT_TO_YOUR_ARTICLE_AT_THE_POSITION_WHERE_YOU_WANT_TO_SHOW_GALLERY');
?>
<div id="jsn-clipboard">
	<div class="jsn-clipboard-input">
		<input type="text" id="syntax-plugin" class="jsn-readonly" name="plugin" value="{imageshow sl=<?php echo $showlistID; ?> sc=<?php echo $showcaseID; ?> /}" />
		<span class="jsn-clipboard-checkicon"></span>
	</div>
	<div id="jsn-clipboard-container">
		<div id="jsn-clipboard-button" class="link-button"><?php echo JText::_('CPANEL_COPY_TO_CLIPBOARD')?></a>
	</div>
	<div class="clearbreak"></div>
</div>

<?php
echo JText::_('CPANEL_MORE_DETAILS_ABOUT_PLUGIN_SYNTAX');
?>
</div>
