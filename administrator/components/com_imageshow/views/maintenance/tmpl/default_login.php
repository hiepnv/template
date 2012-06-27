<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_login.php 11278 2012-02-19 08:24:21Z thangbh $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
$jsClass = JRequest::getVar('js_class');
?>
<script type="text/javascript">

		function submitLoginForm()
		{
			if (document.jsnInstallLoginForm.username.value == ''
				|| document.jsnInstallLoginForm.password.value == '')
			{
				alert('<?php echo JText::_('INSTALLER_LOGIN_REQUIRED_INPUT')?>');
				return false;
			}

			window.top.JSNISInstallImageSources.options.username = document.jsnInstallLoginForm.username.value;
			window.top.JSNISInstallImageSources.options.password = document.jsnInstallLoginForm.password.value;
			window.top.JSNISInstallShowcaseThemes.options.username = document.jsnInstallLoginForm.username.value;
			window.top.JSNISInstallShowcaseThemes.options.password = document.jsnInstallLoginForm.password.value;

			if (window.top.JSNISInstallDefault){
				window.top.JSNISInstallDefault.options.username = document.jsnInstallLoginForm.username.value;
				window.top.JSNISInstallDefault.options.password = document.jsnInstallLoginForm.password.value;
			}

			window.top.<?php echo $jsClass; ?>.installCommercial(true);
			window.top.SqueezeBox.close();
			return false;
		}

</script>
<form name="jsnInstallLoginForm">
	<table id="jsn-install-login-form">
		<tr>
			<td colspan="2" align="center"><h2><?php echo JText::_('INSTALLER_LOGIN_HEADER')?></h2></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><?php echo JText::_('INSTALLER_LOGIN_DESCRIPTION')?></td>
		</tr>
		<tr>
			<td class="label"><?php echo JText::_('INSTALLER_USERNAME'); ?></td>
			<td><input type="text" name="username"/></td>
		</tr>
		<tr>
			<td class="label"><?php echo JText::_('INSTALLER_PASSWORD'); ?></td>
			<td><input type="password" name="password"/></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><button onclick="submitLoginForm(); return false;" class="link-button" type="button" name="<?php echo JText::_('INSTALLER_INSTALL'); ?>" onclick="" ><?php echo JText::_('INSTALLER_INSTALL'); ?></button></td>
		</tr>
	</table>
</form>


