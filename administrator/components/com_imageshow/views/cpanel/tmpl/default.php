<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default.php 12625 2012-05-12 08:27:30Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
JToolBarHelper::title( JText::_('JSN_IMAGESHOW').': '.JText::_('CPANEL_LAUNCH_PAD'), 'launchpad' );
$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
$objJSNUtils->callJSNButtonMenu();
$session 		= JFactory::getSession();
$identifier		= md5('jsn_imageshow_downloasource_identify_name');
$session->set($identifier, '', 'jsnimageshowsession');
?>

<script language="javascript">
function enableButton()
{
	var showlistElement 	= $('showlist_id');
	var showcaseElement 	= $('showcase_id');
	var editShowcase		= $('edit-showcase');
	var editShowlist		= $('edit-showlist');
	var showlistID 			= showlistElement.options[showlistElement.selectedIndex].value;
	var showcaseID 			= showcaseElement.options[showcaseElement.selectedIndex].value;
	var presentationMethod	= $('presentation_method');
	$('menutype').setStyle('display', 'none');
	$('jsn-go-link').href = "javascript:void(0);";
	$('jsn-go-link-modal').setStyle('display', 'none');
	$('jsn-go-link').setStyle('display', '');
	$('jsn-go-link-modal').href = "javascript:void(0);";
	$('jsn-go-link').addClass("disabled");
	$('jsn-go-link-modal').addClass("disabled");
	presentationMethod.selectedIndex = 0;

	if (showcaseID != '0' && showlistID != '0')
	{
		$('presentation_method').className = "jsn-gallery-selectbox active";
		presentationMethod.disabled = false;
	}
	else
	{
		$('presentation_method').className = "jsn-gallery-selectbox";
		presentationMethod.disabled = true;
	}

	if(showcaseID != 0)
	{
		$('edit-showcase').className = "icon-edit";
		$('edit-showcase').href = 'index.php?option=com_imageshow&controller=showcase&task=edit&cid[]='+showcaseID;
		$('edit-showcase').target = "_blank";
		editShowcase.title = '<?php echo JText::_('CPANEL_EDIT_SELECTED_SHOWCASE', true);?>';
	}
	else
	{
		$('edit-showcase').className = "icon-edit icon-disabled";
		$('edit-showcase').href = "javascript:void(0);";
		$('edit-showcase').target = "";
		editShowcase.title = '<?php echo JText::_('CPANEL_YOU_MUST_SELECT_SOME_SHOWCASE_TO_EDIT', true);?>';

	}

	if(showlistID != 0)
	{
		$('edit-showlist').className = "icon-edit";
		$('edit-showlist').href = 'index.php?option=com_imageshow&controller=showlist&task=edit&cid[]='+showlistID;
		$('edit-showlist').target = "_blank";
		editShowlist.title = '<?php echo JText::_('CPANEL_EDIT_SELECTED_SHOWLIST', true);?>';
	}
	else
	{
		$('edit-showlist').className = "icon-edit icon-disabled";
		$('edit-showlist').href = "javascript:void(0);";
		$('edit-showlist').target = "";
		editShowlist.title = '<?php echo JText::_('CPANEL_YOU_MUST_SELECT_SOME_SHOWLIST_TO_EDIT', true);?>';
	}
}

function createViaMenu(create)
{
	var showlistElement = $('showlist_id');
	var showcaseElement = $('showcase_id');
	var menu		    = $('menutype');
	var showlistID 		= showlistElement.options[showlistElement.selectedIndex].value;
	var showcaseID 		= showcaseElement.options[showcaseElement.selectedIndex].value;
	var menutype 		= menu.options[menu.selectedIndex].value;
	if(menutype == "")
	{
		$('jsn-go-link').href = "javascript:void(0);";
	}
	else
	{
		$('jsn-go-link').href	= 'index.php?option=com_imageshow&task=launchAdapter&type=menu&menutype=' + menutype + '&' + 'showlist_id=' + showlistID + '&showcase_id=' + showcaseID;
	}
}

function choosePresentMethode()
{
	var showlistElement = $('showlist_id');
	var showcaseElement = $('showcase_id');
	var method		    = $('presentation_method');

	var linkMenu 		= $('link-menu');
	var showlistID 		= showlistElement.options[showlistElement.selectedIndex].value;
	var showcaseID 		= showcaseElement.options[showcaseElement.selectedIndex].value;
	var methodValue		= method.options[method.selectedIndex].value;
	$('menutype').setStyle('display', 'none');
	$('jsn-go-link-modal').setStyle('display', 'none');
	$('jsn-go-link').setStyle('display', '');
	$('jsn-go-link').href = "javascript:void(0);";
	$('jsn-go-link').removeClass("disabled");
	$('jsn-go-link-modal').removeClass("disabled");
	if(methodValue == "module")
	{
		$('jsn-go-link').href = 'index.php?option=com_imageshow&task=launchAdapter&type=module&' + 'showlist_id=' + showlistID + '&showcase_id=' + showcaseID;
	}
	else if(methodValue == "plugin")
	{
		$('jsn-go-link-modal').href = 'index.php?option=com_imageshow&task=plugin&tmpl=component&' + 'showlist_id=' + showlistID + '&showcase_id=' + showcaseID;
		$('jsn-go-link-modal').setStyle('display', '');
		$('jsn-go-link').setStyle('display', 'none');
	}
	else if(methodValue == "menu")
	{
		$('menutype').setStyle('display', 'block');
		$('menutype').selectedIndex = 0;
	}
	else
	{
		$('jsn-go-link').href = "javascript:void(0);";
		$('jsn-go-link').addClass("disabled");
		$('jsn-go-link-modal').addClass("disabled");
	}
}
</script>

<?php
	$objJSNMsg = JSNISFactory::getObj('classes.jsn_is_displaymessage');
	echo $objJSNMsg->displayMessage('LAUNCH_PAD');
?>
<!--[if IE 7]>
        <link rel="stylesheet" type="text/css" href="components/com_imageshow/assets/css/fixie7.css">
<![endif]-->
<div class="jsn-cpanel-container">
	<div class="jsn-cpanel-block clearafter">
        <div class="jsn-process-step"><h3>1</h3></div>
        <div class="jsn-gallery-option box-grey">
			<h3 class="jsn-element-heading"><?php echo JText::_('CPANEL_CPANEL_SHOWLIST'); ?></h3>
			<p><?php echo JText::_('CPANEL_SETUP_WHAT_IMAGES_TO_BE_SHOWN_IN_THE_GALLERY'); ?></p>
			<div class="jsn-gallery-selection clearafter">
				<?php echo $this->lists['showlist']; ?>
				<span class="jsn-gallery-nav-button">
					<a href="javascript:void(0);" id="edit-showlist" target="" class="icon-edit icon-disabled" title="<?php echo htmlspecialchars(JText::_('CPANEL_YOU_MUST_SELECT_SOME_SHOWLIST_TO_EDIT')); ?>">&nbsp;</a>
					<a href="index.php?option=com_imageshow&controller=showlist&task=add" target="_blank" class="icon-add" title="<?php echo htmlspecialchars(JText::_('CPANEL_CREATE_NEW_SHOWLIST')); ?>">&nbsp;</a>
					<a href="index.php?option=com_imageshow&controller=showlist" target="_blank" class="icon-folder" title="<?php echo htmlspecialchars(JText::_('CPANEL_SEE_ALL_SHOWLISTS')); ?>">&nbsp;</a>
				</span>
			</div>
        </div>
    </div>
    <div class="jsn-cpanel-block clearafter">
        <div class="jsn-process-step"><h3>2</h3></div>
        <div class="jsn-gallery-option box-grey">
			<h3 class="jsn-element-heading"><?php echo JText::_('CPANEL_CPANEL_SHOWCASE'); ?></h3>
			<p><?php echo JText::_('CPANEL_SETUP_HOW_TO_PRESENT_IMAGES_IN_THE_GALLERY'); ?></p>
			<div class="jsn-gallery-selection clearafter">
				<?php echo $this->lists['showcase']; ?>
				<span class="jsn-gallery-nav-button">
					<a href="javascript:void(0);" id="edit-showcase" target="" class="icon-edit icon-disabled" title="<?php echo htmlspecialchars(JText::_('CPANEL_YOU_MUST_SELECT_SOME_SHOWCASE_TO_EDIT')); ?>">&nbsp;</a>
					<a href="index.php?option=com_imageshow&controller=showcase&task=add" target="_blank" class="icon-add" title="<?php echo htmlspecialchars(JText::_('CPANEL_CREATE_NEW_SHOWCASE')); ?>">&nbsp;</a>
					<a href="index.php?option=com_imageshow&controller=showcase" target="_blank" class="icon-folder" title="<?php echo htmlspecialchars(JText::_('CPANEL_SEE_ALL_SHOWCASES')); ?>">&nbsp;</a>
				</span>
			</div>
        </div>
    </div>
   	<div class="jsn-cpanel-block clearafter">
        <div class="jsn-process-step"><h3>3</h3></div>
        <div class="jsn-gallery-option box-orange">
			<h3 class="jsn-element-heading"><?php echo JText::_('CPANEL_PRESENTATION'); ?></h3>
			<p><?php echo JText::_('CPANEL_CONFIGURE_HOW_TO_PRESENT_THE_GALLERY'); ?></p>
			<div class="jsn-gallery-selection clearafter">
				<a href="javascript:void(0);" class="link-button disabled" title="<?php echo htmlspecialchars(JText::_('CPANEL_GO')); ?>" id="jsn-go-link"><?php echo JText::_('CPANEL_GO'); ?></a>
				<a href="javascript:void(0);" rel="{handler: 'iframe', size: {x: 500, y: 300}}" class="link-button disabled modal" style="display: none;" id="jsn-go-link-modal" title="<?php echo htmlspecialchars(JText::_('CPANEL_GO')); ?>"><?php echo JText::_('CPANEL_GO'); ?></a>
				<?php echo $this->lists['presentationMethods']; ?>
				<?php echo $this->lists['menu']; ?>
			</div>
        </div>
     </div>
</div>
<div class="clr"></div>
<?php include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'footer.php'); ?>