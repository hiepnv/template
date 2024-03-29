<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: form_login.php 11278 2012-02-19 08:24:21Z thangbh $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
global $mainframe;

$manualRedirect = JRequest::getVar('install_manual_redirect', 0);
if ($manualRedirect) {
	echo "<script>window.top.location.reload(true); window.top.SqueezeBox.close();</script>";
	exit();
}

$return	= JRequest::getVar('return', '', 'get');
if (base64_decode($return) != @$_SERVER['HTTP_REFERER']) {
	$mainframe->redirect(base64_decode($return));
	return;
}
$objJSNSource     	= JSNISFactory::getObj('classes.jsn_is_source');
$objJSNUtils 		= JSNISFactory::getObj('classes.jsn_is_utils');
$datas 			  	= $objJSNSource->compareSources();
$data				= null;
$countDatas			= count($datas);
$identified_name	= JRequest::getVar('identify_name');
$objVersion			= new JVersion();
if ($countDatas && $identified_name != '')
{
	foreach ($datas as $value)
	{
		if ($value->identified_name == $identified_name)
		{
			$data = $value;
			break;
		}
	}
}

$params = JComponentHelper::getParams('com_languages');
$adminLang = $params->get('administrator', 'en-GB');
?>
<script>
	var canAutoDownload = <?php echo ($this->canAutoDownload) ? 'true' : 'false';?>;
	function submitLoginForm()
	{
		if (document.jsnInstallLoginForm.username.value == ''
			|| document.jsnInstallLoginForm.password.value == '')
		{
			alert('<?php echo JText::_('INSTALLER_LOGIN_REQUIRED_INPUT', true); ?>');
			return false;
		}
		if (canAutoDownload) {
			JSNISInstallImageSources.installCommercial('jsnInstallLoginForm', 'jsn-is-login-message', 'submit-customer-verification');
			return false;
		} else {
			var downloadLink = '<?php echo JSN_IMAGESHOW_AUTOUPDATE_URL; ?>';
			downloadLink += '&identified_name=<?php echo urlencode($data->identified_name);?>';
			downloadLink += '&edition=';
			downloadLink += '&joomla_version=<?php echo urlencode($objVersion->RELEASE);?>';
			downloadLink += '&based_identified_name=imageshow';
			downloadLink += '&username=' + encodeURI(document.jsnInstallLoginForm.username.value);
			downloadLink += '&password=' + encodeURI(document.jsnInstallLoginForm.password.value);
			downloadLink += '&language=<?php echo urlencode($adminLang); ?>';
			$('jsn-showlist-manual-install').style.display = 'block';
			$('jsn-showlist-install-sources-verify').style.display = 'none';
			$('jsn-manual-install-remote-frame').src = downloadLink;
			$('jsn-manual-install-remote-frame').style.width = '90%';
			$('jsn-manual-install-remote-frame').style.height = '25px';

			return false;
		}
	}
	window.addEvent('domready', function()
	{
		var login	= new JSNISAccordions('jsn-login-panel', {multiple: false, activeClass: 'down', showFirstElement: false, durationEffect: 300});
	});
</script>
<div id="jsn-showlist-install-sources-verify">
	<h3 class="jsn-element-heading"><?php echo JText::_('SHOWLIST_PROFILE_CUSTOMER_VERIFICATION')?></h3>
	<?php
		if (isset($data))
		{
			echo '<div class="jsn-related-products-info">';
			echo '<p>'.JText::sprintf('INSTALLER_TO_INSTALL_IMAGE_SOURCE_PLUGIN', $data->name).'</p>';
			echo '<ul><li>'.JText::sprintf('INSTALLER_PURCHASED_THE_PLUGIN_ITSELF', ($data->url) ? $data->url : '#').'</li></ul>';

			if (isset($data->related_products) && count($data->related_products))
			{
				echo '<p><strong>&nbsp;&nbsp;&nbsp;&nbsp;'.JText::_('INSTALLER_OR').'</strong></p>';
				echo '<ul><li>'.JText::_('INSTALLER_PURCHASED_ONE_OF_FOLLOWING_PRODUCTS').'</li>';

				foreach ($data->related_products as $product)
				{
					echo '<li class="jsn-related-product">'.$product->name.' '.JText::sprintf('INSTALLER_READ_MORE', ($product->url) ? $product->url : '#').'</li>';
				}

				echo '</ul>';
			}

			echo '</div>';
		}
	?>
	<span id="jsn-is-login-message"><?php echo JText::_('INSTALLER_LOGIN_DESCRIPTION')?></span>
	<div id="jsn-showlist-login-form">
		<div class="jsn-showlist-login-field-form">
			<form name="jsnInstallLoginForm">
				<table id="jsn-install-login-form" border="0">
					<tr>
						<td class="label" width="15%"><?php echo JText::_('INSTALLER_USERNAME'); ?></td>
						<td width="73%"><input type="text" name="username"/></td>
					</tr>
					<tr>
						<td class="label"><?php echo JText::_('INSTALLER_PASSWORD'); ?></td>
						<td><input type="password" name="password"/></td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<div class="jsn-button-container">
								<button onclick="submitLoginForm(); return false;" class="link-button" id="submit-customer-verification" type="button"><?php echo (($this->canAutoDownload) ? JText::_('INSTALLER_INSTALL') : JText::_('INSTALLER_DOWNLOAD')); ?></button>
							</div>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<div id="jsn-showlist-manual-install" class="jsn-manual-install-form">
	<form method="post" enctype="multipart/form-data" action="index.php?option=com_imageshow&controller=installer&task=installImagesourceManual">
		<h3 class="jsn-element-heading"><?php echo JText::_('SHOWLIST_PROFILE_MANUAL_INSTALL_IMAGE_SOURCE')?></h3>
		<div><?php echo JText::sprintf('SHOWLIST_PROFILE_MANUAL_INSTALL_PLEASE_DOWNLOAD', @$data->name)?></div>
		<iframe id="jsn-manual-install-remote-frame"></iframe>
		<!-- <div><a id="jsn-showlist-profile-manual-download-link" href="</?php echo JSN_IMAGESHOW_AUTOUPDATE_URL;?>"></?php echo JText::_('SHOWLIST_PROFILE_MANUAL_DOWNLOAD_IMAGESOURCE_PACKAGE');?></a>, </?php echo JText::_('SHOWLIST_PROFILE_MANUAL_INSTALL_THEN_SELECT_IT');?></div>-->
		<div><input type="file" name="file" size="70"/></div>
		<input type="hidden" name="redirect_link" value="index.php?option=com_imageshow&controller=showlist&task=authenticate&layout=form_login&install_manual_redirect=1"/>
		<div class="jsn-button-container">
			<button class="link-button" ><?php echo JText::_('SHOWLIST_PROFILE_MANUAL_INSTALL_INSTALL'); ?></button>
		</div>
	</form>
</div>