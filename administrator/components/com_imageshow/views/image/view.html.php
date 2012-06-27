<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN VideoShow
 * @version $Id: view.html.php 10238 2011-12-14 08:09:07Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.application.component.view');
class ImageShowViewImage extends JView
{
		function display($tpl = null)
		{
			$model  = $this->getModel();
			$lists 	= array();
			$items 	= $this->get('data');
			global $componentVersion;
			$document = JFactory::getDocument();
			/*JHTML::script('imagegrid.js?v='.$componentVersion,'administrator/components/com_imageshow/assets/js/joomlashine/');
			JHTML::script('utils.js?v='.$componentVersion,'administrator/components/com_imageshow/assets/js/joomlashine/');	*/
			/*JHTML::script('jquery.min.js?v='.$componentVersion,'administrator/components/com_imageshow/assets/js/jquery/');
			JHTML::script('jquery-ui.custom.min.js?v='.$componentVersion,'administrator/components/com_imageshow/assets/js/jquery/');
			JHTML::script('jquery.layout-latest.js?v='.$componentVersion,'administrator/components/com_imageshow/assets/js/jquery/');
			JHTML::script('conflict.js?v='.$componentVersion,'administrator/components/com_imageshow/assets/js/joomlashine/');*/
			$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/jquery/jquery.treeview.css?v='.$componentVersion);
			$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/jquery/jquery-treeview.js?v='.$componentVersion);

			JHTML::stylesheet('imageshow.css','administrator/components/com_imageshow/assets/css/');
			$document = JFactory::getDocument();
				$js_code = "
					var baseUrl = '".JURI::root()."';
					var imageId = '';
					(function($){
						$(document).ready(function () {
							/*$('#select-link').click(function(){
								$('#dialogbox2').bPopup({
						            closeClass:'close2',
						            onOpen: function(){
						            	$(this).delay(800);
						            },
						            follow:[false, false],
						            position : [500,600],
						            loadUrl:baseUrl+'administrator/index.php?option=com_imageshow&controller=image&view=image&task=showlinkpopup&layout=showlinkpopup&tmpl=component',

					        	});
							})*/
							$('.select-link-edit').click(function(){
								//var url = baseUrl+'administrator/index.php?option=com_imageshow&controller=image&view=image&task=showlinkpopup&layout=showlinkpopup&tmpl=component';
								imageId = $(this).attr('name');
								$('#dialogbox2').dialog({width: 790, modal: true, title: '<span style=\'font-size: 15px; font-weight:bold;\'>Choose Link</span>'});
							})
							$('#bt_close_popup2').click(function(){
								$('#dialogbox2').dialog('close')
							});
							$('#bt_close_popup').click(function(){
								$('#dialogbox').dialog('close')
							});
							$('#tabs').tabs();

								$('#navigation').treeview({
									persist: 'location',
									collapsed: true,
									unique: true
								});

								$('#article-item-list').treeview({
									persist: 'location',
									collapsed: true,
									unique: true
								});

							// action close popup for button cancel
							$('#closepoplink').click(function(){
								$('#dialogbox2').bPopup().close();
							});
							// ajax to get all link of a category
							$('.catlink').each(function(){
								$(this).click(function(){
									$('#menu-list').find('.selected').removeClass('selected');
									$(this).addClass('selected');
									// disable button save
									$('#savelink').removeClass('buttonpopup').attr( 'disabled', 'disabled' );
									var id = $(this).attr('id');
									$.post(baseUrl+'administrator/index.php?option=com_imageshow&controller=image&task=getMenuItemCate&tmpl=component',{
										catid    : id
									}).success( function( responce ){
										$('#article-list').html(responce);
									});

								});
							})
							$('.art_cat').each(function(){
								$(this).click(function(){

									$('#article-item-list').find('.selected').removeClass('selected');
									$(this).addClass('selected');
									// disable button save
									$('#savelink').removeClass('buttonpopup').addClass('buttonpopup-disable').attr( 'disabled', 'disabled' );
									var id = $(this).attr('id');
									$.post(baseUrl+'administrator/index.php?option=com_imageshow&controller=image&task=getArticleCate&tmpl=component',{
										catid    : id
									}).success( function( responce ){
										$('#article-cate-list').html(responce);
									});
								});
							});
							// process action for button save link
							$('#savelink').click(function(){
								var link = $('#linkchild').val();
								if(imageId!='')
									parent = top.document.getElementById('image_link_'+imageId).value = link;
								else
									parent = top.document.getElementById('image_link').value = link;
								$('#dialogbox2').dialog('close');
							});
						});
					})(jQuery);
				  ";

			$document->addScriptDeclaration($js_code);


			$app = JFactory::getApplication();

			$showListID  			= $app->getUserState('com_imageshow.images.showlistID');
			$sourceName  			= $app->getUserState('com_imageshow.images.sourceName');
			$sourceType 			= $app->getUserState('com_imageshow.images.sourceType');
			$imageID     			= $app->getUserState('com_imageshow.images.imageID');
			$imageSource			= JSNISFactory::getSource( $sourceName, $sourceType, $showListID );
			$model 					= &$this->getModel();

			$image 	 				= $model->getItems($imageID,$showListID);
			$categories 			= $model->getTreeMenu();
			$articles_catgories 	= $model->getTreeArticle();
			$this->assign('categories', $categories);
			$this->assign('articles_catgories', $articles_catgories);

			$this->assign('image', $image);

			parent::display($tpl);
		}
}
?>
