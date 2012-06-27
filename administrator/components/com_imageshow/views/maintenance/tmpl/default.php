<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default.php 11338 2012-02-22 10:37:33Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.html.pane');
JHTML::_('behavior.tooltip');
JToolBarHelper::title( JText::_('JSN_IMAGESHOW').': '.JText::_( 'MAINTENANCE_CONFIGURATION_AND_MAINTENANCE' ), 'maintenance' );
$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
$objJSNUtils->callJSNButtonMenu();
$type  			= JRequest::getWord('type','backup');
$sourceType  	= JRequest::getString('source_type');
$themeName 		= JRequest::getString('theme_name');
?>
<script language="javascript">
window.addEvent('domready', function(){
	JSNISImageShow.Maintenance();
});
</script>
<?php
	$objJSNMsg = JSNISFactory::getObj('classes.jsn_is_displaymessage');
	if(empty($sourceType) && empty($themeName)){
	echo $objJSNMsg->displayMessage('CONFIGURATION_AND_MAINTENANCE');
?>

<div id="jsn-imageshow-configuration-maintenance">
	<div id="jsn-imageshow-configuration-maintenance_inner">
		<div id="jsn-imageshow-configuration-maintenance_inner1">
			<div id="jsn-main-navigation">
				<div>
				<h3 class="jsn-element-heading"><span><?php echo JText::_('MAINTENANCE_CONFIGURATION'); ?></span></h3>
				<ul class="jsn-navigation-item">
					<li<?php echo ($type=='configs')?' id="jsn-active-item"':''; ?>><a id="linkconfigs" href="#"><span class="icon-configs"><?php echo JText::_('MAINTENANCE_GLOBAL_PARAMETERS'); ?></span></a></li>
					<li<?php echo ($type=='msgs')?' id="jsn-active-item"':''; ?>><a id="linkmsgs" href="#"><span class="icon-msgs"><?php echo JText::_('MAINTENANCE_MESSAGES'); ?></span></a></li>
					<li<?php echo ($type=='inslangs')?' id="jsn-active-item"':''; ?>><a id="linklangs" href="#"><span class="icon-langs"><?php echo JText::_('MAINTENANCE_LANGUAGES'); ?></span></a></li>
				</ul>
				</div>
				<div>
				<h3 class="jsn-element-heading"><span><?php echo JText::_('MAINTENANCE_MAINTENANCE'); ?></span></h3>
				<ul class="jsn-navigation-item">
					<li<?php echo ($type=='data')?' id="jsn-active-item"':''; ?>><a id="linkdata" href="#"><span class="icon-backup"><?php echo JText::_('MAINTENANCE_DATA'); ?></span></a></li>
					<li<?php echo ($type=='profiles')?' id="jsn-active-item"':''; ?>><a id="linkprofile" href="#"><span class="icon-profile"><?php echo JText::_('MAINTENANCE_IMAGE_SOURCE_PROFILES'); ?></span></a></li>
					<li<?php echo ($type=='themes')?' id="jsn-active-item"':''; ?>><a id="linkthemes" href="#"><span class="icon-themes"><?php echo JText::_('MAINTENANCE_THEMES_MANAGER'); ?></span></a></li>
				</ul>
				</div>
			</div>
			<div id="jsn-main-content-container">
				<?php
		}
		?>
				<?php
						switch($type){
							case 'inslangs':
								echo $this->loadTemplate('inslangs');
							break;
							case 'msgs':
								echo $this->loadTemplate('messages');
							break;
							case 'profiles':
								echo $this->loadTemplate('profiles');
							break;
							case 'editprofile':
								$this->addTemplatePath(JPATH_PLUGINS.DS.'jsnimageshow'.DS.'source'.$sourceType.DS.'views'.DS.'maintenance'.DS.'tmpl');
								echo $this->loadTemplate('edit_source_profile');
							break;
							case 'configs':
								echo $this->loadTemplate('configs');
							break;
							case 'sampledata':
								echo $this->loadTemplate('sampledata');
							break;
							case 'themes':
								echo $this->loadTemplate('themes');
							break;
							case 'themeparameters':
								$this->addTemplatePath(JPATH_PLUGINS.DS.'jsnimageshow'.DS.$themeName.DS.'views'.DS.'maintenance'.DS.'tmpl');
								echo $this->loadTemplate('theme_config');
							break;
							case 'data':
								echo $this->loadTemplate('data');
							break;
							default:
							break;
						}
					?>
				<?php
		if(empty($sourceType) && empty($themeName)){

		?>
			</div>
			<div class="clr"></div>
		</div>
	</div>
</div>
<div id="maintenance-footer">
	<?php include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'footer.php'); ?>
</div>
<?php
}
?>
