<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_messages.php 13385 2012-06-18 11:29:33Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
?>
<script language="javascript">

	function refreshMsg(){
		document.getElementById('task').value = 'refreshmessage';
		document.getElementById('frm_msg').submit();
	}
	function saveMsg(){
		document.getElementById('task').value = 'savemessage';
		document.getElementById('frm_msg').submit();
	}
</script>

<div id="jsn-main-content" class="jsn-bootstrap">
	<div id="jsn-messages">
		<form action="index.php?option=com_imageshow&controller=maintenance&type=msgs" method="POST" name="adminForm" id="frm_msg">
			<div class="jsn-message-refresh"><a href="#" title="<?php echo htmlspecialchars(JText::_('MAINTENANCE_MSG_RETRIEVE_ALL_MESSAGES_FROM_LANGUAGE_FILE_AND_SET_SHOW_STATUS_FOR_THEM')); ?>" onclick="refreshMsg();"><?php echo JText::_('MAINTENANCE_MSG_REFRESH_ALL_MESSAGE'); ?></a><span><?php echo $this->lists['arrayScreen']; ?></span></div>
			<table class="table table-bordered" border="0">
				<thead>
					<tr>
						<th width="20" class="center"><?php echo JText::_('MAINTENANCE_MSG_NUM'); ?></th>
						<th width="150" nowrap="nowrap" class="center"><?php echo JText::_('MAINTENANCE_MSG_SCREEN'); ?></th>
						<th class="center"><?php echo JText::_('MESSAGE'); ?></th>
						<th width="80" nowrap="nowrap" class="center"><?php echo JText::_('MAINTENANCE_MSG_ORDER'); ?></th>
						<th width="80" nowrap="nowrap" class="center"><?php echo JText::_('MAINTENANCE_MSG_SHOW'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
				$k = 0;
				$count = 1;
				$cloneMessages = array();
				for ($j=0, $m=count( $this->messages ); $j < $m; $j++)
				{
					$cloneRow = &$this->messages[$j];
					if($cloneRow->msg_screen == 'LAUNCH_PAD'){
						$cloneMessages [0][] = &$this->messages[$j];
					}elseif ($cloneRow->msg_screen == 'SHOWLISTS'){
						if($this->screen == 'SHOWLISTS'){
							$cloneMessages [0][] = &$this->messages[$j];
						}else{
							$cloneMessages [1][] = &$this->messages[$j];
						}
					}elseif ($cloneRow->msg_screen == 'SHOWCASES'){
						if($this->screen == 'SHOWCASES'){
							$cloneMessages [0][] = &$this->messages[$j];
						}else{
							$cloneMessages [2][] = &$this->messages[$j];
						}
					}elseif ($cloneRow->msg_screen == 'CONFIGURATION_AND_MAINTENANCE'){
						if($this->screen == 'CONFIGURATION_AND_MAINTENANCE'){
							$cloneMessages [0][] = &$this->messages[$j];
						}else{
							$cloneMessages [3][] = &$this->messages[$j];
						}
					}elseif ($cloneRow->msg_screen == 'HELP_AND_SUPPORT'){
						if($this->screen == 'HELP_AND_SUPPORT'){
							$cloneMessages [0][] = &$this->messages[$j];
						}else{
							$cloneMessages [4][] = &$this->messages[$j];
						}
					}else{
						if($this->screen == 'ABOUT'){
							$cloneMessages [0][] = &$this->messages[$j];
						}else{
							$cloneMessages [5][] = &$this->messages[$j];
						}
					}
				}
				ksort ($cloneMessages);
				$cloneMessages = array_values($cloneMessages);
				for ($z=0, $q = count( $cloneMessages ); $z < $q; $z++ )
				{
					for ($i=0, $n=count( $cloneMessages[$z] ); $i < $n; $i++)
					{
						$row = $cloneMessages[$z][$i];
				?>
					<tr class="<?php //echo "row$k"; ?>">
						<td class="center"><?php echo $count++; ?></td>
						<td><?php echo JText::_($row->msg_screen); ?></td>
						<td><span class="editlinktip hasTip" title="::<?php echo htmlspecialchars(strip_tags(JText::_('MESSAGE_'.$row->msg_screen.'_'.$row->ordering.'_PRIMARY'))); ?>"> <?php echo $objJSNUtils->wordLimiter(strip_tags(JText::_('MESSAGE_'.$row->msg_screen.'_'.$row->ordering.'_PRIMARY')), 25); ?> </span></td>
						<td class="center"><?php echo $row->ordering; ?></td>
						<td class="center"><input type="checkbox" id="status<?php echo $i; ?>" name="status[]" value="<?php echo $row->msg_id; ?>" <?php echo ($row->published == 1)?'checked="checked"':''; ?> /></td>
					</tr>
					<?php
					$k = 1 - $k;
					}
				}
				?>
				</tbody>
			</table>
			<input type="hidden" name="option" value="com_imageshow" />
			<input type="hidden" name="controller" value="maintenance" />
			<input type="hidden" name="task" value="" id="task" />
			<?php echo JHTML::_( 'form.token' ); ?>
			<div class="jsn-button-container">
				<button class="btn" type="button" onclick="saveMsg();" value="<?php echo JText::_('SAVE'); ?>"><?php echo JText::_('SAVE'); ?></button>
			</div>
		</form>
	</div>
</div>
