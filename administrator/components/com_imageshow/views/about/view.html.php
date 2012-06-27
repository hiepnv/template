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
class ImageShowViewAbout extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $option, $componentVersion;
		
		$document = JFactory::getDocument();
		$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_imageshow.js?v='.$componentVersion);
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/imageshow.css?v='.$componentVersion);

		$objJSNUtils      = JSNISFactory::getObj('classes.jsn_is_utils');
		$objJSNJSON       = JSNISFactory::getObj('classes.jsn_is_json');
		$doc			  = JFactory::getDocument();

		$componentInfo 	  = $objJSNUtils->getComponentInfo();
		$componentData 	  = null;
		$edition		  = $objJSNUtils->getEdition();
		$componentData  = $objJSNJSON->decode($componentInfo->manifest_cache);
		$currentVersion = @$componentData->version;
		$doc->addScriptDeclaration("
				window.addEvent('domready', function(){
					var check = false;
						var actionVersionUrl = 'index.php';
						var resultVersionMsg = new Element('span');
						resultVersionMsg.set('class','jsn-version-checking');
						resultVersionMsg.set('html','".JText::_('ABOUT_CHECKING')."');
						resultVersionMsg.inject($('jsn-check-version-result'));
						var jsonRequest = new Request.JSON({url: actionVersionUrl, onSuccess: function(jsonObj){
							if(jsonObj.connection) {
								check = JSNISImageShow.checkVersion('".$currentVersion."', jsonObj.version);
								if(check) {
									resultVersionMsg.set('class','jsn-outdated-version');
									resultVersionMsg.set('html','".JText::_('ABOUT_SEE_UPDATE_INSTRUCTIONS', true)."');
								} else {
									resultVersionMsg.set('class','jsn-latest-version');
									resultVersionMsg.set('html','".JText::_('ABOUT_THE_LATEST_VERSION', true)."');
								}
							} else {
								resultVersionMsg.set('class','jsn-connection-fail');
								resultVersionMsg.set('html','".JText::_('ABOUT_CONNECTION_FAILED', true)."');
							}
							resultVersionMsg.inject($('jsn-check-version-result'));
						}}).get({'option': 'com_imageshow',
									'controller': 'ajax',
									'task': 'checkUpdate',
									'name': '".strtolower(trim(@$componentData->name))."',
									'edition': '".strtolower(trim(@$edition))."'
								});
				});

			");
		$params = JComponentHelper::getParams('com_imageshow');
		$this->assignRef('edition',$edition);
		$this->assignRef('componentData', $componentData);
		$this->assignRef('params',$params);
		parent::display($tpl);

	}
}