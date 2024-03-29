<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
$script = "\t".'Joomla.submitbutton = function(pressbutton) {'."\n";
$script .= "\t\t".'var form = document.adminForm;'."\n";
$script .= "\t\t".'if (pressbutton == \'mail.cancel\') {'."\n";
$script .= "\t\t\t".'Joomla.submitform(pressbutton);'."\n";
$script .= "\t\t\t".'return;'."\n";
$script .= "\t\t".'}'."\n";
$script .= "\t\t".'// do field validation'."\n";
$script .= "\t\t".'if (form.jform_subject.value == ""){'."\n";
$script .= "\t\t\t".'alert("'.JText::_('COM_USERS_MAIL_PLEASE_FILL_IN_THE_SUBJECT', true).'");'."\n";
$script .= "\t\t".'} else if (getSelectedValue(\'adminForm\',\'jform[group]\') < 0){'."\n";
$script .= "\t\t\t".'alert("'.JText::_('COM_USERS_MAIL_PLEASE_SELECT_A_GROUP', true).'");'."\n";
$script .= "\t\t".'} else if (form.jform_message.value == ""){'."\n";
$script .= "\t\t\t".'alert("'.JText::_('COM_USERS_MAIL_PLEASE_FILL_IN_THE_MESSAGE', true).'");'."\n";
$script .= "\t\t".'} else {'."\n";
$script .= "\t\t\t".'Joomla.submitform(pressbutton);'."\n";
$script .= "\t\t".'}'."\n";
$script .= "\t\t".'}'."\n";

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');

JFactory::getDocument()->addScriptDeclaration($script);
?>

<form action="<?php echo JRoute::_('index.php?option=com_users&view=mail'); ?>" name="adminForm" method="post" id="adminForm">
	<div class="row">
	<div class="span3">
		<fieldset class="form-inline">
			<legend><?php echo JText::_('COM_USERS_MAIL_DETAILS'); ?></legend>
			<div class="control-group">
				<div class="controls checkbox"><?php echo $this->form->getInput('recurse'); ?></div>
				<div class="control-label"><?php echo $this->form->getLabel('recurse'); ?></div>
			</div>
			<div class="control-group">
				<div class="controls checkbox"><?php echo $this->form->getInput('mode'); ?></div>
				<div class="control-label"><?php echo $this->form->getLabel('mode'); ?></div>
			</div>
			<div class="control-group">
				<div class="controls checkbox"><?php echo $this->form->getInput('disabled'); ?></div>
				<div class="control-label"><?php echo $this->form->getLabel('disabled'); ?></div>
			</div>
			<div class="control-group">
				<div class="controls checkbox"><?php echo $this->form->getInput('bcc'); ?></div>
				<div class="control-label"><?php echo $this->form->getLabel('bcc'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('group'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('group'); ?></div>
			</div>
		</fieldset>
	</div>
	<div class="span9">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_USERS_MAIL_MESSAGE'); ?></legend>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('subject'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('subject'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('message'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('message'); ?></div>
			</div>
		</fieldset>
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
	</div>
</form>
