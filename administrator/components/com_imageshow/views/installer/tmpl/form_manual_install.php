<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: form_manual_install.php 11278 2012-02-19 08:24:21Z thangbh $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
$identifiedName = JRequest::getvar('identify_name', '');
$pluginName = JRequest::getvar('name', '');
$installManualRedirect = JRequest::getVar('install_manual_redirect', 0);
$type = JRequest::getVar('type');

if ($installManualRedirect) {
	echo "<script>window.top.location.reload(true); window.top.SqueezeBox.close();</script>";
	exit();
}
	$objVersion = new JVersion();
	$downloadLink = JSN_IMAGESHOW_AUTOUPDATE_URL;
	$downloadLink .= '&identified_name=' . urlencode($identifiedName);
	$downloadLink .= '&edition=';
	$downloadLink .= '&joomla_version=' . urlencode($objVersion->RELEASE);
	$downloadLink .= '&based_identified_name=imageshow';
	$downloadLink .= '&language=' . urlencode($this->adminLang);

?>
<div class="jsn-manual-install-form">
	<form method="post" enctype="multipart/form-data" action="index.php?option=com_imageshow&controller=installer&task=installImagesourceManual">
		<h3 class="jsn-element-heading"><?php echo JText::_('MANUAL_INSTALL_'.strtoupper($type))?></h3>
		<p><?php echo JText::sprintf('MANUAL_INSTALL_PLEASE_DOWNLOAD_'.strtoupper($type), $pluginName); ?></p>
		<div>
			<a id="jsn-showlist-profile-manual-download-link" href="<?php echo $downloadLink; ?>">
				<?php echo JText::_('MANUAL_DOWNLOAD_'.strtoupper($type).'_PACKAGE'); ?></a>, <?php echo JText::_('MANUAL_THEN_SELECT_IT'); ?>
		</div>
		<div><input type="file" name="file" size="70"/></div>
		<input type="hidden" name="redirect_link" value="index.php?option=com_imageshow&controller=installer&task=manualInstall&layout=form_manual_install&install_manual_redirect=1"/>
		<div class="jsn-button-container">
			<button class="link-button" ><?php echo JText::_('MANUAL_INSTALL'); ?></button>
		</div>
	</form>
</div>