<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_step1.php 12469 2012-05-07 09:30:31Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
if ($this->edition == 'free')
{
	echo JText::sprintf('UPGRADER_BASIC_INFO', 'PRO');
}
else
{
	echo JText::sprintf('UPGRADER_BASIC_INFO', 'PRO UNLIMITED');
}
echo JText::_('UPGRADER_FREE_IMPORTANT_INFO');
echo '<hr class="jsn-horizontal-line" />';
if ($this->edition == 'free')
{
	echo JText::_('UPGRADER_STANDARD_BENEFITS');
	echo '<br/>';
}
echo JText::_('UPGRADER_UNLIMITED_BENEFITS');
?>
<form method="POST" action="index.php?option=com_imageshow&controller=upgrader&step=2" id="frm-upgradeinfo" name="frm_upgradeinfo" class="upgrader-from" autocomplete="off">
	<br />
	<div class="jsn-upgrader-step1">
		<a class="link-button" href="javascript: void(0);" onclick="document.frm_upgradeinfo.submit();" id="jsn-proceed-button">
		<?php
			if ($this->edition == 'free')
			{
				echo JText::sprintf('UPGRADER_PROCEED_BUTTON', 'PRO');
			}
			else
			{
				echo JText::sprintf('UPGRADER_PROCEED_BUTTON', 'PRO UNLIMITED');
			}
		?></a>
		<h4><a class="link-action" target="_blank" href="<?php echo JSN_IS_BUY_LINK; ?>">
		<?php
			if ($this->edition == 'free')
			{
				echo JText::sprintf('UPGRADER_BUY_LINK_TEXT', 'PRO');
			}
			else
			{
				echo JText::sprintf('UPGRADER_BUY_LINK_TEXT', 'PRO UNLIMITED');
			}
		?></a>
		</h4>
	</div>
	<input type="hidden" name="task" value="upgrade_proceeded" />
</form>