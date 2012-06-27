<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default.php 12469 2012-05-07 09:30:31Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
JToolBarHelper::title(JText::_('JSN_IMAGESHOW').': '.JText::_('UPGRADER_UPGRADER'));
$step = JRequest::getCmd('step', '1');
?>
<div id="jsn-upgrader-container">
	<div id="jsn-upgrader-wrapper">
		<h1><?php
			if ($this->edition == 'free')
			{
				echo JText::_('UPGRADER_UPGRADE_TO_PRO');
			}
			else
			{
				echo JText::_('UPGRADER_UPGRADE_TO_PRO_UNLIMITED');
			}
			?><span id="jsn-upgrader-cancel"><a href="index.php?option=com_imageshow" class="link-action"><?php echo JText::_('UPGRADER_UPGRADE_CANCEL'); ?></a></span></h1>
		<?php
			switch ($step)
			{
				case '2':
						echo $this->loadTemplate('step2');
					break;
				case '3':
						echo $this->loadTemplate('step3');
					break;
				case '1':
				default:
					echo $this->loadTemplate('step1');
					break;
			}
		?>
	</div>
</div>
<form enctype="multipart/form-data" action="index.php?option=com_imageshow&controller=installer" method="post" name="jsn_frm_upgrader_install_core" id="jsn_frm_upgrader_install_core">
    <input type="hidden" name="package_path" value="" />
	<input type="hidden" name="task" value="installImageShowCoreByUpgrade" />
	<input type="hidden" name="option" value="com_imageshow" />
	<?php echo JHTML::_('form.token'); ?>
</form>