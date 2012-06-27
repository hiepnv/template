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

class ImageShowViewShowLists extends JView{

	function display($tpl = null)
	{
		global $mainframe, $option, $componentVersion;
		$document = JFactory::getDocument();
		$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_imageshow.js?v='.$componentVersion);
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/imageshow.css?v='.$componentVersion);
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/mediamanager.css?v='.$componentVersion);

		$objJSNShowlist 	= JSNISFactory::getObj('classes.jsn_is_showlist');
		$task 				= JRequest::getString('task');

		$objJSNSource = JSNISFactory::getObj('classes.jsn_is_source');
		$internalSoure = $objJSNSource->getListSources('internal');

		$objJSNSource->checkInternalSourceInstalled();

		if ($task != 'element' && $task != 'elements') {
			$objJSNShowlist->checkShowlistLimition();
		}

		$list 				= array();
		$model 				= $this->getModel();

		$filterState 		= $mainframe->getUserStateFromRequest( 'com_imageshow.showlist.filter_state', 'filter_state', '', 'word' );
		$filterOrder		= $mainframe->getUserStateFromRequest( 'com_imageshow.showlist.filter_order', 'filter_order', '', 'cmd' );
		$filterOrderDir		= $mainframe->getUserStateFromRequest( 'com_imageshow.showlist.filter_order_Dir', 'filter_order_Dir', '', 'word' );
		$showlistTitle 		= $mainframe->getUserStateFromRequest( 'com_imageshow.showlist.showlist_stitle', 'showlist_stitle', '', 'string' );
		$showlistAccess		= $mainframe->getUserStateFromRequest( 'com_imageshow.showlist.filter_access', 'filter_access', '', 'string' );

		$type = array(0 => array('value'=>'', 'text'=>'- Published -'), 1 => array('value'=>'P', 'text'=>'Yes'), 2 => array('value'=>'U', 'text'=>'No'));

		$lists['type'] 		= JHTML::_('select.genericList', $type, 'filter_state', 'id="filter_state" class="inputbox" onchange="document.adminForm.submit( );"'. '', 'value', 'text', $filterState);
		$lists['state']		= JHTML::_('grid.state',  $filterState );
		$lists['access']	= $showlistAccess;
		$lists['showlistTitle'] 	= $showlistTitle;
		$lists['order_Dir'] 		= $filterOrderDir;
		$lists['order'] 			= $filterOrder;

		$items		= $this->get( 'Data' );
		$total		= $this->get( 'Total' );
		$pagination = $this->get( 'Pagination' );
		$this->state = $this->get('State');

		if ($task == 'elements')
		{
			$sourceName 	= JRequest::getVar('image_source_name');
			$sourceID 		= JRequest::getInt('external_source_id', 0);
			$objJSNShowlist = JSNISFactory::getObj('classes.jsn_is_showlist');

			if ($sourceID) {
				$items = $objJSNShowlist->getListShowlistBySource($sourceID, $sourceName);
			}else{
				$items = array();
			}
		}

		$this->assignRef('lists',		$lists);
		$this->assignRef('total',		$total);
		$this->assignRef('items',		$items);
		$this->assignRef('pagination',	$pagination);
		parent::display($tpl);
	}
}
