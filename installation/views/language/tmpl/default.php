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
			<legend><?php echo JText::_('INSTL_LANGUAGE_TITLE'); ?></legend>
			<div class="control-group">
				<label for="jform_language" class="control-label"><?php echo JText::_('INSTL_SELECT_LANGUAGE_TITLE'); ?></label>
				<div class="controls">
					<?php echo $this->form->getInput('language'); ?>
				</div>
			</div>
			<div class="form-actions">
				<a href="#" class="btn btn-primary" onclick="Install.submitform();" rel="next" title="<?php echo JText::_('JNext'); ?>"><?php echo JText::_('JNext'); ?></a>
			</div>
		</fieldset>
	</div>
	<input type="hidden" name="task" value="setup.setlanguage" />
	<?php echo JHtml::_('form.token'); ?>
</form>

<link rel="stylesheet" href="<?php echo JURI::root();?>templates/system/css/chosen.css" type="text/css" />
<script src="<?php echo JURI::root();?>templates/system/js/chosen.jquery.min.js" type="text/javascript"></script>
<script type="text/javascript"> 
!function ($) {
$(".chzn-select-deselect").chosen({allow_single_deselect:true}); 
}(window.jQuery)
</script>