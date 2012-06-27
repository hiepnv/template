<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: form_showlist.php 13385 2012-06-18 11:29:33Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
$objJSNUtils 	= JSNISFactory::getObj('classes.jsn_is_utils');
JHTML::_('behavior.tooltip');
JToolBarHelper::title( JText::_('JSN_IMAGESHOW').': '.JText::_('SHOWLIST_SHOWLIST_SETTINGS'), 'showlist-settings' );
JToolBarHelper::apply();
JToolBarHelper::save();
JToolBarHelper::cancel('cancel', 'JTOOLBAR_CLOSE');
JToolBarHelper::divider();
$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
$objJSNUtils->callJSNButtonMenu();
$showListID = (int) $this->items->showlist_id;
$task = JRequest::getVar('task');
$user = JFactory::getUser();
$showlistID = JRequest::getVar('cid');
$showlistID = $showlistID[0];
if ($task == 'edit')
{
	//echo "<div id=\"jsn-showlist-toolbar-css\"><style>#toolbar-save,#toolbar-apply{display:none;}</style></div>";
}

?>
<script language="javascript" type="text/javascript">
	function submitform(pressbutton)
	{
		JSNISImageShow.checkThumbCallBack = function()
		{
			if (pressbutton) {
				document.adminForm.task.value = pressbutton;
			}
			if (typeof document.adminForm.onsubmit == "function") {
				document.adminForm.onsubmit();
			}
			if (typeof document.adminForm.fireEvent == "function") {
				document.adminForm.fireEvent('submit');
			}
			document.adminForm.submit();
		}

		if (pressbutton == 'save') {
			JSNISImageShow.getScriptCheckThumb(<?php echo (int)$showlistID; ?>);
		} else {
			JSNISImageShow.checkThumbCallBack();
		}
	}

	Joomla.submitbutton = function(pressbutton)
	{
		var form 		= document.adminForm;
		var link  		= form.showlist_link.value;
		var flexElement = document.getElementById('flash');
		var task 		= '<?php echo $task; ?>';
		if (pressbutton == 'cancel')
		{
			submitform( pressbutton );
			return;
		}

		if (form.showlist_title.value == "")
		{
			alert( "<?php echo JText::_('SHOWLIST_SHOWLIST_MUST_HAVE_A_TITLE', true); ?>");
			return;
		}
		else
		{
			if(task != 'add')
			{
				try
				{
					if (flexElement != null) {
						flexElement.saveFlex(pressbutton);
					} else {
						submitform( pressbutton );
					}
				}
				catch(e){}
			}
			else
			{
				submitform( pressbutton );
			}
		}
	}

	function selectArticle_auth_article_id(id, title, catid)
	{
		document.id("aid_name").value = title;
		document.id("aid_id").value = id;
		SqueezeBox.close();
	}

	function jInsertFieldValue(value,id)
	{
		var old_id = document.getElementById(id).value;
		if (old_id != id)
		{
			document.getElementById(id).value = value;
		}
	}

	document.addEvent('domready', function()
	{
		JSNISImageShow.simpleSlide('jsn-showlist-detail-arrow',
				'jsn-showlist-detail-slide',
				'jsn-showlist-detail-arrow',
				'jsn-showlist-detail-title',
				'jsn-element-heading-arrow-collapse',
				'jsn-element-heading-title');

		$('jsn-showlist-detail-arrow').addEvent('click', function()
		{
			JSNISImageShow.setCookieHeadingTitleStatus('jsn-heading-title-showlist-<?php echo $user->id . (int) $this->items->showlist_id; ?>');
		});

		var showlistHeadingTitleStatus = JSNISUtils.getCookie('jsn-heading-title-showlist-<?php echo $user->id . (int) $this->items->showlist_id; ?>');

		if (showlistHeadingTitleStatus == 'close')
		{
			$('jsn-showlist-detail-arrow').fireEvent('click');
			JSNISUtils.setCookie('jsn-heading-title-showlist-<?php echo $user->id . (int) $this->items->showlist_id; ?>', 'close', 15);
		}
	});
</script>
<?php
$objJSNMsg = JSNISFactory::getObj('classes.jsn_is_displaymessage');
echo $objJSNMsg->displayMessage('SHOWLISTS');
?>
<form name="adminForm" id="adminForm" action="index.php?option=com_imageshow&controller=showlist" method="post">
	<div id="jsn-showlist-detail-heading">
		<h3 class="jsn-element-heading">
			<?php echo JText::_('SHOWLIST_TITLE_SHOWLIST_DETAILS');?>
			<span id="jsn-showlist-detail-arrow" class="jsn-element-heading-arrow"></span>
			<span id="jsn-showlist-detail-title" class="jsn-element-heading-title"><?php echo ($this->items->showlist_title != '') ? ': '.htmlspecialchars($this->items->showlist_title) : ''; ?></span>
		</h3>
	</div>
	<table id="jsn-showlist-detail-slide" class="jsn-showlist-settings" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td valign="top" style="width: 50%;"><fieldset>
					<legend> <?php echo JText::_('SHOWLIST_GENERAL');?> </legend>
					<div class="jsn-bootstrap">
					<table class="admintable" border="0">
						<tbody>
							<?php
					if($showListID != 0){
				?>
							<tr>
								<td class="key"><?php echo JText::_('ID');?></td>
								<td><?php echo $showListID; ?></td>
							</tr>
							<?php
					}
				?>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo htmlspecialchars(JText::_('SHOWLIST_TITLE_SHOWLIST'));?>::<?php echo htmlspecialchars(JText::_('SHOWLIST_DES_SHOWLIST')); ?>"><?php echo JText::_('SHOWLIST_TITLE_SHOWLIST');?></span><span class="jsn-require-star"> *</span></td>
								<td><input class="span8" type="text" value="<?php echo htmlspecialchars($this->items->showlist_title);?>" name="showlist_title"/>
								</td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo htmlspecialchars(JText::_('SHOWLIST_TITLE_PUBLISHED'));?>::<?php echo htmlspecialchars(JText::_('SHOWLIST_DES_PUBLISHED')); ?>"><?php echo JText::_('SHOWLIST_TITLE_PUBLISHED');?></span></td>
								<td><?php echo $this->lists['published']; ?></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo htmlspecialchars(JText::_('SHOWLIST_TITLE_ORDER'));?>::<?php echo htmlspecialchars(JText::_('SHOWLIST_DES_ORDER')); ?>"><?php echo JText::_('SHOWLIST_TITLE_ORDER');?></span></td>
								<td><?php echo $this->lists['ordering']; ?></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo htmlspecialchars(JText::_('SHOWLIST_TITLE_HITS'));?>::<?php echo htmlspecialchars(JText::_('SHOWLIST_DES_HITS')); ?>"><?php echo JText::_('SHOWLIST_HITS');?></span></td>
								<td><input class="span1" type="text" name="hits" value="<?php echo ($this->items->hits!='')?$this->items->hits:0;?>" /></td>
							</tr>
							<tr>
								<td valign="top" class="key"><span class="editlinktip hasTip" title="<?php echo htmlspecialchars(JText::_('SHOWLIST_TITLE_DESCRIPTION'));?>::<?php echo htmlspecialchars(JText::_('SHOWLIST_DES_DESCRIPTION')); ?>"><?php echo JText::_('SHOWLIST_TITLE_DESCRIPTION');?></span></td>
								<td><textarea class="span8" name="description" rows="3"><?php echo $this->items->description; ?></textarea></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo htmlspecialchars(JText::_('SHOWLIST_TITLE_LINK'));?>::<?php echo htmlspecialchars(JText::_('SHOWLIST_DES_LINK')); ?>"><?php echo JText::_('SHOWLIST_LINK');?></span></td>
								<td><input class="span8" type="text" name="showlist_link" value="<?php echo htmlspecialchars($objJSNUtils->decodeUrl($this->items->showlist_link)); ?>" /></td>
							</tr>
						</tbody>
					</table>
					</div>
				</fieldset></td>
			<td valign="top">
				<fieldset>
					<legend><?php echo JText::_('SHOWLIST_IMAGES_DETAILS_OVERRIDE'); ?></legend>
					<table class="admintable" border="0">
						<tbody>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo htmlspecialchars(JText::_('SHOWLIST_OVERRIDE_TITLE'));?>::<?php echo htmlspecialchars(JText::_('SHOWLIST_OVERRIDE_TITLE_DESC')); ?>"><?php echo JText::_('SHOWLIST_OVERRIDE_TITLE');?></span></td>
								<td><?php echo $this->lists['overrideTitle']; ?></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo htmlspecialchars(JText::_('SHOWLIST_OVERRIDE_DESCRIPTION'));?>::<?php echo htmlspecialchars(JText::_('SHOWLIST_OVERRIDE_DESCRIPTION_DESC')); ?>"><?php echo JText::_('SHOWLIST_OVERRIDE_DESCRIPTION');?></span></td>
								<td><?php echo $this->lists['overrideDesc']; ?></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('SHOWLIST_OVERRIDE_LINK');?>::<?php echo JText::_('SHOWLIST_OVERRIDE_LINK_DESC'); ?>"><?php echo JText::_('SHOWLIST_OVERRIDE_LINK');?></span></td>
								<td><?php echo $this->lists['overrideLink']; ?></td>
							</tr>
						</tbody>
					</table>
				</fieldset>
				<fieldset>
					<legend><?php echo JText::_('SHOWLIST_ACCESS_PERMISSION'); ?></legend>
					<div class="jsn-bootstrap">
					<table class="admintable" border="0">
						<tbody>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('SHOWLIST_TITLE_ACCESS_LEVEL');?>::<?php echo JText::_('SHOWLIST_DES_ACCESS_LEVEL'); ?>"><?php echo JText::_('SHOWLIST_TITLE_ACCESS_LEVEL');?></span></td>
								<td>
									<select name="access" class="inputbox">
									<?php echo JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->items->access);?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('SHOWLIST_TITLE_AUTHORIZATION_MESSAGE');?>::<?php echo JText::_('SHOWLIST_DES_AUTHORIZATION_MESSAGE'); ?>"><?php echo JText::_('SHOWLIST_TITLE_AUTHORIZATION_MESSAGE');?></span></td>
								<td class="paramlist_value"><?php echo $this->lists['authorizationCombo']; ?>
									<div style="<?php echo ($this->items->authorization_status == 1)?'display:"";':'display:none;'; ?>" id="wrap-aut-article">
										<span class="button-wrapper"><input class="span6 jsn-readonly" type="text" id="aid_name" value="<?php echo @$this->items->aut_article_title;?>" readonly="readonly" /></span>
										<span class="button-wrapper"><a class="btn jsn-modal" rel="{handler: 'iframe', size: {x: 651, y: 375}}" href="index.php?option=com_content&view=articles&layout=modal&tmpl=component&function=selectArticle_auth_article_id" title="Select Content"><?php echo JText::_('SHOWLIST_SELECT');?></a></span>
										<input type="hidden" id="aid_id" name="alter_autid" value="<?php echo $this->items->alter_autid;?>" />
									</div>
								</td>
							</tr>
						</tbody>
					</table>
					</div>
				</fieldset>
				<fieldset>
					<legend><?php echo JText::_('SHOWLIST_MISC'); ?></legend>
					<div class="jsn-bootstrap">
					<table class="admintable" border="0">
						<tbody>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo htmlspecialchars(JText::_('SHOWLIST_IMAGES_LOADING_ORDER_TITLE'));?>::<?php echo htmlspecialchars(JText::_('SHOWLIST_IMAGES_LOADING_ORDER_DESC')); ?>"><?php echo JText::_('SHOWLIST_IMAGES_LOADING_ORDER_TITLE');?></span></td>
								<td><?php echo $this->lists['imagesLoadingOrder']; ?></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo htmlspecialchars(JText::_('SHOWLIST_SHOW_EXIF_TITLE'));?>::<?php echo htmlspecialchars(JText::_('SHOWLIST_SHOW_EXIF_DESC')); ?>"><?php echo JText::_('SHOWLIST_SHOW_EXIF_TITLE');?></span></td>
								<td><?php echo $this->lists['showExifData']; ?></td>
							</tr>
						</tbody>
					</table>
					</div>
				</fieldset></td>
	</table>
	<input type="hidden" name="cid[]" value="<?php echo (int) $this->items->showlist_id;?>" />
	<input type="hidden" name="option" value="com_imageshow" />
	<input type="hidden" name="controller" value="showlist" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" id="redirectLink" name="redirectLink" value="<?php echo ((int) $this->items->showlist_id)?'index.php?option=com_imageshow&controller=showlist&task=edit&cid[]='.(int) $this->items->showlist_id:'';?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>