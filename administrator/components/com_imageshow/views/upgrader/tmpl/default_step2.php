<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_step2.php 12464 2012-05-07 05:14:25Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
$session 		= JFactory::getSession();
$identifier		= md5('jsn_upgrader_jsn_imageshow');
$sessionValue   = $session->get($identifier, array(), 'jsnimageshowsession');
?>
<script type="text/javascript">
	var upgrader = new JSNISUpgrader({});
</script>
<form method="POST" action="index.php?option=com_imageshow&controller=upgrader&step=<?php echo (count($sessionValue) && $sessionValue['success'] && count($sessionValue['editions']) > 1)?'3':'2';?>" id="frm-login" name="frm_login" class="upgrader-from" autocomplete="off">
	<div class="jsn-upgrader-step2">
		<div class="jsn-install-admin-info">
			<h2><?php echo JText::_('UPGRADER_HEADING_STEP1'); ?></h2>
			<?php echo JText::_('UPGRADER_LOGIN_MES'); ?>
			<p class="clearafter">
				<span><?php echo JText::_('UPGRADER_USERNAME'); ?><input name="customer_username" id="username" <?php echo (@$sessionValue['customer_username'] !='')?'readonly="readonly" class="jsn-readonly"':''?> value="<?php echo @$sessionValue['customer_username']; ?>" type="text" onchange="upgrader.setNextButtonState(this.form, this.form.next_step_button);" onkeyup="upgrader.setNextButtonState(this.form, this.form.next_step_button);" /></span>
				<span><?php echo JText::_('UPGRADER_PASSWORD'); ?><input name="customer_password" id="password" <?php echo (@$sessionValue['customer_password'] !='')?'readonly="readonly" class="jsn-readonly"':''?> value="<?php echo @$sessionValue['customer_password']; ?>" type="password"  onchange="upgrader.setNextButtonState(this.form, this.form.next_step_button);" onkeyup="upgrader.setNextButtonState(this.form, this.form.next_step_button);" /></span>
			</p>
		</div>
		<?php
			if (count($sessionValue) && @$sessionValue['success'] && count(@$sessionValue['editions']) > 1)
			{
		?>
		<div id="jsn-upgrade-edition-wrapper">
			<hr class="jsn-horizontal-line" />
			<p><?php echo JText::_('UPGRADER_MULTIPLE_SELECT_MES'); ?></p>
			<p class="clearafter">
				<span><?php echo JText::_('UPGRADER_UPGRADE_TO'); ?></span>
				<select name="jsn_upgrade_edition" id="jsn-upgrade-edition-select" onchange="upgrader.setNextButtonState(this.form, this.form.next_step_button_new);">
					<option value=""><?php echo JText::_('UPGRADER_DEFAULT_SELECT_OPTION'); ?></option>
					<?php
						$editions = $sessionValue['editions'];
						$counte   = count($editions);
						foreach ($editions as $value)
						{
						?>
							<option value="<?php echo strtolower($value)?>"><?php echo $value; ?></option>
						<?php
						}
					?>
				</select>
			</p>
		</div>
		<?php } else { ?>
			<input type="hidden" name="task" value="authenticate" />
		<?php }?>
		<div class="jsn-install-admin-check" id="jsn-upgrade-old-button-wrapper">
			<hr class="jsn-horizontal-line" />
			<div class="jsn-upgrader-next-button">
				<button class="link-button disabled" id="jsn-upgrader-btn-next" disabled="disabled" onclick="this.disabled=true; this.addClass('disabled'); $('jsn-upgrader-cancel').setStyle('display', 'none'); document.frm_login.submit();" name="<?php echo (count($sessionValue) && $sessionValue['success'] && count($sessionValue['editions']) > 1)?'next_step_button_new':'next_step_button';?>"><?php echo JText::_('UPGRADER_NEXT_BUTTON'); ?></button>
			</div>
		</div>
		<input type="hidden" name="identify_name" value="<?php echo $this->core->identify_name;?>" />
		<input type="hidden" name="based_identified_name" value="<?php echo $this->core->based_identified_name;?>" />
		<input type="hidden" name="edition" value="<?php echo $this->core->edition;?>" />
		<input type="hidden" name="language" value="<?php echo $this->core->language;?>" />
		<?php echo JHTML::_('form.token'); ?>
	</div>
</form>
<?php $session->set($identifier, array(), 'jsnimageshowsession'); ?>