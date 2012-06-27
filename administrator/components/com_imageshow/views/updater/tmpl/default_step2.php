<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_step2.php 13261 2012-06-13 03:06:05Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
$session 		= JFactory::getSession();
$identifier		= md5('jsn_updater_jsn_imageshow');
$sessionValue   = $session->get($identifier, array(), 'jsnimageshowsession');
$paramsLang 	= JComponentHelper::getParams('com_languages');
$adminLang 		= $paramsLang->get('administrator', 'en-GB');
?>
<script type="text/javascript">
	var updater = new JSNISUpdater({});
</script>
<form method="POST" action="index.php?option=com_imageshow&controller=updater&step=<?php echo (count($sessionValue) && $sessionValue['success'])?'3':'2';?>" id="frm-login" name="frm_login" class="upgrader-from" autocomplete="off">
	<div class="jsn-upgrader-step2">
		<div class="jsn-install-admin-info">
			<h2><?php echo JText::_('UPGRADER_HEADING_STEP1'); ?></h2>
			<?php echo JText::_('UPGRADER_LOGIN_MES'); ?>
			<p class="clearafter">
				<span><?php echo JText::_('UPGRADER_USERNAME'); ?><input name="customer_username" id="username" type="text" onchange="updater.setNextButtonState(this.form, this.form.next_step_button);" onkeyup="updater.setNextButtonState(this.form, this.form.next_step_button);" /></span>
				<span><?php echo JText::_('UPGRADER_PASSWORD'); ?><input name="customer_password" id="password" type="password"  onchange="updater.setNextButtonState(this.form, this.form.next_step_button);" onkeyup="updater.setNextButtonState(this.form, this.form.next_step_button);" /></span>
			</p>
		</div>
		<input type="hidden" name="task" value="authenticate" />
		<div class="jsn-install-admin-check" id="jsn-upgrade-old-button-wrapper">
			<hr class="jsn-horizontal-line" />
			<div class="jsn-upgrader-next-button">
				<button class="link-button disabled" id="jsn-upgrader-btn-next" disabled="disabled" onclick="this.disabled=true; this.addClass('disabled'); $('jsn-updater-link-cancel').setStyle('display', 'none'); document.frm_login.submit();" name="next_step_button"><?php echo JText::_('UPGRADER_NEXT_BUTTON'); ?></button>
			</div>
		</div>
		<input type="hidden" name="identify_name" value="<?php echo $this->imageshowCore->id;?>" />
		<input type="hidden" name="based_identified_name" value="" />
		<input type="hidden" name="edition" value="<?php echo $this->imageshowCore->edition;?>" />
		<input type="hidden" name="language" value="<?php echo $adminLang;?>" />
		<?php echo JHTML::_('form.token'); ?>
	</div>
</form>
<?php $session->set($identifier, array(), 'jsnimageshowsession'); ?>