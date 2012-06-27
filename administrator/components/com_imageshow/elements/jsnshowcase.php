<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsnshowcase.php 12885 2012-05-31 03:10:25Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
class JFormFieldJsnshowcase extends JFormField
{
	protected function getInput()
	{
		$enabledCSS 	= ' jsn-disable';
		$menuid			= JRequest::getInt('id');
		$app 			= JFactory::getApplication();
		$showcaseID 	= $app->getUserState('com_imageshow.add.showcase_id');

		if ($showcaseID != 0)
		{
			 $this->value = $showcaseID;
			 $app->setUserState('com_modules.add.showcase_id', 0);
		}

		$db = JFactory::getDBO();
		JHTML::stylesheet('style.css','modules/mod_imageshow/assets/css/');
		JHTML::script('jsnis_module.js','modules/mod_imageshow/assets/js/');
        //build the list of categories
		$query = 'SELECT a.showcase_title AS text, a.showcase_id AS id'
		. ' FROM #__imageshow_showcase AS a'
		. ' WHERE a.published = 1'
		. ' ORDER BY a.ordering';
		$db->setQuery( $query );
		$data 		= $db->loadObjectList();
		$results[] 	= JHTML::_('select.option', '0', '- '.JText::_('JSN_FIELD_SELECT_SHOWCASE').' -', 'id', 'text' );
		$results 	= array_merge( $results, $data);
		if ($data)
		{
			$enabledCSS = '';
			if (!$menuid && !$showcaseID)
			{
				$this->value = $data[0]->id;
			}
		}
		else
		{
			$this->value = '0';
		}
		$html  = "<div id='jsn-showcase-icon-warning'>";
		$html .= JHTML::_('select.genericList', $results, $this->name, 'class="inputbox jsn-select-value'.$enabledCSS.'"', 'id', 'text', $this->value,  $this->id);
		if (!$data)
		{
			$html 	.= '<span class="jsn-menu-alert-message">'.JText::_('JSN_DO_NOT_HAVE_ANY_SHOWCASE').'</span>';
		}
		$html .= "<span class=\"jsn-icon-warning".$enabledCSS."\" id = \"showcase-icon-warning\"><span class=\"jsn-tooltip-wrap\"><span class=\"jsn-tooltip-anchor\"></span><p class=\"jsn-tooltip-title\">".JText::_('JSN_FIELD_TITLE_SHOWCASE_WARNING')."</p>".JText::_('JSN_FIELD_DES_SHOWCASE_WARNING')."</span></span>";
		$html .= "<a class=\"jsn-link-edit-showcase\" id=\"jsn-link-edit-showcase\" href=\"javascript: void(0);\" target=\"_blank\" title=\"".JText::_('EDIT_SELECTED_SHOWCASE')."\"><span class=\"jsn-icon-edit\" id=\"showcase-icon-edit\"></span></a>";
		$html .= "<a href=\"index.php?option=com_imageshow&controller=showcase&task=add\" target=\"_blank\" title=\"".JText::_('CREATE_NEW_SHOWCASE')."\"><span class=\"jsn-icon-add\" id=\"showcase-icon-add\"></span></a>";
		$html .= "</div>";

		return $html;
	}

	public function showcaseDropDownList($name, $id)
	{
		JHTML::script('jsn_is_plg_editor.js','administrator/components/com_imageshow/assets/js/');
		$value = 0;
		$db = JFactory::getDBO();
		$query = 'SELECT a.showcase_title AS text, a.showcase_id AS id'
		. ' FROM #__imageshow_showcase AS a'
		. ' WHERE a.published = 1'
		. ' ORDER BY a.ordering';
		$db->setQuery($query);
		$data 		= $db->loadObjectList();
		$results[] 	= JHTML::_('select.option', '0', '- '.JText::_('PLG_EDITOR_FIELD_SELECT_SHOWCASE').' -', 'id', 'text');
		$results 	= array_merge( $results, $data);
		$html  = "<div id='jsn-showcase-icon-warning'>";
		if (!$data)
		{
			$html 	.= '<span class="jsn-menu-alert-message">'.JText::_('PLG_EDITOR_DO_NOT_HAVE_ANY_SHOWCASE').'</span>';
		}
		else
		{
			$value = $data[0]->id;
			$html .= JHTML::_('select.genericList', $results, $name, 'class="inputbox jsn-select-value" id="'.$id.'"', 'id', 'text', $value);
			$html .= "<span class=\"jsn-icon-warning\" id = \"showcase-icon-warning\"><span class=\"jsn-tooltip-wrap\"><span class=\"jsn-tooltip-anchor\"></span><p class=\"jsn-tooltip-title\">".JText::_('PLG_EDITOR_TITLE_SHOWCASE_WARNING')."</p>".JText::_('PLG_EDITOR_DES_SHOWCASE_WARNING')."</span></span>";
			$html .= "<a class=\"jsn-link-edit-showcase\" id=\"jsn-link-edit-showcase\" href=\"javascript: void(0);\" target=\"_blank\" title=\"".JText::_('PLG_EDITOR_EDIT_SELECTED_SHOWCASE')."\"><span class=\"jsn-icon-edit\" id=\"showcase-icon-edit\"></span></a>";
		}
		$html .= "<a href=\"index.php?option=com_imageshow&controller=showcase&task=add\" target=\"_blank\" title=\"".JText::_('PLG_EDITOR_CREATE_NEW_SHOWCASE')."\"><span class=\"jsn-icon-add\" id=\"showcase-icon-add\"></span></a>";
		$html .= "</div>";

		return $html;
	}
}
?>