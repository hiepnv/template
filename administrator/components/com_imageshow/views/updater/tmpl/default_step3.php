<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_step3.php 13252 2012-06-13 01:52:07Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
global $mainframe;
$session 					= JFactory::getSession();
$identifierIsCustomer		= md5('jsn_updater_jsn_imageshow_is_customer');
$identifier					= md5('jsn_updater_jsn_imageshow');
$isCustomer					= $session->get($identifierIsCustomer, false, 'jsnimageshowsession');
$sessionValue   			= $session->get($identifier, array(), 'jsnimageshowsession');
if (count($sessionValue))
{
	$customerName = (string) $sessionValue['customer_username'];
	$customerPass = (string) $sessionValue['customer_password'];
}
else
{
	$customerName 	= (string) JRequest::getVar('customer_username');
	$customerPass 	= JRequest::getVar('customer_password', '', 'post', 'string', JREQUEST_ALLOWRAW);
}


$elementID 		= JRequest::getInt('element_id');
$type 			= JRequest::getVar('type');
$objVersion		= new JVersion();
$authentication =  false;

if ($this->imageshowCore->commercial && $this->imageshowCore->needUpdate ) {
	$authentication = true;
}

$dataUpdate = array();
$core 		= array();
$themes 	= array();
$sources 	= array();
$html = '';
$dataUpdate['wait_text'] 	= JText::_('UPGRADER_UPGRADE_INSTALL_WAIT_TEXT', true);
$dataUpdate['process_text']	= JText::_('UPGRADER_UPGRADE_INSTALL_PROCESS_TEXT', true);
foreach ($this->themes as $key => $theme)
{
	if ($theme->needUpdate)
	{
		if ($theme->authentication) $authentication = true;
		$themeInfoUpdate 					= new stdClass();
		$themeInfoUpdate->elementID 		= 'jsn-update-element-theme-'.$key;
		$themeInfoUpdate->identify_name 	= $theme->identified_name;
		$themeInfoUpdate->edition 			= '';
		$themeInfoUpdate->joomla_version 	= $objVersion->RELEASE;
		$themes[] 							= $themeInfoUpdate;
		$html .= '<li id="jsn-update-element-theme-'.$key.'">'.JText::_('UPDATER_THEME').' "'. $theme->name .'"</li>';
	}
}

foreach ($this->sources as $key => $source)
{
	if ($source->needUpdate)
	{
		if ($source->authentication) $authentication = true;
		$sourceInfoUpdate 					= new stdClass();
		$sourceInfoUpdate->elementID 		= 'jsn-update-element-source-'.$key;
		$sourceInfoUpdate->identify_name 	= $source->identified_name;
		$sourceInfoUpdate->edition 			= '';
		$sourceInfoUpdate->joomla_version 	= $objVersion->RELEASE;
		$sources[] 							= $sourceInfoUpdate;
		$html .= '<li id="jsn-update-element-source-'.$key.'">'.JText::_('UPDATER_SOURCE').' "'. $source->name.'"</li>';
	}
}
if ($authentication)
{
	if (!$isCustomer || $customerName == '' || $customerPass == '')
	{
		$mainframe->redirect('index.php?option=com_imageshow&controller=updater');
	}
}
?>
<div class="jsn-upgrader-step3">
	<h2>
		<?php
		echo JText::sprintf('UPDATER_HEADING_STEP2', ($authentication)?'2':'1');
		?>
	</h2>
	<div id="jsn-upgrader-upgrading">
	<?php if (count($sources) || count($themes) || $this->imageshowCore->needUpdate){ ?>
		<p><?php echo JText::_('UPGRADER_UPGRADE_UPGRADING'); ?></p>
		<ul>

			<?php if ($this->imageshowCore->needUpdate): ?>
				<?php
					$coreInfoUpdate 				= new stdClass();
					$coreInfoUpdate->elementID 		= 'jsn-update-element-core';
					$coreInfoUpdate->identify_name 	= $this->imageshowCore->id;
					$coreInfoUpdate->edition 		= $this->imageshowCore->edition;
					$coreInfoUpdate->joomla_version = $objVersion->RELEASE;
					$core[] 						= $coreInfoUpdate;
				?>
				<li id="jsn-update-element-core"><?php echo JText::_('UPDATER_JSN_IMAGESHOW_CORE');?></li>
			<?php endif;?>
			<?php
				if ($this->imageshowCore->needUpdate == false) {
					echo $html;
				}
			?>
		</ul>
		<div id="jsn-updater-successfully">
			<?php echo JText::_('UPDATER_INSTALLATION_CONGRATULATION')?>
		</div>
		<div id="jsn-updater-buttons" class="jsn-button-container">
			<?php
				$jsnObjLightCart = JSNISFactory::getObj('classes.jsn_is_lightcart');
				$params = JComponentHelper::getParams('com_languages');
				$adminLang = $params->get('administrator', 'en-GB');
				$dataUpdate['language'] = $adminLang;
				$dataUpdate['lightCartErrorCode'] = $jsnObjLightCart->getErrorCode();
				$dataUpdate['linkCancelID'] = 'jsn-updater-link-cancel';
				$dataUpdate['successID'] 	= 'jsn-updater-successfully';
				$dataUpdate['loginFormID'] 	= 'jsn-updater-login-form';
				$dataUpdate['buttonID'] 	= 'jsn-updater-buttons';
				$dataUpdate['languageText']['downloadText']				= JText::_('UPDATER_DOWNLOAD_INSTALLATION_PACKAGE', true);
				$dataUpdate['languageText']['installText']				= JText::_('UPDATER_INSTALL_PACKAGE', true);
				$dataUpdate['languageText']['manualThenSelectItText']	= JText::_('MANUAL_THEN_SELECT_IT', true);
				$dataUpdate['languageText']['manualInstallButton']		= JText::_('MANUAL_INSTALL_BUTTON', true);
				$dataUpdate['languageText']['manualDownloadText']		= JText::_('MANUAL_DOWNLOAD', true);
				$dataUpdate['languageText']['dowloadInstallationPackageText']		= JText::_('MANUAL_DOWNLOAD_INSTALLATION_PACKAGE', true);
				$dataUpdate['languageText']['selectDownloadPackageText']			= JText::_('MANUAL_SELECT_DOWNLOADED_PACKAGE', true);
				$dataUpdate['languageText']['manualInstallButton']					= JText::_('MANUAL_INSTALL_BUTTON', true);
				$dataUpdate['authentication'] = $authentication;
				$dataUpdate['core']		= $core;
				$dataUpdate['themes']	= ($this->imageshowCore->needUpdate == false) ? $themes : array();
				$dataUpdate['sources']	= ($this->imageshowCore->needUpdate == false) ? $sources : array();
				$dataUpdate['redirectLink'] = 'index.php?option=com_imageshow&controller=updater';
				$dataUpdate['downloadLink'] = JSN_IMAGESHOW_AUTOUPDATE_URL;
			?>
			<script type="text/javascript">
				var authentication	= <?php echo ($authentication) ? 'true' : 'false'; ?>;
				var updateData		= <?php echo json_encode($dataUpdate); ?>;
				var updater			= new JSNISUpdater(updateData);
				var themeData		= <?php echo json_encode($themes); ?>;
				var sourceData		= <?php echo json_encode($sources); ?>;

				if (authentication) {
					updater.onLogin = function()
					{
						this.options.username = "<?php echo $customerName; ?>";
						this.options.password = "<?php echo $customerPass; ?>";
						return true;
					}
				}

				function redirectAfterFinishInstall()
				{
					if (updateData.core.length > 0 && (themeData.length > 0 || sourceData.length > 0)){
						window.location.href = 'index.php?option=com_imageshow&controller=updater&step=3';
					} else {
						window.location.href = 'index.php?option=com_imageshow';
					}
				}

				window.addEvent('domready', function()
				{
					updater.onUpdate();
				})
			</script>
			<button id="jsn-updater-button-cancel" class="link-button" onclick="window.location.href='index.php?option=com_imageshow';"><?php echo Jtext::_('UPDATER_BUTTON_CANCEL');?></button>
			<button id="jsn-updater-button-finish" class="link-button" onclick="redirectAfterFinishInstall(); return false;"><?php echo Jtext::_('UPDATER_BUTTON_FINISH');?></button>
		</div>
	<?php } else if (!count($sources) && !count($themes) && !$this->imageshowCore->needUpdate && $this->canAutoUpdate) { ?>
		<?php $mainframe->redirect('index.php?option=com_imageshow&controller=updater');?>
	<?php } else {?>
		<?php $mainframe->redirect('index.php?option=com_imageshow&controller=updater');?>
	<?php }?>
	</div>
</div>
<?php
if (!$authentication)
{
	$session->set($identifierIsCustomer, false, 'jsnimageshowsession');
	$session->set($identifier, array(), 'jsnimageshowsession');
}
?>