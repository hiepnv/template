<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: view.html.php 13385 2012-06-18 11:29:33Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.application.component.view');
class ImageShowViewShowlist extends JView
{
		function display($tpl = null)
		{
			global $mainframe, $option, $componentVersion;
			$images = array();
			$catid  = 0;
			$tmpjs = '';
			$user     = JFactory::getUser();
			$objJSNImages = JSNISFactory::getObj('classes.jsn_is_images');
			JHTML::_('behavior.modal', 'a.modal');
			JHTML::_('behavior.modal', 'a.jsn-modal');
			$document = JFactory::getDocument();
			$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/imageshow.css?v='.$componentVersion);
			$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/jquery/layout-default-latest.css?v='.$componentVersion);
			$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/jquery/jquery-ui.custom.css?v='.$componentVersion);
			//$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/jquery/jquery.contextMenu.css?v='.$componentVersion);
			$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/source-accordion.css?v='.$componentVersion);

			$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_imageshow.js?v='.$componentVersion);
			$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_utils.js?v='.$componentVersion);
			$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_installimagesources.js?v='.$componentVersion);
			$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_installdefault.js?v='.$componentVersion);
			$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jsn_is_accordions.js?v='.$componentVersion);

			$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jquery/jquery.min.js?v='.$componentVersion);
			$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jquery/jquery-ui.custom.min.js?v='.$componentVersion);
			$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jquery/jquery.layout-latest.js?v='.$componentVersion);
			$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jquery/jquery.contextmenu.r2.js?v='.$componentVersion);
			$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/conflict.js?v='.$componentVersion);
			$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/window.css?v='.$componentVersion);

			$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/accordion.css?v='.$componentVersion);
			$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/jquery/jquery.treeview.css?v='.$componentVersion);
			$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/jquery/jquery-treeview.js?v='.$componentVersion);

			$model  = $this->getModel();
			$lists 	= array();
			$items 	= $this->get('data');

			$countImage = 0;

			if (isset($items->image_source_name) && $items->image_source_name != '') {
				$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/image_sortable.css?v='.$componentVersion);

				$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/videoshow.css?v='.$componentVersion);
				$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/imagegrid.js?v='.$componentVersion);
				$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/utils.js?v='.$componentVersion);
				$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/tree.js?v='.$componentVersion);

				$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/window.js?v='.$componentVersion);
				$document->addScript(JUri::root(true).'/administrator/components/com_imageshow/assets/js/jquery/jquery.topzindex.js?v='.$componentVersion);

				$imageSource = JSNISFactory::getSource($items->image_source_name, $items->image_source_type, $items->showlist_id);

				$objImages 	 = JSNISFactory::getObj('classes.jsn_is_images');
				$cat 		 = $objImages->getAllCatShowlist($items->showlist_id);

				if(!empty($cat)){
					$catid		 = $cat[0];
					$config		 = array('album'=>$catid);
					$sync 		 = $imageSource->getShowlistMode();
					if($sync=='sync'){
						$images 	 = $imageSource->loadImages($config);
					}else{
						$images 	 = $imageSource->loadImages($config);
					}
				}

				$document = JFactory::getDocument();
				if($imageSource->getShowlistMode()=='sync'){
					$rmcat = 'imageGrid.removecatSelected();';
				}else{
					$rmcat = '';
				}

				$totalimage = count($images);

				if ($totalimage)
				{
					$imageInfo = (array) $images->images[0];
					$albumID = $imageInfo['album_extid'];
					$tmpjs = "imageGrid.reloadOneImageSource('".$albumID."');";
				}

				$js_code = "
					var baseUrl = '".JURI::root()."';
					var VERSION_EDITION_NOTICE = '".JText::_('VERSION_EDITION_NOTICE')."';
					function reshowtree(obj,e)
					{
						if(e>=1){
							if(e==1){
								obj.parent().parent().parent().find('>ul').css('display','block');
							}
							obj.parent().parent().css('display','block');
							reshowtree(obj.parent().parent());
						}
					}

					(function($){


						$('#dialogbox:ui-dialog').dialog( 'destroy');
						$('#dialogbox2:ui-dialog').dialog( 'destroy');
						$(document).ready(function () {

							var imageGrid = $.JSNISImageGirdGetInstaces({
								showListID   : '".$items->showlist_id."',
								sourceName   : '".$items->image_source_name."',
								sourceType   : '".$items->image_source_type."',
								selectMode   : '".$imageSource->getShowlistMode()."',
								layoutHeight : 700,
								layoutWidth  : '100%'
							});
							".$rmcat."
							".$tmpjs."
							imageGrid.initialize();
							if(!$('.video-item').length && !$('.jtree-selected',$('#images')).length){
								imageGrid.cookie.set('rate_of_west', 58 );
								imageGrid.UILayout.sizePane('west', '58%');
							}
							// context menu
							$('div#source-images').contextMenu('sourceimage_contextmenu', {
								menuStyle: {
								    backgroundColor: '#F1F1F1',
								    border: '2px solid #F1F1F1',
								    outline: '1px solid #949694',
								    padding: '0',
							        width: '200px'
							      },

							    itemStyle: {



							        color: 'black',

							        border: 'none',

							        padding: '2px'

							      },

							    itemHoverStyle: {

							        color: '#F1F1F1',

							        border: 'none'

							      },

						        bindings: {

						          'selectallimage': function() {
						            imageGrid.SelectAllImages('source');
						          },

						          'deselectall': function() {

						            imageGrid.DeselectAll('source');

						          },

						          'revertselection': function() {

						            imageGrid.RevertSelection('source');

						          }

						        }

						      });
							$('div#showlist-videos').contextMenu('showlist_menucontext', {
								menuStyle: {
							        backgroundColor: '#F1F1F1',
								    border: '2px solid #F1F1F1',
								    outline: '1px solid #949694',
								    padding: '0',
							        width: '200px'
							      },

							    itemStyle: {

							        backgroundColor : '#F1F1F1',
							        color: 'black',
							        border: 'none',
							        padding: '1px'
							      },

							    itemHoverStyle: {
							        color: '#F1F1F1',
							        border: 'none',
							      },

						        bindings: {
						          'selectallimage': function() {
						            imageGrid.SelectAllImages('showlist');
						          },
						          'deselectall': function() {
						            imageGrid.DeselectAll('showlist');
						          },

						          'revertselection': function() {
						            imageGrid.RevertSelection('showlist');
						          },
						          'purgeabsoleteimage': function(){
						          		imageGrid.PurgeAbsoleteImages();
						          },
						          'resetselectedimagedetail':function(){
						          		imageGrid.ResetDetailImages();
						          }
						        }
							});

							// process only show level 2 of tree

							//$('#jsn-jtree-categories ul:first li:first ul li').removeClass('jsn-jtree-open').addClass('jsn-jtree-close').find('ul').css('display','none');
							$('#jsn-jtree-categories ul li:first').removeClass().addClass('jsn-jtree-open');
							$('#jsn-jtree-categories ul li ul li.secondchild').each(function(){
								if(!$(this).hasClass('jsn-jtree-children')){
									$(this).removeClass('secondchild').addClass('jsn-jtree-close');
								}
								$(this).find('ul').css('display','none');
							});
							// expand all parent of current 'li'
							$('#jsn-jtree-categories ul li ul').find('li.catselected').parent().parent().find('>ul').each(function(e){
								$(this).css('display','block');
								reshowtree($(this),e);
							});

							$('#jsn-jtree-categories ul li ul').find('li.catselected').parents().each(function(){
								$(this).removeClass('jsn-jtree-close').addClass('jsn-jtree-open');
								reshowtree($(this));
							});

							$('#jsn-jtree-categories ul li ul').find('li.catselected').parents().each(function(){
								$(this).css('display','block');
							})

						});
					})(jQuery);
				  ";

				$document->addScriptDeclaration($js_code);
			 	$this->assignRef('selectMode',$imageSource->getShowlistMode());
			}

			if($items->showlist_id != 0 && $items->showlist_id != '')
			{
				if($objJSNImages->checkImageLimition($items->showlist_id))
				{
					$msg = JText::_('SHOWLIST_YOU_HAVE_REACHED_THE_LIMITATION_OF_10_IMAGES_IN_FREE_EDITION');
					JError::raiseNotice(100, $msg);
				}

				$countImage = $objJSNImages->countImagesShowList($items->showlist_id);
				$countImage = $countImage[0];
			}

			$authorizationCombo = array(
				'0' => array('value' => '0',
				'text' => JText::_('SHOWLIST_NO_MESSAGE')),
				'1' => array('value' => '1',
				'text' => JText::_('SHOWLIST_JOOMLA_ARTICLE'))
			);

			$imagesLoadingOrder= array(
				'0' => array('value' => 'forward',
				'text' => JText::_('SHOWLIST_GENERAL_FORWARD')),
				'1' => array('value' => 'backward',
				'text' => JText::_('SHOWLIST_GENERAL_BACKWARD')),
				'2' => array('value' => 'random',
				'text' => JText::_('SHOWLIST_GENERAL_RANDOM'))
			);

			$showExifData= array(
				'0' => array('value' => 'no',
				'text' => JText::_('SHOWLIST_SHOW_EXIF_DATA_NO')),
				'1' => array('value' => 'title',
				'text' => JText::_('SHOWLIST_SHOW_EXIF_DATA_TITLE')),
				'2' => array('value' => 'description',
				'text' => JText::_('SHOWLIST_SHOW_EXIF_DATA_DESCRIPTION'))
			);

			$lists['imagesLoadingOrder'] 	= JHTML::_('select.genericList', $imagesLoadingOrder, 'image_loading_order', 'class="inputbox" '. '', 'value', 'text', $items->image_loading_order);
			$lists['showExifData'] 			= JHTML::_('select.genericList', $showExifData, 'show_exif_data', 'class="inputbox" '. '', 'value', 'text', $items->show_exif_data);
			$lists['authorizationCombo'] 	= JHTML::_('select.genericList', $authorizationCombo, 'authorization_status', 'class="inputbox" onchange="JSNISImageShow.ShowListCheckAuthorizationContent();"'. '', 'value', 'text', $items->authorization_status );
			$lists['published'] 	= JHTML::_('select.booleanlist',  'published', 'class="inputbox"', ($items->published !='')?$items->published:1 );
			$lists['overrideTitle'] = JHTML::_('select.booleanlist',  'override_title', 'class="inputbox"', $items->override_title);
			$lists['overrideDesc'] 	= JHTML::_('select.booleanlist',  'override_description', 'class="inputbox"', $items->override_description);
			$lists['overrideLink'] 	= JHTML::_('select.booleanlist',  'override_link', 'class="inputbox"', $items->override_link);

			$query 				= 'SELECT ordering AS value, showlist_title AS text'
									. ' FROM #__imageshow_showlist'
									. ' ORDER BY ordering';
			$lists['ordering'] 			= JHTML::_('list.specificordering',  $items, $items->showlist_id, $query );

			$canAutoDownload = true;
			$objJSNUtils 	 = JSNISFactory::getObj('classes.jsn_is_utils');

			if (!$objJSNUtils->checkEnvironmentDownload()) {
				$canAutoDownload = false;
			}

			// for init image selected

			/*$objImages 	 = JSNISFactory::getObj('classes.jsn_is_images');
			$cat 		 = $objImages->getAllCatShowlist($items->showlist_id);
			if(!empty($cat)){
				$catid		 = $cat[0];
				$config		 = array('album'=>$catid);
				$sync 		 = $imageSource->getShowlistMode();
				if($sync=='sync'){
					$images 	 = $imageSource->loadImages($config);
				}else{
					$images 	 = $imageSource->loadImages($config);
				}
			}else {
				$images = array();
				$catid  = 0;
			}*/
			//end selected images

			$image_model  		= $this->getModel();
			$categories 		= $model->getTreeMenu();
			$articlesCatgories 	= $model->getTreeArticle();
			$this->assign('categories', $categories);
			$this->assign('articles_catgories', $articlesCatgories);
			$this->assignRef('canAutoDownload', $canAutoDownload);
			$this->assignRef('lists', $lists);
			$this->assignRef('items', $items);
			$this->assignRef('imageSource',$imageSource);
			$this->assignRef('countImage', $countImage);
			$this->assignRef('images',$images);
			$this->assignRef('catSelected',$catid);

			parent::display($tpl);
		}
}
?>