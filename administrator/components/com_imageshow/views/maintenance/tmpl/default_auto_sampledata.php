<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_auto_sampledata.php 13385 2012-06-18 11:29:33Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
?>

<div id="jsn-sample-data" class="jsn-bootstrap">
	<div class="text-alert" id="jsn-sample-data-text-alert"> <strong style="color: #cc0000"><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_WARNING'); ?></strong> <?php echo JText::_('MAINTENANCE_SAMPLE_DATA_INSTALL_SUGGESTION'); ?> </div>
	<div id="jsn-sample-data-install">
		<form action="index.php?option=com_imageshow&controller=maintenance&type=sampledata" method="post" enctype="multipart/form-data">
			<div id="jsn-start-installing-sampledata">
				<p>
					<input onclick="return setButtonState(this.form);" type="checkbox" name="agree_install_sample" id="agree_install_sample_local" value="1" />
					<label for="agree_install_sample_local">
						<strong><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_AGREE_INSTALL_SAMPLE_DATA'); ?></strong>
					</label>
				</p>
				<div class="jsn-button-container">
					<button class="btn agree_install_sample_local disabled" type="button" name="button_installation_data" onclick="JSNISSampleData.installSampleData();" disabled="disabled"><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_INSTALL_SAMPLE_DATA');?></button>
				</div>
			</div>
			<div id="jsn-installing-sampledata">
				<p><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_AFTER_DOWNLOAD_SUGGESTION'); ?></p>
				<ul>
					<li id="jsn-download-sample-data-package-title"><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_DOWNLOAD_SAMPLE_DATA_PACKAGE'); ?>.<span class="jsn-icon jsn-icon-loading" id="jsn-downloading-sampledata">&nbsp;</span><span class="jsn-icon jsn-icon-check" id="jsn-download-sampledata-success">&nbsp;</span><span class="jsn-icon jsn-icon-failed" id="jsn-span-unsuccessful-downloading-sampledata">&nbsp;</span><br />
						<p id="jsn-span-unsuccessful-downloading-sampledata-message"></p></li>
					<li id="jsn-install-sample-data-package-title"><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_INSTALL_SAMPLE_DATA'); ?>.<span class="jsn-icon jsn-icon-loading" id="jsn-span-installing-sampledata-state">&nbsp;</span><span class="jsn-icon jsn-icon-check" id="jsn-span-successful-installing-sampledata">&nbsp;</span><span class="jsn-icon jsn-icon-failed" id="jsn-install-sampledata-unsuccessful">&nbsp;</span><br />
						<p id="jsn-span-unsuccessful-installing-sampledata-message"></p></li>
				</ul>
			</div>
			<div class="jsn-sampledata-warnings-text" id="jsn-sampledata-warnings">
				<ul id="jsn-sampledata-ul-warnings">
				</ul>
				<p id="jsn-sampledata-link-install-all-requried-plugins"><a id="jsn-sampledata-a-link-install-all-requried-plugins" rel="{handler: 'iframe', size: {x: 450, y: 250}}" onclick="JSNISSampleData.installAllRequiredPlugins(false);" class="link-action"><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_INSTALL_ALL_REQUIRED_PLUGINS'); ?></a></p> </div>
			<div id="jsn-installing-sampledata-unsuccessfully">
				<div class="jsn-button-container">
					<button class="btn" type="button" name="button_installation_sampledata_unsuccessfully" onclick="window.top.location='index.php?option=com_imageshow&controller=maintenance&type=data&tab=0';"><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_CANCEL');?></button>
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
		</form>
	</div>
</div>