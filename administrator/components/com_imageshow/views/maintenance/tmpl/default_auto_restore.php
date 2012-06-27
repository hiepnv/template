<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_auto_restore.php 13385 2012-06-18 11:29:33Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
$session 	   = JFactory::getSession();
$restoreResult = $session->get('JSNISRestore');
?>
<div class="jsn-bootstrap">
<form action="index.php?option=com_imageshow&controller=maintenance" method="POST" name="adminFormRestore" enctype="multipart/form-data" id="frm_restore">
	<table border="0" width="100%" align="center" cellpadding="2" cellspacing="0">
		<?php if (!is_array($restoreResult)) { ?>
		<tr>
			<td>
				<p class="item-title"><?php echo JText::_('MAINTENANCE_BACKUP_BACKUP_FILE'); ?>:</p>
				<p>
					<input type="file" id="file-upload" name="filedata" size="70" onchange="return setRestoreButtonState(this.form);"/>
				</p>
				<div class="jsn-button-container">
					<button class="btn disabled" type="button" value="<?php echo JText::_('MAINTENANCE_BACKUP_RESTORE'); ?>" onclick="return restore();" disabled="disabled" name="button_backup_restore"><?php echo JText::_('MAINTENANCE_BACKUP_RESTORE'); ?></button>
				</div>
			</td>
		</tr>
		<?php } else if (is_array($restoreResult)) { ?>
		<tr>
			<td>
				<p id="jsn-restore-result"><?php echo JText::_('MAINTENANCE_RESTORE_RESULT_HEADER');?> </p>
				<ul>
					<?php if (is_array($restoreResult) && $restoreResult['extractFile'] == false){ ?>
						<li>
							<?php echo JText::_('MAINTENANCE_RESTORE_EXTRACT_BACKUP_FILE')?>
								<span class="jsn-icon jsn-icon-failed jsn-restore-icon-failure">&nbsp;</span>
								<p id="jsn-restore-extract-failure"><?php echo $restoreResult['message']; ?></p>
						</li>
					<?php } else {?>
						<li>
							<?php echo JText::_('MAINTENANCE_RESTORE_EXTRACT_BACKUP_FILE')?>
							<span class="jsn-icon jsn-icon-check jsn-restore-icon-success">&nbsp;</span>
						</li>
						<li id="jsn-restore-data-wrap">
							<?php echo JText::_('MAINTENANCE_RESTORE_RESTORE_DATA')?>
							<?php if ($restoreResult['requiredSourcesNeedInstall'] || $restoreResult['requiredThemesNeedInstall']) { ?>
								<span class="jsn-icon jsn-icon-failed jsn-restore-icon-failure">&nbsp;</span>
									<div id="jsn-restore-data" class="jsn-restore-icon-process">
										<img src="components/com_imageshow/assets/images/ajax-loader-circle.gif"/>
									</div>
									<div class="jsn-restore-warning">
										<div id="jsn-restore-install-required-warning">
											<p><?php echo JText::_('MAINTENANCE_RESTORE_IMAGESOURCES_AND_THEMES_REQUIRED');?></p>
											<ul id="jsn-restore-list-required-install">
												<?php foreach ($restoreResult['requiredSourcesNeedInstall'] as $source):?>
													<li><?php echo $source->name; ?></li>
												<?php endforeach; ?>

												<?php foreach ($restoreResult['requiredThemesNeedInstall'] as $theme):?>
													<li><?php echo $theme->name; ?></li>
												<?php endforeach; ?>
											</ul>
											<?php if (isset($restoreResult['requiredInstallData']['commercial']) && $restoreResult['requiredInstallData']['commercial'] == true) { ?>
												<p><a class="modal jsn-action-link"
													href="index.php?option=com_imageshow&controller=maintenance&task=login&layout=default_login&tmpl=component&js_class=JSNISInstallDefault"
													onclick="JSNISInstallDefault.setOption(<?php echo $this->escape(json_encode($restoreResult['requiredInstallData'])); ?>)"
													rel="{handler: 'iframe', size: {x: 450, y: 250}}" >
														<?php echo JText::_('MAINTENANCE_RESTORE_INSTALL_ALL_REQUIRED_PLUGINS')?></a>
												</p>
											<?php } else { ?>
												<p><a class="jsn-action-link" href="#"
													onclick="JSNISInstallDefault.restoreInstall(<?php echo $this->escape(json_encode($restoreResult['requiredInstallData'])); ?>); return false;" >
													 <?php echo JText::_('MAINTENANCE_RESTORE_INSTALL_ALL_REQUIRED_PLUGINS')?></a>
												</p>
											<?php } ?>
										</div>
										<ul class="jsn-restore-install-required">
											<?php if (count($restoreResult['requiredSourcesNeedInstall'])) {?>
											<li id="jsn-restore-required-sources">
												<?php echo JText::_('MAINTENANCE_RESTORE_INSTALL_REQUIRED_IMAGE_SOURCES')?>
												<div class="jsn-restore-icon-process">
													<img src="components/com_imageshow/assets/images/ajax-loader-circle.gif"/>
												</div>
												<span class="jsn-restore-change-text" id="jsn-restore-source-change-text"></span>
												<span class="jsn-icon jsn-icon-check jsn-restore-icon-success">&nbsp;</span>
											</li>
											<?php } ?>
											<?php if (count($restoreResult['requiredThemesNeedInstall'])) {?>
											<li id="jsn-restore-required-themes">
												<?php echo JText::_('MAINTENANCE_RESTORE_INSTALL_REQUIRED_IMAGE_THEMES')?>
												<div class="jsn-restore-icon-process">
													<img src="components/com_imageshow/assets/images/ajax-loader-circle.gif"/>
												</div>
												<span class="jsn-restore-change-text" id="jsn-restore-theme-change-text"></span>
												<span class="jsn-icon jsn-icon-check jsn-restore-icon-success">&nbsp;</span>
											</li>
											<?php }?>
										</ul>

									 </div>

							<?php } else { ?>
								<span class="jsn-icon jsn-icon-check jsn-restore-icon-success">&nbsp;</span>
							<?php } ?>
						</li>
						<?php }?>
				</ul>
				<div id="jsn-restore-database-success" <?php echo ($restoreResult['error'] == false)? ' style="display:block;" ' : ''?>>
					<hr />
					<?php echo JText::_('MAINTENANCE_RESTORE_RESTORE_DATABASE_SUCCESS')?>
				</div>
				<div id="jsn-restore-buttons" class="jsn-button-container <?php echo ($restoreResult['error'] == false)? ' jsn-restore-installing-success ' : ''?>">
					<button class="btn jsn-restore-button-cancel" type="button" value="<?php echo JText::_('MAINTENANCE_RESTORE_CANCEL'); ?>" onclick="return clearSessionRestoreResult();" name="button_backup_restore"><?php echo JText::_('MAINTENANCE_RESTORE_CANCEL'); ?></button>
					<button class="btn jsn-restore-button-finish" type="button" value="<?php echo JText::_('MAINTENANCE_RESTORE_FINISH'); ?>" onclick="return clearSessionRestoreResult();" name="button_backup_restore"><?php echo JText::_('MAINTENANCE_RESTORE_FINISH'); ?></button>
				</div>
			</td>
		</tr>
		<?php } ?>
		</table>
		<input type="hidden" name="option" value="com_imageshow" />
		<input type="hidden" name="controller" value="maintenance" />
		<input type="hidden" name="task" value="restore" />
		<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>