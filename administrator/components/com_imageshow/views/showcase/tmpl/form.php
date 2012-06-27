<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: form.php 12492 2012-05-08 09:29:01Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
JHTML::_('behavior.tooltip');
$edit 	= JRequest::getVar('edit',true);
$editor = JFactory::getEditor();
$cid 	= JRequest::getVar( 'cid', array(0), 'get', 'array' );
JToolBarHelper::title(JText::_('JSN_IMAGESHOW').': '.JText::_('SHOWCASE_SHOWCASE_SETTINGS'), 'showcase-settings');
JToolBarHelper::apply();
JToolBarHelper::save();
JToolBarHelper::cancel('cancel', 'JTOOLBAR_CLOSE');
JToolBarHelper::divider();
$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
$objJSNUtils->callJSNButtonMenu();
$showCaseID = (int) $this->items->showcase_id;
$user 		= JFactory::getUser();
$task		= JRequest::getVar('task');
?>
<script language="javascript" type="text/javascript">
	window.addEvent('domready', function()
	{
		JSNISImageShow.simpleSlide('jsn-showcase-detail-arrow',
				'jsn-showcase-detail-slide',
				'jsn-showcase-detail-arrow',
				'jsn-showcase-detail-title',
				'jsn-element-heading-arrow-collapse',
				'jsn-element-heading-title');

		$('jsn-showcase-detail-arrow').addEvent('click', function()
		{
			JSNISImageShow.setCookieHeadingTitleStatus('jsn-heading-title-showcase-<?php echo $user->id; ?>');
		});

		var showcaseHeadingTitleStatus = JSNISUtils.getCookie('jsn-heading-title-showcase-<?php echo $user->id; ?>');

		if (showcaseHeadingTitleStatus == 'close')
		{
			$('jsn-showcase-detail-arrow').fireEvent('click');
			JSNISUtils.setCookie('jsn-heading-title-showcase-<?php echo $user->id; ?>', 'close', 15);
		}
	});

	var original_value = '';
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;

		if (pressbutton == 'cancel')
		{
			submitform( pressbutton );
			return;
		}

		if (form.showcase_title.value == "")
		{
			alert( "<?php echo JText::_('SHOWCASE_REQUIRED_FIELD_TITLE_CANNOT_BE_LEFT_BLANK', true); ?>");
		} else {
			submitform( pressbutton );
		}
	}

	function getInputValue(object)
	{
		original_value = object.value;
	}

	function checkInputValue(object, percent)
	{
		var patt;
		var form 		= document.adminForm;
		var msg;
		if(percent == 1)
		{
			patt=/^[0-9]+(\%)?$/;
			msg = "<?php echo JText::_('SHOWCASE_ALLOW_ONLY_DIGITS_AND_THE_PERCENTAGE_CHARACTER', true); ?>";
		}
		else
		{
			patt=/^[0-9]+$/;
			msg = "<?php echo JText::_('SHOWCASE_ALLOW_ONLY_DIGITS', true); ?>";
		}
		if(!patt.test(object.value))
		{
			alert (msg);
			object.value = original_value;
			return;
		}
	}

	function checkOverallWidth()
	{
		var width	= document.adminForm.general_overall_width;
		var unit	= document.getElementById('overall_width_dimension');

		if (width.value > 100 && unit.value == '%')
		{
			alert("<?php echo JText::_('SHOWCASE_ALLOW_ONLY_VALUE_SMALLER_OR_EQUALLER_THAN_100');?>");
			width.value = 100;
		}
		return true;
	}
</script>
<!--[if IE 7]>
	<link href="<?php echo JURI::base();?>components/com_imageshow/assets/css/fixie7.css" rel="stylesheet" type="text/css" />
<![endif]-->

<form action="index.php?option=com_imageshow&controller=showcase" method="POST" name="adminForm" id="adminForm">
<?php
	$uri	        = JURI::getInstance();
	$base['prefix'] = $uri->toString( array('scheme', 'host', 'port'));
	$base['path']   =  rtrim(dirname(str_replace(array('"', '<', '>', "'",'administrator'), '', $_SERVER["PHP_SELF"])), '/\\');
	$url 			= $base['prefix'].$base['path'].'/';
?>
	<div id="jsn-showcase-detail-heading">
		<h3 class="jsn-element-heading">
			<?php echo JText::_('SHOWCASE_TITLE_SHOWCASE_DETAILS'); ?>
			<span id="jsn-showcase-detail-title" class="jsn-element-heading-title"><?php echo ($this->generalData['generalTitle'] != '') ? ': '.$this->generalData['generalTitle'] : ''; ?></span>
			<span id="jsn-showcase-detail-arrow" class="jsn-element-heading-arrow "></span>
		</h3>
	</div>
	<table id="jsn-showcase-detail-slide" class="jsn-showcase-settings" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td valign="top" style="width: 50%;"><fieldset>
					<legend><?php echo JText::_('SHOWCASE_GENERAL_GENERAL');?></legend>
					<table class="admintable">
						<?php
							if($showCaseID != 0){
						?>
						<tr>
							<td class="key"><?php echo JText::_('ID');?></td>
							<td><?php echo $showCaseID; ?></td>
						</tr>
						<?php
							}
						?>
						<tr>
							<td class="key"><?php echo JText::_('SHOWCASE_GENERAL_TITLE');?></td>
							<td><input type="text" style="width: 96%;" name="showcase_title" id="showcase_title" value="<?php echo $this->generalData['generalTitle']; ?>" />
								<font color="Red"> *</font></td>
						</tr>
						<tr>
							<td class="key"><?php echo JText::_('SHOWCASE_GENERAL_PUBLISHED');?></td>
							<td><?php echo $this->lists['published']; ?></td>
						</tr>
						<tr>
							<td class="key"><?php echo JText::_('SHOWCASE_GENERAL_ORDER');?></td>
							<td><?php echo $this->lists['ordering']; ?></td>
						</tr>
					</table>
				</fieldset>
			</td>
			<td valign="top">
				<fieldset>
					<legend><?php echo JText::_('SHOWCASE_GENERAL_DIMENSION'); ?></legend>
					<table class="admintable">
						<tbody>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo htmlspecialchars(JText::_('SHOWCASE_GENERAL_OVERALL_WIDTH'));?>::<?php echo htmlspecialchars(JText::_('SHOWCASE_GENERAL_OVERALL_WIDTH_DESC')); ?>"><?php echo JText::_('SHOWCASE_GENERAL_OVERALL_WIDTH'); ?></span></td>
								<td><input type="text" size="5" name="general_overall_width" value="<?php echo (int) $this->generalData['generalWidth']; ?>" onchange="checkInputValue(this, 0); checkOverallWidth();" onfocus="getInputValue(this);" />&nbsp;<?php echo $this->lists['overallWidthDimension'];?></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo htmlspecialchars(JText::_('SHOWCASE_GENERAL_OVERALL_HEIGHT'));?>::<?php echo htmlspecialchars(JText::_('SHOWCASE_GENERAL_OVERALL_HEIGHT_DESC')); ?>"><?php echo JText::_('SHOWCASE_GENERAL_OVERALL_HEIGHT'); ?></span></td>
								<td><input type="text" size="5" name="general_overall_height" value="<?php echo $this->generalData['generalHeight']; ?>" onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" />&nbsp;<?php echo JText::_('px'); ?></td>
							</tr>
						</tbody>
					</table>
				</fieldset>
			</td>
		</tr>
	</table>
	<div id="jsn-showcase-theme-wrapper">
	<?php
		$objShowcaseTheme 	= JSNISFactory::getObj('classes.jsn_is_showcasetheme');
		$objShowcase		= JSNISFactory::getObj('classes.jsn_is_showcase');
		$themes 			= $objShowcaseTheme->listThemes(false);
		$countTheme 		= count($themes);
		$theme				= JRequest::getVar('theme');
		$themeProfile 		= $objShowcaseTheme->getThemeProfile($this->items->showcase_id);
		$totalThemes 		= count($this->needUpdateList) + count($this->needInstallList);
		$text				= '';
		if (!is_null($themeProfile) || $theme!='')
		{
			$text = JText::_('TITLE_SHOWCASE_THEME_SETTINGS');
		}
		else
		{
			$text = JText::_('TITLE_SHOWCASE_SELECT_THEME');
		}
	?>
		<div id="jsn-showcase-theme-heading">
			<h3 class="jsn-element-heading"><?php echo $text; ?>
				<?php if (($totalThemes > 1 && $task == 'edit' && !is_null($themeProfile)) || ($theme != '')) { ?>
				<a href="javascript: void(0);" onclick="JSNISImageShow.confirmChangeTheme('<?php echo JText::_('SHOWCASE_INSTALL_WARNING_CHANGE_THEME', true)?>', <?php echo $this->items->showcase_id?>);" class="link-button"><span><?php echo JText::_('SHOWCASE_CHANGE_THEME', true); ?></span></a>
				<?php } ?>
			</h3>
		</div>
		<?php

		$divOpenTag 	= '<div class="jsn-showcase-selecttheme-wrapper">';
		$divCloseTag 	= '</div>';
		if (empty($showCaseID)) {
			echo '
				<div id="jsn-no-showcase">
					<p class="jsn-showcase-empty-warning">'.JText::_('SHOWCASE_PLEASE_SAVE_THIS_SHOWCASE_BEFORE_SELECTING_THEME').'</p>
					<div class="jsn-button-container">
						<a id="jsn-go-link" class="link-button" href="javascript: javascript:Joomla.submitbutton('."'apply'".');">'.JText::_('SHOWCASE_SAVE_SHOWCASE').'</a>
					</div>
				</div>
				';
		}
		else if ($task == 'add')
		{
			$updateAdd = false;
			if($totalThemes > 1 && $theme == '')
			{
				echo $divOpenTag;
				echo $this->loadTemplate('themes');
				if (count($this->needUpdateList) && count($this->needInstallList)) echo '<hr />';
				echo $this->loadTemplate('install_themes');
				echo $divCloseTag;
			}
			elseif ($totalThemes <= 1 && $theme == '')
			{
				if (count($this->needUpdateList))
				{
					foreach ($this->needUpdateList as $value)
					{
						if ($value->identified_name == @$themes[0]['element'])
						{
							$updateAdd = $value->needUpdate;
							break;
						}
					}
				}
				if ($updateAdd)
				{
					echo $divOpenTag;
					echo $this->loadTemplate('themes');
					echo $divCloseTag;
				}
				else
				{
					$objShowcaseTheme->loadThemeByName(@$themes[0]['element']);
				}
			}
			else
			{
				$objShowcaseTheme->loadThemeByName($theme);
			}
		}
		else
		{
			$update = false;
			if (isset($themeProfile->theme_name) && $objShowcaseTheme->checkThemeExist($themeProfile->theme_name))
			{
				$objShowcaseTheme->loadThemeByName($themeProfile->theme_name, $themeProfile->theme_id);
			}
			else
			{
				if($theme != '')
				{
					$objShowcaseTheme->loadThemeByName($theme);
				}
				else
				{
					if ($totalThemes <= 1 && $theme == '' && count($this->needUpdateList) > 0)
					{
						$objShowcaseTheme->loadThemeByName(@$themes[0]['element']);
					}
					else
					{
						echo $divOpenTag;
						echo $this->loadTemplate('themes');
						if (count($this->needUpdateList) && count($this->needInstallList)) echo '<hr />';
						echo $this->loadTemplate('install_themes');
						echo $divCloseTag;
					}
				}
			}
		}
	?>
	</div>
	<input type="hidden" name="redirectLinkTheme" value="" />
	<input type="hidden" id="redirectLink" name="redirectLink" value="<?php echo ((int)$this->items->showcase_id == 0)?'index.php?option=com_imageshow&controller=showcase&task=add':'index.php?option=com_imageshow&controller=showcase&task=edit&cid[]='.(int) $this->items->showcase_id.'&theme='; ?>" />
	<input type="hidden" name="option" value="com_imageshow" />
	<input type="hidden" name="controller" value="showcase" />
	<input type="hidden" name="cid[]" value="<?php echo (int) $this->items->showcase_id; ?>" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_('form.token'); ?>
</form>
<?php include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'footer.php'); ?>