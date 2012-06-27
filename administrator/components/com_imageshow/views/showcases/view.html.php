<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: view.html.php 13017 2012-06-04 11:04:16Z haonv $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class ImageShowViewShowCases extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $option, $componentVersion;	
		$list = array();
		JHTML::_('behavior.modal', 'a.modal');
		
		$document = JFactory::getDocument();
		$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_imageshow.js?v='.$componentVersion);
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/imageshow.css?v='.$componentVersion);
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/mediamanager.css?v='.$componentVersion);
		
		$objJSNShowcase 	= JSNISFactory::getObj('classes.jsn_is_showcase');
		$objJSNShowcase->checkShowcaseLimition();
		
		$filterState 		= $mainframe->getUserStateFromRequest( 'com_imageshow.showcase.filter_state', 'filter_state', '', 'word' );
		$filterOrder		= $mainframe->getUserStateFromRequest( 'com_imageshow.showcase.filter_order','filter_order', '', 'cmd' );
		$filterOrderDir		= $mainframe->getUserStateFromRequest( 'com_imageshow.showcase.filter_order_Dir',	'filter_order_Dir',	'',	'word' );
		$showcaseTitle 		= $mainframe->getUserStateFromRequest( 'com_imageshow.showcase.showcase_title', 'showcase_title', '', 'string' );
		
		$type = array(0 => array('value'=>'', 'text'=>'- Published -'), 1 => array('value'=>'P', 'text'=>'Yes'), 2 => array('value'=>'U', 'text'=>'No'));
		
		$lists['type'] 		= JHTML::_('select.genericList', $type, 'filter_state', 'id="filter_state" class="inputbox" onchange="document.adminForm.submit( );"'. '', 'value', 'text', $filterState);	
		$lists['state']		= JHTML::_('grid.state',  $filterState );
		$lists['showcaseTitle'] = $showcaseTitle;	
		$lists['order_Dir'] 	= $filterOrderDir;
		$lists['order'] 		= $filterOrder;	
	
		
		$items		= $this->get( 'Data' );
		$total		= $this->get( 'Total' );
		$pagination = $this->get( 'Pagination' );
			
		$this->assignRef('lists',		$lists);
		$this->assignRef('total',		$total);
		$this->assignRef('items',		$items);
		$this->assignRef('pagination',	$pagination);			
		parent::display($tpl);
		
	}
}

?>