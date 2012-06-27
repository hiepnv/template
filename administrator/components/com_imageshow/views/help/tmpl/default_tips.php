<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_tips.php 6505 2011-05-31 11:01:31Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );

?>
<h4><?php echo $this->randomContentTips['title']; ?></h4>
<p><?php echo $this->randomContentTips['content']; ?></p>

<a class="quicklink" href="#" onclick="popupWindow('<?php echo JURI::base().'index.php?option=com_imageshow&task=alltip&tmpl=component'; ?>', <?php echo JText::_('Tips');?>, 640, 480, 1)"><?php echo JText::_('See all tips'); ?></a>
