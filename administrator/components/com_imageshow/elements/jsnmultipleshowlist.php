<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsnmultipleshowlist.php 10584 2011-12-29 10:25:12Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
class JFormFieldJSNMultipleShowlist extends JFormField
{
	public $type = 'JSNMultipleShowlist';
	protected function getInput()
	{
		JHTML::stylesheet('style.css','modules/mod_imageshow/assets/css/');
		$enabledCSS = ' jsn-disable';
		$menuid		= JRequest::getInt('id');
		$app =& JFactory::getApplication();
		$db  =& JFactory::getDBO();
		$doc =& JFactory::getDocument();
		$doc->addScriptDeclaration("
			function JSNSetMultipleShowlistStatus()
			{
				var multiple_showlist 		= $('tmp_multiple_showlist');
				var length					= multiple_showlist.length;
				var selected				= false;
				var counter					= 0;
				if (length)
				{
					for (var i = 0; i < length; i++)
					{
						if (multiple_showlist[i].selected == true)
						{
							selected = true;
							counter ++;
						}
					}
					if (selected)
					{
						multiple_showlist.style.background = '#FFFFDD';
						multiple_showlist.style.color = '#000';
						$('showlist-icon-warning').setStyle('display', 'none');
						$('showlist-icon-edit').setStyle('display', '');
						if (counter > 1)
						{
							$('showlist-icon-edit').removeClass('jsn-icon-edit');
							$('showlist-icon-edit').addClass('jsn-icon-trans-edit');
							$('jsn-link-edit-showlist').title='".JText::_('EDIT_SELECTED_SHOWLIST_IMPOSSIBLE')."';
							$('jsn-link-edit-showlist').href='javascript: void(0);';
							$('jsn-link-edit-showlist').target='_self';
						}
						else
						{
							$('showlist-icon-edit').addClass('jsn-icon-edit');
							$('showlist-icon-edit').removeClass('jsn-icon-trans-edit');
							$('jsn-link-edit-showlist').title='".JText::_('EDIT_SELECTED_SHOWLIST')."';
							$('jsn-link-edit-showlist').href='index.php?option=com_imageshow&controller=showlist&task=edit&cid[]='+multiple_showlist.value;
							$('jsn-link-edit-showlist').target='_blank';
						}
					}
					else
					{
						multiple_showlist.style.background 	= '#CC0000';
						multiple_showlist.style.color 		= '#fff';
						$('showlist-icon-warning').setStyle('display', '');
						$('showlist-icon-edit').setStyle('display', 'none');
						$('jsn-link-edit-showlist').href= 'javascript: void(0);';
						$('jsn-link-edit-showlist').target='_self';
					}

				}
				else
				{
					multiple_showlist.style.background 	= '#CC0000';
					multiple_showlist.style.color 		= '#fff';
					$('showlist-icon-warning').setStyle('display', '');
					$('showlist-icon-edit').setStyle('display', 'none');
					$('jsn-link-edit-showlist').href= 'javascript: void(0);';
					$('jsn-link-edit-showlist').target='_self';
				}
			}

			function JSNChangeMultipleShowlist()
			{
				var items					= new Array();
				var multiple_showlist 		= $('tmp_multiple_showlist');
				var hidden_multiple_showlist = $('".$this->id."');
				var length					= multiple_showlist.length;
				if (length)
				{
					var index = 0;
					for (var i = 0; i < length; i++)
					{
						if (multiple_showlist[i].selected == true && multiple_showlist[i].value != 0)
						{
							items [index] = multiple_showlist[i].value;
							index++;
						}
					}
					hidden_multiple_showlist.value = items.join(',');
				}
				JSNSetMultipleShowlistStatus();
			}

			function JSNSetSelectedOptionMultipleShowlist()
			{
				var multiple_showlist 			= $('tmp_multiple_showlist');
				var hidden_multiple_showlist 	= $('".$this->id."');
				if (hidden_multiple_showlist.value != '')
				{
					var items								= hidden_multiple_showlist.value.split(',');

					var multiple_showlist_length  			= multiple_showlist.length;
					var hidden_multiple_showlist_length  	= items.length;
					if (multiple_showlist_length && hidden_multiple_showlist_length)
					{
						for (var i = 0; i < multiple_showlist_length; i++)
						{
							for (var j = 0; j < hidden_multiple_showlist_length; j++)
							{
								if (multiple_showlist[i].value == items[j] && multiple_showlist[i].value != 0)
								{
									multiple_showlist[i].selected = true;
								}
							}
						}
					}
				}
			}
			window.addEvent('domready', function() {
				JSNSetSelectedOptionMultipleShowlist();
				JSNSetMultipleShowlistStatus();
			});
		");
		$query = 'SELECT a.showlist_title AS text, a.showlist_id AS id'
		. ' FROM #__imageshow_showlist AS a'
		. ' WHERE a.published = 1'
		. ' ORDER BY a.ordering';
		$db->setQuery($query);
		//$results[] 	= JHTML::_('select.option', '0', '- '.JText::_('JSN_FIELD_SELECT_SHOWLIST').' -', 'id', 'text' );
		//$results 	= array_merge($results, $db->loadObjectList());
		$results 	 = $db->loadObjectList();
		if ($results)
		{
			$enabledCSS = '';
			if (!$menuid)
			{
				$this->value = $results[0]->id;
			}
		}
		$html  		 = "<div id='jsn-showlist-icon-warning'>";
		$html 		.= JHTML::_('select.genericList', $results, 'tmp_multiple_showlist', 'class="inputbox jsn-select-value'.$enabledCSS.'" size="6" multiple="multiple" onchange=JSNChangeMultipleShowlist();', 'id', 'text');
		if (!$results)
		{
			$html 		.= '<span class="jsn-menu-alert-message">'.JText::_('JSN_DO_NOT_HAVE_ANY_SHOWLIST').'</span>';
			$this->value = '';
		}
		$html 		.= '<input type="hidden" name="'.$this->name.'" id="'.$this->id.'" value="'.$this->value.'"/>';
		$html 		.= "<span class=\"jsn-icon-warning".$enabledCSS."\" id = \"showlist-icon-warning\"><span class=\"jsn-tooltip-wrap\"><span class=\"jsn-tooltip-anchor\"></span><p class=\"jsn-tooltip-title\">".JText::_('JSN_FIELD_TITLE_SHOWLIST_WARNING')."</p>".JText::_('JSN_FIELD_DES_SHOWLIST_WARNING')."</span></span>";
		$html 		.= "<a class=\"jsn-link-edit-showlist\" id=\"jsn-link-edit-showlist\" href=\"javascript: void(0);\" target=\"_blank\" title=\"".JText::_('EDIT_SELECTED_SHOWLIST')."\"><span class=\"jsn-icon-edit\" id = \"showlist-icon-edit\"></span></a>";
		$html 		.= "<a href=\"index.php?option=com_imageshow&controller=showlist&task=add\" target=\"_blank\" title=\"".JText::_('CREATE_NEW_SHOWLIST')."\"><span class=\"jsn-icon-add\" id = \"showlist-icon-add\"></span></a>";
		$html 		.= "</div>";
		return $html.'';
	}
}
?>