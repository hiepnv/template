<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_manual_sampledata.php 13385 2012-06-18 11:29:33Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
$session 					=& JFactory::getSession();
$subAction					= JRequest::getVar('sub_action');
$tab						= JRequest::getVar('tab');
$uploadIdentifier 			= md5('upload_sampledata_package');
$packagenameIdentifier 		= md5('sampledata_package_name');
if ($tab != '0' || $subAction == 'cancelintallation')
{
	$session->set($uploadIdentifier, false, 'jsnimageshow');
	$session->set($packagenameIdentifier, '', 'jsnimageshow');
}
$sessionValue				= $session->get($uploadIdentifier, false, 'jsnimageshow');
$packageName				= $session->get($packagenameIdentifier, '', 'jsnimageshow');
?>
<div id="jsn-sample-data">
	<?php if (!$sessionValue) { ?>
	<div class="text-alert"> <strong style="color: #cc0000"><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_WARNING'); ?></strong> <?php echo JText::_('MAINTENANCE_SAMPLE_DATA_INSTALL_SUGGESTION'); ?> </div>
	<?php } ?>
	<div id="jsn-sample-data-install">
		<form action="index.php?option=com_imageshow&controller=maintenance&type=data" method="post" enctype="multipart/form-data">
			<?php if (!$sessionValue) { ?>
			<div class="jsn-manual-installation">
				<ul>
					<li>1. <?php echo JText::_('MANUAL_DOWNLOAD_INSTALLATION_PACKAGE');?>: <a class="btn" href="<?php echo JSN_IMAGESHOW_FILE_URL; ?>"><span><?php echo JText::_('MANUAL_DOWNLOAD'); ?></span></a></li>
					<li>2. <?php echo JText::_('MANUAL_SELECT_DOWNLOADED_PACKAGE');?>: <input id="sample_data_input_file" size="45%" type="file" name="install_package" /></li>
				</ul>
			</div>
			<div id="jsn-start-installing-sampledata">
				<p>
					<input onclick="return setButtonState(this.form);" type="checkbox" name="agree_install_sample" id="agree_install_sample_local" value="1" />
					<label for="agree_install_sample_local">
						<strong><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_AGREE_INSTALL_SAMPLE_DATA'); ?></strong>
					</label>
				</p>
				<div class="jsn-button-container">
					<button class="btn agree_install_sample_local disabled" type="submit" name="button_installation_data" disabled="disabled"><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_INSTALL_SAMPLE_DATA');?></button>
				</div>
			</div>
			<input type="hidden" name="task" value="installSampledataManually"/>
			<input type="hidden" name="option" value="com_imageshow" />
			<input type="hidden" name="controller" value="maintenance" />
			<input type="hidden" name="method_install_sample_data" value="manually" />
			<?php echo JHTML::_( 'form.token' ); ?>
		</form>
			<?php } else {
			?>
			<script type="text/javascript">
				window.addEvent('domready', function() {
					$('jsn-installing-sampledata').setStyle('display', 'block');
					$('jsn-downloading-sampledata').setStyle('display', 'none');
					$('jsn-download-sampledata-success').setStyle('display', 'inline-block');
					$('jsn-install-sample-data-package-title').setStyle('display', 'list-item');
					JSNISSampleDataInstallSampleDataName = '<?php echo trim($packageName);?>';
					JSNISSampleDataManual.installPackage('<?php echo trim($packageName);?>');
				});
			</script>
			<div id="jsn-installing-sampledata">
				<p><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_AFTER_DOWNLOAD_SUGGESTION'); ?></p>
				<ul>
					<li id="jsn-download-sample-data-package-title"><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_UPLOAD_SAMPLE_DATA_PACKAGE'); ?>.<span class="jsn-icon jsn-icon-loading" id="jsn-downloading-sampledata">&nbsp;</span><span class="jsn-icon jsn-icon-check" id="jsn-download-sampledata-success">&nbsp;</span><span class="jsn-icon jsn-icon-failed" id="jsn-span-unsuccessful-downloading-sampledata" style="display:none;">&nbsp;</span><br />
						<p id="jsn-span-unsuccessful-downloading-sampledata-message"></p></li>
					<li id="jsn-install-sample-data-package-title"><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_INSTALL_SAMPLE_DATA'); ?>.<span class="jsn-icon jsn-icon-loading" id="jsn-span-installing-sampledata-state">&nbsp;</span><span class="jsn-icon jsn-icon-check" id="jsn-span-successful-installing-sampledata">&nbsp;</span><span class="jsn-icon jsn-icon-failed" id="jsn-install-sampledata-unsuccessful">&nbsp;</span><br />
						<p id="jsn-span-unsuccessful-installing-sampledata-message"></p>
						<div id="jsn-installing-sampledata_install_requried_plugin">
							<form action="index.php?option=com_imageshow&controller=maintenance&type=data" method="post" name="installPluignForm" enctype="multipart/form-data">
								<input id="pluign_file" size="75%" type="file" name="pluign_file" />
								<div class="jsn-button-container">
									<button class="btn agree_install_sample_local" type="submit" name="button_installation_data"><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_INSTALL_REQUIRED_PLUGIN');?></button>
									<button class="btn" type="button" name="button_installation_sampledata_unsuccessfully" onclick="window.top.location='index.php?option=com_imageshow&controller=maintenance&type=data&tab=0&sub_action=cancelintallation';"><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_CANCEL');?></button>
								</div>
								<input type="hidden" name="task" value="installRequiredPlugin"/>
								<input type="hidden" name="option" value="com_imageshow" />
								<input type="hidden" name="controller" value="maintenance" />
								<input type="hidden" name="method_install_sample_data" value="manually" />
								<input type="hidden" name="element_type" id="element_type" value="" />
								<?php echo JHTML::_( 'form.token' ); ?>
							</form>
						</div>
					</li>
				</ul>
			</div>
			<div id="jsn-installing-sampledata-unsuccessfully">
				<div class="jsn-button-container">
					<button class="btn" type="button" name="button_installation_sampledata_unsuccessfully" onclick="window.top.location='index.php?option=com_imageshow&controller=maintenance&type=data&tab=0&sub_action=cancelintallation';"><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_CANCEL');?></button>
				</div>
			</div>
			<div id="jsn-installing-sampledata-successfully">
				<hr>
				<h3><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_SAMPLE_DATA_IS_SUCCESSFULLY_INSTALLED'); ?></h3>
				<p><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_CONGRATULATIONS_NOW_YOU_CAN_OPERATE_ON_SAMPLE_SHOWLISTS_AND_SHOWCASES'); ?></p>
				<div class="jsn-button-container">
					<button class="btn agree_install_sample_local" type="button" name="button_installation_sampledata_finish" onclick="window.top.location='index.php?option=com_imageshow';"><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_FINISH');?></button>
				</div>
			</div>
			<?php } ?>
	</div>
</div>