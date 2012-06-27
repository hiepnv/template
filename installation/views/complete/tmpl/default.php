<?php
/**
 * @package		Joomla.Installation
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<form action="index.php" method="post" id="adminForm" class="form-validate form-horizontal">
	<div id="installer">
		<fieldset>
			<legend><?php echo JText::_('INSTL_COMPLETE_TITLE'); ?></legend>
			<div class="control-group">
				<label for="" class="control-label">
					<?php echo JText::_('INSTL_COMPLETE_REMOVE_FOLDER'); ?>
				</label>
				<div class="controls">
					<div class="alert alert-info">
						<?php echo JText::_('INSTL_COMPLETE_REMOVE_INSTALLATION'); ?>
					</div>
					<button class="btn btn-info" name="instDefault" onclick="Install.removeFolder(this);"><i class="icon-remove icon-white"></i> <?php echo JText::_('INSTL_COMPLETE_REMOVE_FOLDER'); ?></button>
					
					<div class="alert alert-error inlineError" id="theDefaultError" style="display: none">
							<h4 class="alert-heading"><?php echo JText::_('JERROR'); ?></h4>
							<p id="theDefaultErrorMessage"></p>
					</div>
				</div>
			</div>
			<div class="control-group">
				<label for="" class="control-label">
					<?php echo JText::_('INSTL_COMPLETE_DESC1'); ?>
				</label>
				<div class="controls">
					<a class="btn" href="<?php echo JURI::root(); ?>" title="<?php echo JText::_('JSITE'); ?>"><i class="icon-eye-open"></i> <?php echo JText::_('JSITE'); ?></a> 
					<a class="btn btn-primary" href="<?php echo JURI::root(); ?>administrator/" title="<?php echo JText::_('JADMINISTRATOR'); ?>"><i class="icon-lock icon-white"></i> <?php echo JText::_('JADMINISTRATOR'); ?></a>
				</div>
			</div>
			<div class="control-group">
				<label for="" class="control-label">
					<?php echo JText::_('INSTL_COMPLETE_ADMINISTRATION_LOGIN_DETAILS'); ?>
				</label>
				<div class="controls">
					<?php echo JText::_('JUSERNAME'); ?> : <span class="label"><?php echo $this->options['admin_user']; ?></span>
				</div>
			</div>
			<div class="control-group">
				<label for="" class="control-label">
					<?php echo JText::_('INSTL_COMPLETE_LANGUAGE_1'); ?>
				</label>
				<div class="controls">
					<a href="http://community.joomla.org/translations/joomla-16-translations.html" target="_blank">
						<?php echo JText::_('INSTL_COMPLETE_LANGUAGE_2'); ?>
					</a>
				</div>
			</div>
			<?php if ($this->config) : ?>
			<div class="control-group">
				<label for="" class="control-label">
					<?php echo JText::_('INSTL_CONFPROBLEM'); ?>
				</label>
				<div class="controls">
					<div class="alert alert-error">
						<textarea rows="5" cols="49" name="configcode" onclick="this.form.configcode.focus();this.form.configcode.select();" ><?php echo $this->config; ?></textarea>
					</div>
				</div>
			</div>
			<?php endif; ?>
		</fieldset>
	</div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
