<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default.php 13228 2012-06-12 09:18:53Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
JToolBarHelper::title(JText::_('JSN_IMAGESHOW').': '.JText::_('UPDATER_UPDATER'));
$this->objJSNUtil->callJSNButtonMenu();
$step = JRequest::getCmd('step', '1');
?>
<div id="jsn-upgrader-container">
	<div id="jsn-upgrader-wrapper">
	<a id="jsn-updater-link-cancel" class="jsn-updater-link-cancel link-action" href="index.php?option=com_imageshow"><?php echo JText::_('UPDATER_BUTTON_CANCEL'); ?></a>
		<h1 class="jsn-element-heading"><?php echo JText::sprintf('UPDATER_UPDATE_HEADING', 'JSN '.@$this->infoXmlDetail['realName']. ' '. @$this->infoXmlDetail['edition']); ?></h1>
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

