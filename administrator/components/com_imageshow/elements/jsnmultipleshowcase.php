<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsnmultipleshowcase.php 10584 2011-12-29 10:25:12Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
class JFormFieldJSNMultipleShowcase extends JFormField
{
	public $type = 'JSNMultipleShowcase';
	protected function getInput()
	{
		JHTML::stylesheet('style.css','modules/mod_imageshow/assets/css/');
		$enabledCSS = ' jsn-disable';
		$menuid		= JRequest::getInt('id');
		$app =& JFactory::getApplication();
		$db  =& JFactory::getDBO();
		$db  =& JFactory::getDBO();
		$doc =& JFactory::getDocument();
		$doc->addScriptDeclaration("
			function JSNSetMultipleShowcaseStatus()
			{
				var showcase 		= $('jform_params_showcase_id');
				var length			= showcase.length;
				var selected		= false;
				var counter			= 0;
				if (length)
				{
					if (showcase.value == 0)
					{
						showcase.style.background = '#CC0000';
						showcase.style.color = '#fff';
						$('showcase-icon-warning').setStyle('display', '');
						$('showcase-icon-edit').setStyle('display', 'none');
						$('jsn-link-edit-showcase').href='javascript: void(0);';
					}
					else
					{
						showcase.style.background = '#FFFFDD';
						showcase.style.color = '#000';
						$('showcase-icon-warning').setStyle('display', 'none');
						$('showcase-icon-edit').setStyle('display', '');
						$('jsn-link-edit-showcase').href='index.php?option=com_imageshow&controller=showcase&task=edit&cid[]='+showcase.value;
					}
				}
			}
			window.addEvent('domready', function() {
				JSNSetMultipleShowcaseStatus();
				$$('.jsn-icon-warning').each(function(item, i){
					item.addEvent('mouseover', function() {
						$$('.pane-slider')[0].setStyle('overflow', 'visible');
					});
					item.addEvent('mouseout', function() {
						$$('.pane-slider')[0].setStyle('overflow', 'hidden');
					});
				});
			});
		");
		$query = 'SELECT a.showcase_title AS text, a.showcase_id AS id'
		. ' FROM #__imageshow_showcase AS a'
		. ' WHERE a.published = 1'
		. ' ORDER BY a.ordering';

		$db->setQuery($query);
		$data 		= $db->loadObjectList();
		$results[] 	= JHTML::_('select.option', '0', '- '.JText::_('JSN_FIELD_SELECT_SHOWCASE').' -', 'id', 'text' );
		$results 	= array_merge($results, $data);
		if ($data)
		{
			$enabledCSS = '';
			if (!$menuid)
			{
				$this->value = $data[0]->id;
			}
		}
		else
		{
			$this->value = '0';
		}
		$html  		 = "<div id='jsn-showcase-icon-warning'>";
		$html 		.= JHTML::_('select.genericList', $results, $this->name, 'class="inputbox jsn-select-value'.$enabledCSS.'" onchange="JSNSetMultipleShowcaseStatus();"', 'id', 'text', $this->value,  $this->id);
		if (!$data)
		{
			$html 	.= '<span class="jsn-menu-alert-message">'.JText::_('JSN_DO_NOT_HAVE_ANY_SHOWCASE').'</span>';
		}
		$html 		.= "<span class=\"jsn-icon-warning".$enabledCSS."\" id = \"showcase-icon-warning\"><span class=\"jsn-tooltip-wrap\"><span class=\"jsn-tooltip-anchor\"></span><p class=\"jsn-tooltip-title\">".JText::_('JSN_FIELD_TITLE_SHOWCASE_WARNING')."</p>".JText::_('JSN_FIELD_DES_SHOWCASE_WARNING')."</span></span>";
		$html 		.= "<a class=\"jsn-link-edit-showcase\" id=\"jsn-link-edit-showcase\" href=\"javascript: void(0);\" target=\"_blank\" title=\"".JText::_('EDIT_SELECTED_SHOWCASE')."\"><span class=\"jsn-icon-edit\" id=\"showcase-icon-edit\"></span></a>";
		$html 		.= "<a href=\"index.php?option=com_imageshow&controller=showcase&task=add\" target=\"_blank\" title=\"".JText::_('CREATE_NEW_SHOWCASE')."\"><span class=\"jsn-icon-add\" id=\"showcase-icon-add\"></span></a>";
		$html 		.= "</div>";
		return $html;
	}
}
?>