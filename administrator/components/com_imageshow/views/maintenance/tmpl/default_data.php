<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_data.php 13385 2012-06-18 11:29:33Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
$selectedTab 				= JRequest::getInt('tab', 0, 'get');
$myaction	 				= JRequest::getVar('myaction');
$user 		 				= JFactory::getUser();
$session 					= JFactory::getSession();
$restoreResult 				= $session->get('JSNISRestore');
$methodInstallSampleData 	= JRequest::getVar('method_install_sample_data');
$objJSNUtil 				= JSNISFactory::getObj('classes.jsn_is_utils');
$canAutoDownload			= $objJSNUtil->checkEnvironmentDownload();
$db 						= JFactory::getDbo();
?>
<script language="javascript" type="text/javascript">
	var restoreOption = {
		wait_text: '<?php echo JText::_('SHOWLIST_IMAGE_SOURCE_INSTALL_WAIT_TEXT', true); ?>',
		process_text: '<?php echo JText::_('SHOWLIST_IMAGE_SOURCE_INSTALL_PROCESS_TEXT', true); ?>',
		textTag: 'span'
	};

	JSNISInstallDefault.options = $merge(JSNISInstallDefault.options, restoreOption);
	JSNISInstallShowcaseThemes.options = $merge(JSNISInstallShowcaseThemes.options, JSNISInstallDefault.options);
	JSNISInstallImageSources.options = $merge(JSNISInstallImageSources.options, JSNISInstallDefault.options);

	function backup()
	{
		document.getElementById('frm_backup').submit();
	}

	function restore()
	{
		if (document.getElementById('file-upload').value == ""){
			alert( "<?php echo JText::_('MAINTENANCE_BACKUP_YOU_MUST_SELECT_A_FILE_BEFORE_IMPORTING', true); ?>" );
			return false;
		}else {
			document.getElementById('frm_restore').submit();
		}
	}

	function clearSessionRestoreResult(){
		document.adminFormRestore.task.value = 'clearSessionRestoreResult';
		document.adminFormRestore.submit();
	}

	function setButtonState(form)
	{
		if(form.agree_install_sample.checked)
		{
			form.button_installation_data.disabled = false;
			$(form.button_installation_data).removeClass('disabled');
		}
		else
		{
			form.button_installation_data.disabled = true;
			$(form.button_installation_data).addClass('disabled');
		}
	}

	function setBackupButtonState(form)
	{
		var showlist = form.showlists.checked;
		var showcase = form.showcases.checked;
		var filename = form.filename.value;
		if ((showlist || showcase) && filename != '')
		{
			form.button_backup_data.disabled = false;
			$(form.button_backup_data).removeClass('disabled');
		}
		else
		{
			form.button_backup_data.disabled = true;
			$(form.button_backup_data).addClass('disabled');
		}
	}

	function setRestoreButtonState(form)
	{
		var filedata = form.filedata.value;
		if (filedata != '')
		{
			form.button_backup_restore.disabled = false;
			$(form.button_backup_restore).removeClass('disabled');
		}
		else
		{
			form.button_backup_restore.disabled = true;
			$(form.button_backup_restore).addClass('disabled');
		}
	}
</script>
<script>
(function($){
	$(document).ready(function () {
			<?php if ($selectedTab == '1') { ?>
		    $('#dataTab a[href="#tab2"]').tab('show');
		    <?php } else {?>
		    $('#dataTab a[href="#tab1"]').tab('show');
		    <?php } ?>
		    <?php if ($myaction == 'restore') { ?>
		    $('#accordion2 #collapseTwo').collapse('show');
		    <?php } ?>
		})
})(jQuery);
</script>
<div id="jsn-main-content">
	<div id="jsn-data">
		<div class="jsn-bootstrap tabbable">
		    <ul class="nav nav-tabs" id="dataTab">
		    	<?php if (strtolower($db->name) != 'sqlsrv') {?>
		    	<li><a href="#tab1" data-toggle="tab"><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_INSTALLATION'); ?></a></li>
		   		<li><a href="#tab2" data-toggle="tab"><?php echo JText::_('MAINTENANCE_DATA_BACKUP_AND_RESTORE'); ?></a></li>
		   		<?php } ?>
		   		<li<?php echo (strtolower($db->name) == 'sqlsrv')?' class="active"':''; ?>><a <?php echo (strtolower($db->name) == 'sqlsrv')?' href="#tab1"':' href="#tab3"'; ?> data-toggle="tab"><?php echo JText::_('MAINTENANCE_DATA_MAINTENANCE'); ?></a></li>
		    </ul>
		    <div class="tab-content">
		    	<?php if (strtolower($db->name) != 'sqlsrv') {?>
		    	<div class="tab-pane" id="tab1">
					<?php
						if ($methodInstallSampleData != '' && $methodInstallSampleData == 'manually' || !$canAutoDownload)
						{
							echo $this->loadTemplate('manual_sampledata');
						}
						else
						{
							echo $this->loadTemplate('auto_sampledata');
						}
					?>
		    	</div>
		    	<div class="tab-pane" id="tab2">
					<div id="accordion2" class="accordion">
						 <div class="accordion-group">
							 <div class="accordion-heading">
								<a data-target="#collapseOne" data-parent="#accordion2" data-toggle="collapse" class="accordion-toggle">
                  					<?php echo JText::_('MAINTENANCE_DATA_BACKUP'); ?>
               					 </a>
							 </div>
				              <div class="accordion-body collapse" id="collapseOne">
				                <div class="accordion-inner">
					                <div id="jsn-data-backup">
										<form action="index.php?option=com_imageshow&controller=maintenance" method="POST" name="adminFormBackup" id="frm_backup" onsubmit="return false;">
											<table border="0" width="100%" align="center" cellpadding="2" cellspacing="0">
												<tr>
													<td width="50%" valign="top">
														<p class="item-title"><?php echo JText::_('MAINTENANCE_BACKUP_BACKUP_FILENAME'); ?>:</p>
														<p>
															<input type="text" id="filename" name="filename" onkeyup="return setBackupButtonState(this.form);"/>
														</p>
														<p>
															<input type="checkbox" name="timestamp" id="timestamp" value="1" />
															<label for="timestamp"><?php echo JText::_('MAINTENANCE_BACKUP_ATTACH_TIMESTAMP_TO_FILENAME'); ?></label>
														</p>
													</td>
													<td width="50%" valign="top">
														<p class="item-title"><?php echo JText::_('MAINTENANCE_BACKUP_BACKUP_OPTIONS'); ?>:</p>
														<p>
															<input type="checkbox" name="showlists" id="showlist" value="1" onclick="return setBackupButtonState(this.form);"/>
															<label for="showlist"><?php echo JText::_('MAINTENANCE_BACKUP_BACKUP_SHOWLISTS'); ?></label>
														</p>
														<p>
															<input type="checkbox" name="showcases" id="showcases" value="1" onclick="return setBackupButtonState(this.form);"/>
															<label for="showcases"><?php echo JText::_('MAINTENANCE_BACKUP_BACKUP_SHOWCASES'); ?></label>
														</p>
													</td>
												</tr>
												<tr>
													<td colspan="2" align="center">
														<div class="jsn-button-container">
															<button class="btn disabled" type="button" value="<?php echo JText::_('MAINTENANCE_BACKUP_BACKUP');?>" onclick="backup();" disabled="disabled" name="button_backup_data"><?php echo JText::_('MAINTENANCE_BACKUP_BACKUP');?></button>
														</div>
													</td>
												</tr>
											</table>
											<input type="hidden" name="option" value="com_imageshow" />
											<input type="hidden" name="controller" value="maintenance" />
											<input type="hidden" name="task" value="backup" />
											<?php echo JHTML::_( 'form.token' ); ?>
										</form>
									</div>
				                </div>
				              </div>
						 </div>
						 <div class="accordion-group">
							 <div class="accordion-heading">
								<a data-target="#collapseTwo" data-parent="#accordion2" data-toggle="collapse" class="accordion-toggle">
                  					<?php echo JText::_('MAINTENANCE_DATA_RESTORE'); ?>
               					 </a>
							 </div>
				              <div class="accordion-body collapse" id="collapseTwo">
				                <div class="accordion-inner">
				                  	<div id="jsn-data-restore">
										<?php
											if ($canAutoDownload) {
												echo $this->loadTemplate('auto_restore');
											} else {
												echo $this->loadTemplate('manual_restore');
											}
										?>
									</div>
				                </div>
				              </div>
						 </div>
					</div>
		    	</div>
		    	<?php } ?>
		     	<div class="tab-pane<?php echo (strtolower($db->name) == 'sqlsrv')?' active':''; ?>" <?php echo (strtolower($db->name) == 'sqlsrv')?' id="tab1"':' id="tab3"'; ?>>
					<form action="index.php?option=com_imageshow&controller=maintenance" name="adminFormDatamaintenance" id="frm_datamaintenance">
						<div id="jsn-data-maintenance">
							<h3>
								<?php echo JText::_("MAINTENANCE_RECREATE_THUMBNAILS");?>
							</h3>
							<p><?php echo JText::_('MAINTENANCE_THIS_PROCESS_WILL_RECREATE_ALL_THUMBNAILS'); ?></p>
							<div class="jsn-button-container">
								<button class="btn" id="jsn-button-delete-obsolete-thumnail" type=button value="<?php echo JText::_('MAINTENANCE_START'); ?>" onclick="JSNISImageShow.deleteObsoleteThumbnails('<?php echo JUtility::getToken();?>')"><?php echo JText::_('MAINTENANCE_START'); ?></button>
								<span class="jsn-icon jsn-icon-loading" id="jsn-creating-thumbnail"></span>
								<span class="jsn-icon jsn-icon-check" id="jsn-creat-thumbnail-successful"></span>
								<span class="jsn-icon jsn-icon-warning" id="jsn-creat-thumbnail-unsuccessful"></span>
							</div>
						</div>
					</form>
		    	</div>
			</div>
		</div>
	</div>
</div>