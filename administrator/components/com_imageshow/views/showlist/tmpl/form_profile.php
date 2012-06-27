<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: form_profile.php 11484 2012-03-02 04:29:15Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
global $mainframe;
$showlistID 	= JRequest::getInt('showlist_id', 0);
$sourceIdentify = JRequest::getVar('source_identify', '');
$sourceType		= JRequest::getVar('image_source_type', '');
$return			= JRequest::getVar('return', '', 'get');
if (base64_decode($return) != @$_SERVER['HTTP_REFERER'])
{
	$mainframe->redirect(base64_decode($return));
	return;
}
$availableProfile = array();

if ($sourceIdentify != '') {
	$imageSource = JSNISFactory::getSource($sourceIdentify, $sourceType, $showlistID);
	$availableProfile = $imageSource->getAvaiableProfiles();
}
$availableProfile = array_reverse($availableProfile);
$exsitedAvailableProfile = count($availableProfile);
$availableProfile[] = array('value' => 0,
				'text' => ' - '.JText::_('SHOWLIST_PROFILE_SELECT_PROFILE').' - ');
$availableProfile = array_reverse($availableProfile);
?>
<script type="text/javascript">
	function onSubmit()
	{
		if ($('profile_type_new') != null && $('profile_type_new').checked)
		{
			$('task').value = 'createprofile';
			if ($('external_source_id')){
				$('external_source_id').value = 0;
			}
			JSNISImageShow.submitFormProfile();
		}
		if ($('profile_type_available') != null && $('profile_type_available').checked)
		{
			$('task').value = 'changeprofile';
			var form = document.adminForm;
			if (form.external_source_id.selectedIndex == 0)
			{
				alert( "<?php echo JText::_('SHOWLIST_PROFILE_SELECT_AVAILABLE_PROFILE', true); ?>");
				return;
			}

			JSNISImageShow.submitForm();
		}
	}

	JSNISImageShow.submitForm = function()
	{
		if ($('submit-available-profile-form') != null)
		{
			$('submit-available-profile-form').disabled = true;
			$('submit-available-profile-form').addClass('disabled');
		}

		JSNISImageShow.submitProfile('adminForm');
	}

	window.addEvent('domready', function()
	{
		var restoreBackup	= new JSNISAccordions('jsn-profile-panel', {multiple: false, activeClass: 'down', showFirstElement: true, durationEffect: 300});

		var availbleProfile = $('external_source_id');

		if (availbleProfile)
		{
			availbleProfile.addEvent('change', function(el)
			{
				if (availbleProfile.value == 0) {
					$('submit-available-profile-form').addClass('disabled');
				} else {
					$('submit-available-profile-form').removeClass('disabled');
				}
			});
		}

		var newProfileParams = $$('.jsn-new-profile-input');

		if (newProfileParams.length > 0)
		{
			newProfileParams.each(function(el)
			{
				el.addEvent('change', function ()
				{
					var countElement = 0;

					$$('.jsn-new-profile-input').each(function(element)
					{
						if (element.value != '') {
							countElement++;
						}
					});

					if (countElement == newProfileParams.length) {
						$('submit-new-profile-form').removeClass('disabled');
					} else {
						$('submit-new-profile-form').addClass('disabled');
					}
				});
			});
		}
	});
</script>
<div id="jsn-showlist-install-sources-verify">
<h3 class="jsn-element-heading"><?php echo JText::_('SHOWLIST_PROFILE_SELECT_IMAGE_SOURCE_PROFILE')?></h3>
<form name='adminForm' id='adminForm' action="index.php" method="post" onsubmit="return false;">
<div id="jsn-profile-panel" class="jsn-accordion">
	<?php if ($exsitedAvailableProfile) { ?>
	<div class="jsn-accordion-title">
		<input id="profile_type_available" class="jsn-accordion-radio" type="radio" value="available" checked="checked" name="profile_type">
		<?php echo JText::_('SHOWLIST_PROFILE_SELECT_AVAILABLE_PROFILE')?>
	</div>
	<div class="jsn-accordion-pane">
		<div id="jsn-showlist-available-profile">
			<div id="jsn-showlist-profile-select">
				<?php echo JHTML::_('select.genericList', $availableProfile, 'external_source_id', 'class="inputbox jsn-available-profile-input"', 'value', 'text');?>
			</div>
			<div id="jsn-showlist-profile-button" class="jsn-button-container">
				<a onclick="onSubmit();" id="submit-available-profile-form" class="link-button disabled"><?php echo JText::_('SHOWLIST_PROFILE_SELECT'); ?></a>
			</div>
		</div>
	</div>
	<?php } ?>
	<div class="jsn-accordion-title">
		<input id="profile_type_new" class="jsn-accordion-radio" type="radio" value="available" name="profile_type" <?php echo (!$exsitedAvailableProfile)?' style="display: none;" checked="checked"':'';?>>
		<?php echo JText::_('SHOWLIST_PROFILE_CREATE_NEW_PROFILE')?>
	</div>
	<div class="jsn-accordion-pane">
		<div id="jsn-showlist-new-profile">
			<div id="jsn-showlist-profile-params">
			<?php
				$this->addTemplatePath(JPATH_PLUGINS.DS.'jsnimageshow'.DS.'source'.$sourceIdentify.DS.'views'.DS.'showlist'.DS.'tmpl');
				echo $this->loadTemplate($sourceIdentify);
			?>
			</div>
			<div id="jsn-showlist-profile-button" class="jsn-button-container">
				<a onclick="onSubmit();" id="submit-new-profile-form" class="link-button disabled"><?php echo JText::_('SHOWLIST_PROFILE_CREATE'); ?></a>
				<span class="jsn-source-icon-loading" id="jsn-create-source"></span>
			</div>
			
		</div>
	</div>
</div>
<input type="hidden" id="task" name="task" value="" />
<input type="hidden" name="source_identify" value="<?php echo $sourceIdentify; ?>" />
<input type="hidden" name="image_source_type" value="external" />
<input type="hidden" name="showlist_id" value="<?php echo $showlistID; ?>"/>
<input type="hidden" name="option" value="com_imageshow" />
<input type="hidden" name="controller" value="showlist" />
<?php echo JHTML::_('form.token'); ?>
</form>
</div>