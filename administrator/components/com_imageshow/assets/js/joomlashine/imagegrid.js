(function($){ 
	/**
	 * Get the value of a cookie with the given name.
	 *
	 * @example $.cookie('the_cookie');
	 * @desc Get the value of a cookie.
	 *
	 * @param String name The name of the cookie.
	 * @return The value of the cookie.
	 * @type String
	 *
	 * @name $.cookie
	 * @cat Plugins/Cookie
	 * @author Klaus Hartl/klaus.hartl@stilbuero.de
	 */
	$.cookie = function(name, value, options) {
	    if (typeof value != 'undefined') { // name and value given, set cookie
	        options = options || {};
	        if (value === null) {
	            value = '';
	            options.expires = -1;
	        }
	        var expires = '';
	        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
	            var date;
	            if (typeof options.expires == 'number') {
	                date = new Date();
	                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
	            } else {
	                date = options.expires;
	            }
	            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
	        }
	        // CAUTION: Needed to parenthesize options.path and options.domain
	        // in the following expressions, otherwise they evaluate to undefined
	        // in the packed version for some reason...
	        var path = options.path ? '; path=' + (options.path) : '';
	        var domain = options.domain ? '; domain=' + (options.domain) : '';
	        var secure = options.secure ? '; secure' : '';
	        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
	    } else { // only name given, get cookie
	        var cookieValue = null;
	        if (document.cookie && document.cookie != '') {
	            var cookies = document.cookie.split(';');
	            for (var i = 0; i < cookies.length; i++) {
	                var cookie = jQuery.trim(cookies[i]);
	                // Does this cookie string begin with the name we want?
	                if (cookie.substring(0, name.length + 1) == (name + '=')) {
	                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
	                    break;
	                }
	            }
	        }
	        return cookieValue;
	    }
	};
	/**
	 * Image  
	 */
	$.JSNISImageGird = function( options ){
		var ImageGird             = this;
		/**
		 * Class of HTML element, class of element images listing
		 * 
		 */
		ImageGird.classImagesSort = '.videos';
		ImageGird.receive 		  = false;
		/**
		 * Class of HTML element, class for element multiple
		 */
		ImageGird.classMultiple   = '.image-item-multiple-select';
		/**
		 * jQuery element, parent of source images listing
		 */
		ImageGird.sourceImages    = $('#source-images');
		/**
		 * jQuery element, parent of showlist images
		 */
		ImageGird.showlistImages  = $('#showlist-videos');
		/**
		 * Delete images showlist button
		 */
		ImageGird.deleteImageShowlistBtt   = $('#delete-video-showlist');
		/**
		 * Edit image showlist button
		 */
		ImageGird.editImageShowlistBtt     = $('#edit-video-showlist');
		/**
		
		/**
		 * Select link image popup
		 */
		ImageGird.selectlinkBtt     = $('#select-link');
		/**
		
		 * Move image to showlist button
		 */
		ImageGird.moveImageToShowlistBtt   = $('#move-selected-video-source');
		/**
		 * Header of source images
		 */
		ImageGird.sourcePanelHeader        = $('#source-video-header');
		/**
		 * Header of showlist
		 */
		ImageGird.showlistPanelHeader      = $('#showlist-video-header');
		/**
		 * Header of tree control
		 */
		ImageGird.treePanelHeader          = $('#jsn-header-tree-control');
		/**
		 * Variable to store JSN jTree
		 */
		ImageGird.jsnjTree;
		/**
		 * Make option object for script
		 */
		ImageGird.options         = $.extend({}, options);
		/**
		 * Variable UILayout
		 */
		ImageGird.UILayout;
		/**
		 * Object variables rate of layout
		 */
		ImageGird.resizeRate;
		/**
		 * Object variables store UILayout panel
		 */
		ImageGird.freeImageLimit = 10;
		
		ImageGird.panels          = {
			/**
			 * jQuery element for init UILayout
			 */
			panelFull   : $('#showlist-video-layout'),
			/**
			 * jQuery element west layout
			 */
			panelWest   : $('#panel-west'),
			/**
			 * jQuery element center layout
			 */
			panelCenter : $('#panel-center')
		};
		/**
		 * Object jQuery variables content of grid
		 */
		ImageGird.contents        = {
			/**
			 * jQuery element tree categories of image
			 */
			categories     : $('#jsn-jtree-categories'),
			/**
			 * jQuery element source image container
			 */
			sourceimages   : $('#sourcevideo-container'),
			/**
			 * jQuery element showlist image container
			 */
			showlistimages : $('#showlistvideo-container')
		};
		/**
		 * Cookie store
		 */
		ImageGird.cookie          = {
			set    : function(name, value){
				$.cookie( name, value );
			},
			get    : function( name, type ){
				switch(type){
					case 'int'  :
						return parseInt( $.cookie(name) );
					case 'float' : 
						return parseFloat( $.cookie(name) );
					default:
						return $.cookie( name );
				}
			},
			exists : function(name){
				return $.cookie( name ) == null ? false : true;
			}
		};
		/**
		 * Initialize image grid
		 */
		ImageGird.initialize      = function(){	
			
			//ImageGird.repaddingImage();

			/*ImageGird.showlistImages.find('div.video-item').each(function(){
				    var src = $(this).find('#linkcheck').val();						  		    
				    var http = $.ajax({
					    type:"HEAD",
					    url: src,
					    async: false
					  })
				   var check = http.status;	
				   if(check==404){
				   		$(this).find('.image_link').addClass('noimage');
				   		//$(this).find('.image_link img').attr('src','aa');
				   		$(this).find('.image_link img').remove();
				   		$(this).find('.image_link').append('<img src="" style="max-height: 60px; max-width: 80px; padding-top: 30px !important; border: none !important;" />');
				   }
			});*/
			if ( ImageGird.options.selectMode == 'sync' ){
				$.when(
					ImageGird.initEvents()
				).then(
					$.when(
						// show images the first album choosed 

						//showLoading(),
						ImageGird.syncRefreshing()
					).then(
						showLoading({removeall:true})
					)
				);
			}else if(ImageGird.options.removeload == 1){
					showLoading({removeall:true});
			}else{				
				$.when(
					//showLoading(false),
					//showLoading({removeall:false}),
					ImageGird.initEvents()
				).then(
					//showLoading({removeall:true})
				);
			}
		};

		ImageGird.SelectAllImages = function(val){
			if(val=='source'){
				ImageGird.sourceImages.find('div.video-item').each(function(){				
					ImageGird.multipleselect.select($(this) );
				});	
				ImageGird.moveImageToShowlistBtt.addClass('active');				
			}else{
				ImageGird.showlistImages.find('div.video-item').each(function(){				
					ImageGird.multipleselect.select($(this) );
				});
				ImageGird.editImageShowlistBtt.addClass('active');				
				ImageGird.deleteImageShowlistBtt.addClass('active');
			}
			ImageGird.activeButtonsAction();
		};

		ImageGird.DeselectAll 	  = function(val){		
			if(val=='source'){	
				ImageGird.multipleselect.deSelectAll(ImageGird.sourceImages);
				ImageGird.moveImageToShowlistBtt.removeClass('active');
			}else{
				ImageGird.multipleselect.deSelectAll(ImageGird.showlistImages);
				ImageGird.editImageShowlistBtt.removeClass('active');				
				ImageGird.deleteImageShowlistBtt.removeClass('active');
			}
		}

		ImageGird.RevertSelection = function(val){	
			if(val=='source'){			
				ImageGird.sourceImages.find('div.video-item').each(function(){				
					if($(this).hasClass('image-item-multiple-select')){
						$(this).removeClass('image-item-multiple-select');						
					}else{
						ImageGird.multipleselect.select($(this) );						
					}
				});
				var count = ImageGird.sourceImages.find('.image-item-multiple-select').length;				
				if(parseInt(count) > 0){					
					ImageGird.moveImageToShowlistBtt.addClass('active');	
				}else{
					ImageGird.moveImageToShowlistBtt.removeClass('active');	
				}
			}else{
				ImageGird.showlistImages.find('div.video-item').each(function(){				
					if($(this).hasClass('image-item-multiple-select')){
						$(this).removeClass('image-item-multiple-select');
					}else{
						ImageGird.multipleselect.select($(this) );
					}
				});
				var count = ImageGird.showlistImages.find('.image-item-multiple-select').length;				
				if(parseInt(count) > 0){					
					ImageGird.editImageShowlistBtt.addClass('active');				
					ImageGird.deleteImageShowlistBtt.addClass('active');
				}else{
					ImageGird.editImageShowlistBtt.removeClass('active');				
					ImageGird.deleteImageShowlistBtt.removeClass('active');
				}
				
			}
		}

		ImageGird.removecatSelected  = function(){
			ImageGird.contents.categories.find('ul li').each(function(){							
							$(this).addClass('catsyn');
						});
		}	

		ImageGird.repaddingImage = function(){
			ImageGird.sourceImages.find('div.video-thumbnail img').each(function(){
				 $(this).load(function() {	
					var imageHeight 	= $(this).height();				
					var parentheight    = $('div.video-thumbnail').height();
					var padding = parentheight/2 - imageHeight/2-5;
					$(this).css('padding-top',padding);
				 })
			});
			ImageGird.showlistImages.find('div.video-thumbnail img').each(function(){	
				 $(this).load(function() {			
					var imageHeight 	= $(this).height();				
					var parentheight    = $('div.video-thumbnail').height();					
					var padding = parentheight/2 - imageHeight/2-5;					
					$(this).css('padding-top',padding);
				});
			});
		}

		/**
		* Reset Detail Images
		*/
		ImageGird.ResetDetailImages = function(){
			var count = ImageGird.showlistImages.find('.image-item-multiple-select').length;
			if(count>0){
				// ajax reset detail of images.				
				ImageGird.showlistImages.find('.image-item-multiple-select').each(function(){					
					$.post( baseUrl+'administrator/index.php?option=com_imageshow&controller=image&task=resetImageDetails', {
						showlist_id : ImageGird.options.showListID,
						image_extid : $(this).attr('id'),
						album_extid	: $(this).find('.video-info').attr('id'),
						img_detail  : $(this).find('input.img_detail').val()
					}).success(function(res){

					})
					$(this).find('div.modified').removeClass('modified');	
				});					
			}else{
				$("#dialogbox").html('<div style="width:100%; text-align:center; font-size:16px; font-weight:500; margin-top:30px; ">No item is selected</div>').dialog(
								{	
									width: 600, 
									modal: true,
									title: '<span style="font-size: 15px; font-weight:bold;">Confirmation</span>',
									buttons: [
								    {
								        text: "Close",
								        click: function() { $(this).dialog("close"); }
								    }
									]
								});	
			}	
		}

		/**
		* Purge Absolete Images
		*/
		ImageGird.PurgeAbsoleteImages = function(){
			
				// process reset detail of each images is selected
				ImageGird.showlistImages.find('div.video-item, div.image-item-multiple-select').each(function(){
				    var src = $(this).find('#linkcheck').val();				    
				    var http = $.ajax({
					    type:"HEAD",
					    url: src,
					    async: false
					  })
				   var check = http.status;				   	
				   if(check ==404){				   		
				   		$.post( baseUrl+'administrator/index.php?option=com_imageshow&controller=image&task=PurgeAbsoleteImages', {
							showListID : ImageGird.options.showListID,
							ImageID    : $(this).attr('id')
						}).success(function(res){

						})
						$(this).fadeOut(1500,function(){
							ImageGird.indexImages();		
							ImageGird.contentResize();
							ImageGird.multipleselect.init();
							$(this).remove();
							$('div[id="'+$(this).attr('id')+'"]', ImageGird.sourceImages).each(function(){
								$(this).removeClass('image-item-is-selected').addClass('video-item').children('div.img-mark-isselected').remove();
							});											
							ImageGird.removeselectedAlbum();							
						});	
				   }
				});		
			
		}
		/**
		 * Set options
		 */
		ImageGird.setOption       = function(name, value){
			if ( typeof name == 'Array' ){
				for(k in name){
					ImageGird.options[name] = value;
				}
			}else{
				if ( ImageGird.options[name] != undefined ){
					ImageGird.options[name] = value;
				}
			}
		};
		/**
		* 
		* Init layout
		*
		* @return: None 
		*/
		ImageGird.initLayout      = function(){			
			ImageGird.panels.panelFull.css('width', ImageGird.options.layoutWidth);
			ImageGird.panels.panelFull.css('height', ImageGird.options.layoutHeight);
			ImageGird.contents.categories.css('height', ImageGird.options.layoutHeight - 145);
			ImageGird.contents.sourceimages.css('height', ImageGird.options.layoutHeight - 95);
			ImageGird.contents.showlistimages.css('height', ImageGird.options.layoutHeight - 95);
			ImageGird.contents.categories.parents('div.panel-left').css({
				'border-right' 	: '1px solid #999999',
				'margin-top'	: '-'+(ImageGird.contents.categories.parents('div.panel-left').height() - 1)+'px' 
			});
			ImageGird.UILayout = ImageGird.panels.panelFull.layout({				
				west__onresize: function(){
					ImageGird.contentResize();
				},
				west__onopen: function(){
					ImageGird.contentResize();
				},
				west__onclose: function(){
					if ( ImageGird.sourceImages.hasClass('showlist')){
						ImageGird.sourceImages.find('div.video-item').removeAttr('style');
						ImageGird.sourceImages.find('div.image-item-is-selected').removeAttr('style');
					}					
					if ( ImageGird.showlistImages.hasClass('showlist')){
						ImageGird.showlistImages.find('div.video-item').removeAttr('style');
					}
					ImageGird.contentResize();
				},
				onresizeall_end: function(){
					setTimeout(function(){
						ImageGird.calculatorRate();
						if ( ImageGird.sourceImages.hasClass('showlist')){
							ImageGird.sourceImages.find('div.video-item').removeAttr('style');
							ImageGird.sourceImages.find('div.image-item-is-selected').removeAttr('style');
						}
						
						if ( ImageGird.showlistImages.hasClass('showlist')){
							ImageGird.showlistImages.find('div.video-item').removeAttr('style');
						}
						ImageGird.contentResize();
					}, 200);
				},
				ondrag_end: function(){
					setTimeout(function(){
						ImageGird.calculatorRate();						
						if ( ImageGird.sourceImages.hasClass('showlist')){
							ImageGird.sourceImages.find('div.video-item').removeAttr('style');
							ImageGird.sourceImages.find('div.image-item-is-selected').removeAttr('style');
						}
						
						if ( ImageGird.showlistImages.hasClass('showlist')){
							ImageGird.showlistImages.find('div.video-item').removeAttr('style');
						}
						ImageGird.contentResize();
						ImageGird.cookie.set('rate_of_west', ImageGird.resizeRate.west );
					}, 200);
				}
			});
			ImageGird.panels.panelWest.css('position', '');
			if ( $.browser.msie ){
				ImageGird.contents.showlistimages.parents('div.sourcevideo-panel-container').css('margin-top', '-10px');
			}
			/**
			 * Restore layout resize
			 */
			if ( ImageGird.cookie.exists('rate_of_west') ){
				var fullWidth = ImageGird.panels.panelFull.outerWidth();
				ImageGird.UILayout.sizePane("west", ImageGird.cookie.get('rate_of_west', 'int')*ImageGird.panels.panelFull.outerWidth()/100);
			}
			/**
			 * Call calcaulator rate
			 */
			ImageGird.calculatorRate();
			/**
			 * Auto-resize when window resize
			 */
			$(window).resize(function(){
				var fullWidth = $('#showlist-video-layout').width();
				ImageGird.UILayout.sizePane("west", ImageGird.resizeRate.west*ImageGird.panels.panelFull.outerWidth()/100);
			});
		};
		/**
		* 
		* Calculator rate size
		* 
		* @return: Calculator rate of width
		*/
		ImageGird.calculatorRate  = function(){
			var westWidth        = ImageGird.panels.panelWest.innerWidth();
			var centerWirth      = ImageGird.panels.panelCenter.innerWidth();
			var fullWidth        = ImageGird.panels.panelFull.outerWidth();
			ImageGird.resizeRate = {west: westWidth*100/fullWidth, center: centerWirth*100/fullWidth};
		};
		/**
		 * Content resize
		 */
		ImageGird.contentResize   = function(){
			$(ImageGird.classImagesSort).each(function(){
				if ($(this).parents('div.ui-layout-center').length){
					if ($(this).find('div.video-item').length){
						$(this).removeClass('empty-video');
					}else{
						$(this).addClass('empty-video');
					}
				}
				if ( $(this).children('div:last').attr('class') != 'clr'){
					$(this).children('.clr').remove().end().append('<div class="clr" />');
				}
			});

			if (ImageGird.contents.categories.children('ul').height() > ImageGird.contents.categories.height()){
				ImageGird.contents.categories.css({
					'overflow-x' : 'hidden',
					'overflow-y' : 'scroll'
				});
			}else{
				ImageGird.contents.categories.css({
					'overflow-x' : 'hidden',
					'overflow-y' : 'hidden'
				});
			}
			
			if (ImageGird.contents.sourceimages.children('div.videos').height() > ImageGird.contents.sourceimages.height()){
				ImageGird.contents.sourceimages.css({
					'overflow-x' : 'hidden',
					'overflow-y' : 'scroll'
				});
			}else{
				ImageGird.contents.sourceimages.css({
					'overflow-x' : 'hidden',
					'overflow-y' : 'hidden'
				});
			}
			
			if (ImageGird.contents.showlistimages.children('div.videos').height() > ImageGird.contents.showlistimages.height()){
				ImageGird.contents.showlistimages.css({
					'overflow-x' : 'hidden',
					'overflow-y' : 'scroll'
				});
			}else{
				ImageGird.contents.showlistimages.css({
					'overflow-x' : 'hidden',
					'overflow-y' : 'hidden'
				});
			}
			
			if ( ImageGird.contents.sourceimages.children('div.videos').hasClass('showlist') && ImageGird.contents.sourceimages.find('div.video-item').length > 0){
				if ( ImageGird.contents.sourceimages.css('overflow-y') == 'scroll' ){
					ImageGird.contents.sourceimages.find('div.video-item').css('width', ImageGird.contents.sourceimages.width() - 40);
					ImageGird.contents.sourceimages.find('div.image-item-is-selected').css('width', ImageGird.contents.sourceimages.width() - 40);
				}
				
				var tmpItem = ImageGird.contents.sourceimages.find('div.video-item:first');
				var contaierWidth   = tmpItem.innerWidth();
				var thumbnailHeight = tmpItem.children('div.video-thumbnail').outerHeight();
				var thumbnailWidth  = tmpItem.children('div.video-thumbnail').outerWidth();				
				ImageGird.contents.sourceimages.find('div.video-info').css({
					//'width'  : contaierWidth - thumbnailWidth - 5,					
					'width'  : 'auto',
					//'width'  : '85%',					
					'height' : 'auto' //thumbnailHeight-1					
				});

				/*if ( $.browser.mozilla ){
					ImageGird.contents.sourceimages.find('div.video-item').css('height', thumbnailHeight + tmpItem.children('div.video-index').height() + 1);
				}else{
					ImageGird.contents.sourceimages.find('div.video-item').css('height', thumbnailHeight + tmpItem.children('div.video-index').height());
				}
				ImageGird.contents.sourceimages.find('div.image-item-is-selected').css('height', thumbnailHeight + tmpItem.children('div.video-index').height());
				*/
			}else{
				ImageGird.contents.sourceimages.find('div.video-item').removeAttr('style');
				ImageGird.contents.sourceimages.find('div.image-item-is-selected').removeAttr('style');
			}
			
			if ( ImageGird.contents.showlistimages.children('div.videos').hasClass('showlist') && ImageGird.contents.showlistimages.find('div.video-item').length > 0){
				if ( ImageGird.contents.showlistimages.css('overflow-y') == 'scroll' ){
					ImageGird.contents.showlistimages.find('div.video-item').css('width', 'auto'); //ImageGird.contents.showlistimages.width() - 40
				}
				
				var tmpItem = ImageGird.contents.showlistimages.find('div.video-item:first');
				var contaierWidth  = tmpItem.innerWidth();
				var thumbnailHeight= tmpItem.children('div.video-thumbnail').outerHeight();
				var thumbnailWidth = tmpItem.children('div.video-thumbnail').outerWidth();
				ImageGird.contents.showlistimages.find('div.video-info').css({
					//'width'  : contaierWidth - thumbnailWidth - 5,
					'width'  : contaierWidth - thumbnailWidth - 100,
					'height' : 'auto'//thumbnailHeight
				});
				ImageGird.contents.showlistimages.find('div.video-item').css('height', 'auto'); //thumbnailHeight + tmpItem.children('div.video-index').height()
			}else{
				ImageGird.contents.showlistimages.find('div.video-item').removeAttr('style');
			}
			setTimeout(function(){
				ImageGird.contents.categories.parents('div.panel-left').css({
					'border-right' 	: '1px solid #999999',
					'margin-top'	: '-'+(ImageGird.contents.categories.parents('div.panel-left').height() - 1)+'px' 
				});
			}, 500);
		};
		
		/**
		 * Show imgloading for image thumbnail
		 */
		ImageGird.imageLoading    = function(imgList){
			imgList.each(function(i){
				if ($(this).parents('div.video-item').length > 0){
					var parent = $(this).parents('div.video-item');
				}else{
					var parent = $(this).parents('div.image-item-is-selected');
				}
				if ( !parent.data('image_is_loaded')  && parent.find('img.imgloading').length == 0 ){
					$(this).css('opacity', '0.5');
					$('<img />', {
						'class'  : 'imgloading',
						'src'    : baseUrl + 'administrator/components/com_imageshow/assets/images/ajax-loader-circle.gif'
					}).appendTo(parent);
				}
			}).load(function(){
				if ($(this).parents('div.video-item').length > 0){
					var parent = $(this).parents('div.video-item');
				}else{
					var parent = $(this).parents('div.image-item-is-selected');
				}
				if ( !parent.data('image_is_loaded') ) {
					$(this).css('opacity', '1');
					parent.find('img.imgloading').remove();
					parent.data('image_is_loaded', true);
				}
			});
		};
		/**
		 * Sync refreshing
		 */
		ImageGird.syncRefreshing  = function(treeRoot){
			if ( treeRoot == undefined ){
				treeRoot = ImageGird.jsnjTree.getContainer();
			}			
			treeRoot.children('li').each(function(){
				var current = $(this);				
				$.when(
					$.post( baseUrl+'administrator/index.php?option=com_imageshow&controller=images&task=checksyncalbum', {
						showListID : ImageGird.options.showListID,
						sourceType : ImageGird.options.sourceType,
						sourceName : ImageGird.options.sourceName,
						syncCate   : current.attr('id')
					}).success(function(res){
						if ( res == 'is_selected' ){							
							current.children('input.sync').attr('checked', true);
							//ImageGird.getImagesSync( current.attr('id'), 'append' );
						}
					})

				).then(
					//Call sync to childrens items
					ImageGird.syncRefreshing( current.children('ul') )
				);
			});
		};
		/**
		 * Get source images and showlist images by sync mode
		 */
		ImageGird.getImagesSync = function( syncName, typeAppend ){
			$.post( baseUrl+'administrator/index.php?option=com_imageshow&controller=images&task=loadSourceImages',{
				showListID : ImageGird.options.showListID,
				sourceType : ImageGird.options.sourceType,
				sourceName : ImageGird.options.sourceName,
				selectMode : ImageGird.options.selectMode,
				cateName   : syncName
			}).success( function(res){							
				if (typeAppend == 'append' ){					
					/**
					 * Append response to source videos
					 */
					ImageGird.sourceImages.html(res);

					ImageGird.sourceImages.find('div.video-item').removeClass('video-item').addClass('image-item-is-selected').append('<div class="clr"></div><div class="img-mark-isselected">&nbsp;</div>');
					/**
					 * Add all images to showlist
					 */
					ImageGird.showlistImages.find('div.clr').remove();
					/**
					 * Append response to showlist
					 */
					ImageGird.showlistImages.append(res);
					/**
					 * Remove div
					 */
					ImageGird.showlistImages.find('div.img-mark-isselected').remove();
					/**
					 * Add div clear
					 */
					ImageGird.showlistImages.append('<div class="clr"></div>');
					/**
					 * Move all images from source to showlist
					 */
					ImageGird.showlistImages.find('div.image-item-is-selected').removeClass('image-item-is-selected').addClass('video-item');
					/**
					 * Init events
					 */
					ImageGird.initEvents();					
					//Save showlist
					//ImageGird.saveShowlist();
					// repadding for image 
					//ImageGird.repaddingImage();
				}else{					
					/**
					 * Append response to source videos
					 */
					ImageGird.sourceImages.html(res);
					/**
					 * Add all video to showlist
					 */
					res = '<div id="res-videos">'+res+'</div>';
					$(res).children().each(function(){
						$('div[id="'+$(this).attr('id')+'"]', ImageGird.sourceImages).removeClass('image-item-is-selected').addClass('video-item');
						$('div[id="'+$(this).attr('id')+'"]', ImageGird.showlistImages).remove();
					});
					/**
					 * Init events
					 */
					ImageGird.initEvents();
				}
				//Add image loading thumbnail
				ImageGird.imageLoading(ImageGird.sourceImages.find('img[alt="video thumbnail"]'));
				ImageGird.imageLoading(ImageGird.showlistImages.find('img[alt="video thumbnail"]'));
				ImageGird.showlistImages.find('div.video-thumbnail').addClass('loaded');

				ImageGird.sourceImages.find('div.video-thumbnail').addClass('loaded');
				setTimeout(function(){
					showLoading({removeall:true});
				}, 500);
			});
		};
		/**
		 * Init events
		 */
		ImageGird.initEvents      = function(){
			
			/**
			 * Init JSN jTree
			 */
			if ( ImageGird.contents.categories.data('jsn_jtree_initialized', undefined) === undefined){
				if ( ImageGird.options.selectMode == 'sync'){					
					var jsnjTreeOptions = {
						syncmode : true
					};
				}else{
					var jsnjTreeOptions = {
						syncmode : false
					};
				}

				ImageGird.jsnjTree = ImageGird.contents.categories.jsnjtree(jsnjTreeOptions).bind('jsn_jtree.selectitem', function(e, obj){		
					showLoading({removeall:false});
					$.when(
						$.post( baseUrl+'administrator/index.php?option=com_imageshow&controller=images&task=loadSourceImages', {
							showListID : ImageGird.options.showListID,
							sourceType : ImageGird.options.sourceType,
							sourceName : ImageGird.options.sourceName,
							selectMode : ImageGird.options.selectMode,
							cateName   : obj.attr('id')
						}).success( function(res){													
							ImageGird.sourceImages.html(res);
							//Add image thumbnail
							ImageGird.imageLoading(ImageGird.sourceImages.find('img[alt="video thumbnail"]'));
							/**
							 * Init events
							 */							 
							ImageGird.initEvents();
							ImageGird.sourceImages.find('div.video-thumbnail').addClass('loaded');
							setTimeout(function(){						

								showLoading({removeall:true});
							}, 500);
							// add class loaded to each image thumnail
							ImageGird.sourceImages.find('div.video-item').each(function(){
								$(this).find('div.video-thumbnail').addClass('loaded');
							});

							// repadding for image
							//ImageGird.repaddingImage();
						})
					);
				}).bind("jsn_jtree.sync", function(e, obj){
					showLoading();
					if ( obj.attr('checked') == 'checked' ){

						/**
						 * Save sync checked
						 */
						ImageGird.showlistImages.find('.showlist-sync-image-notice').remove();
						$.post( baseUrl+'administrator/index.php?option=com_imageshow&controller=images&task=savesync', {
							showlist_id : ImageGird.options.showListID,
							sourceType : ImageGird.options.sourceType,
							sourceName : ImageGird.options.sourceName,
							album_extid   : obj.parent().attr('id')
						}).success(function(res){							
							ImageGird.getImagesSync( obj.parent().attr('id'), 'append' );
						});
					}else{		

						$.post( baseUrl+'administrator/index.php?option=com_imageshow&controller=images&task=removesync', {
							showlist_id : ImageGird.options.showListID,
							sourceType : ImageGird.options.sourceType,
							sourceName : ImageGird.options.sourceName,
							album_extid   : obj.parent().attr('id')
						}).success(function(res){							
							//ImageGird.showlistImages.html('');
							ImageGird.getImagesSync( obj.parent().attr('id'), 'remove' );
							imageGrid.removecatSelected();
						});
					}
				});
			}
			/**
			 * UILayout init
			 */
			ImageGird.initLayout();
			/**
			 * Init multiple
			 */
			if ( ImageGird.options.selectMode == 'sync' ){
				ImageGird.multipleselect.destroy();
			}else{
				ImageGird.multipleselect.init();
			}
			
			/**
			 * Index videos
			 */
			ImageGird.indexImages();
			/**
			 * Init sortable
			 */
			ImageGird.sortable();
			/**
			 * Active button
			 */
			ImageGird.activeButtonsAction();
			/**
			 * Move video
			 */
			ImageGird.sourceImages.find('button.move-to-showlist').unbind("click").click(function(){
				var _append;
				showLoading({removeall:false});
				ImageGird.contents.categories.find('a.jtree-selected').parent().addClass('catselected');
				ImageGird.moveVideoToShowlist( $(this).parents('div.video-item'),_append,1,1 );
				//$('.image-item-multiple-select',ImageGird.sourceImages).removeClass('image-item-multiple-select').removeClass('multiselectable-previous');	
				///ImageGird.moveImageToShowlistBtt.removeClass('active');
				
			});
			/**
			 * Animate to change videos show type
			 */
			$('button', ImageGird.sourcePanelHeader).unbind("click").click(function(){
				if ( $(this).hasClass('video-show-grid') && !$(this).hasClass('active') ){
					$(this).addClass('active');
					$(this).next().removeClass('active');
					ImageGird.sourceImages.fadeOut(300, function(){
						$(this).removeClass('showlist');
						
						ImageGird.contents.sourceimages.find('div.video-item').removeAttr('style');
						ImageGird.contents.sourceimages.find('div.image-item-is-selected').removeAttr('style');
						
						$(this).addClass('showgrid').fadeIn(300, function(){
							//Set status to cookie store
							ImageGird.cookie.set('source_video_is_showlist', false);
							ImageGird.contentResize();
						});
					});
				}else if($(this).hasClass('video-show-list') && !$(this).hasClass('active')){
					$(this).addClass('active');
					$(this).prev().removeClass('active');
					ImageGird.sourceImages.fadeOut(300, function() {
					  $(this).removeClass('showgrid').addClass('showlist').fadeIn(300, function(){
					  		//Set status to cookie store
					  		ImageGird.cookie.set('source_video_is_showlist', true);
							ImageGird.contentResize();
					  });
					});
				}
			});
			/**
			 * Animate to change showlist videos show types
			 */
			$('button', ImageGird.showlistPanelHeader ).unbind("click").click(function(){
				if ( $(this).hasClass('video-show-grid') && !$(this).hasClass('active') ){
					$(this).addClass('active');
					$(this).next().removeClass('active');
					ImageGird.showlistImages.fadeOut(300, function(){
						$(this).removeClass('showlist');
						ImageGird.contents.showlistimages.find('div.video-item').removeAttr('style');
						$(this).addClass('showgrid').fadeIn(300, function(){
							//Set status to cookie store
							ImageGird.cookie.set('showlist_video_is_showlist', false);
							ImageGird.contentResize();
						});
					});
				}else if($(this).hasClass('video-show-list') && !$(this).hasClass('active')){
					$(this).addClass('active');
					$(this).prev().removeClass('active');
					ImageGird.showlistImages.fadeOut(300, function(){
						$(this).removeClass('showgrid').addClass('showlist').delay(300).fadeIn(300, function(){
							//Set status to cookie store
							ImageGird.cookie.set('showlist_video_is_showlist', true);
							ImageGird.contentResize();
						});
					});
				}
			});
			
			/**
			 * Button to change jsnjtree
			 */
			$('button', ImageGird.treePanelHeader).unbind("click").click(function(){
				if ( $(this).hasClass('expand') ){
					ImageGird.jsnjTree.expand_all();
				}else if ( $(this).hasClass('collapse') ){
					ImageGird.jsnjTree.collapse_all();
				}else if( $(this).hasClass('sync') ){
					if ( $(this).hasClass('active') ){						
						$(this).removeClass('active');
						//Show delete showlist image button
						ImageGird.deleteImageShowlistBtt.show();
						//Show edit showlist image button
						ImageGird.editImageShowlistBtt.show();
						//Show move source image button
						ImageGird.moveImageToShowlistBtt.show();
						//Change mode to normal
						ImageGird.setOption('selectMode', '');
						//Remove sync
						ImageGird.jsnjTree.removeSync();
						//Add notice
						//ImageGird.showlistImages.html('<div class="showlist-drag-drop-video-notice">Drag and drop images here</div>');						
						// remove sync 
						ImageGird.removeSync();		
						ImageGird.showlistImages.html('<div class="video-no-found">No images found</div>');			
						//Save showlist
						ImageGird.saveShowlist();						
						//Set empty source images
						ImageGird.sourceImages.html('<div class="video-no-found">No images found</div>');
						//Uncheck selected category item
						ImageGird.contents.categories.find('a.jtree-selected').removeClass('jtree-selected');
					}else{
						var syncButton = $(this);						
						if (ImageGird.showlistImages.find('div.video-item').length > 0){

							var jsnConfirm = $.JSNUIConfirm('Sync enabling mode confirmation', 'When enabling "Sync mode", all current images in the showlist will be removed from it. <div style="width:100%; text-align:center; margin-top:20px; font-size:14px; font-weight:bold;"> Do you want to continue?</div>',
						    {
									width:  500,
									height: 180,
									modal:  true,
									buttons: {
										'Ok': function(){
											//Change mode to normal
											ImageGird.setOption('selectMode', 'sync');
											//Hide delete showlist video button
											ImageGird.deleteImageShowlistBtt.hide();
											//Hide edit video showlist button
											ImageGird.editImageShowlistBtt.hide();
											//Hide move source video button
											ImageGird.moveImageToShowlistBtt.hide();
											//Set empty source images
											ImageGird.sourceImages.html('<div class="video-no-found">No images found</div>');
											//Uncheck selected category item
											ImageGird.contents.categories.find('a.jtree-selected').removeClass('jtree-selected');
											//Add sync button to active
											syncButton.addClass('active');
											//Add notice
											//ImageGird.showlistImages.html('<div class="showlist-drag-drop-video-notice">Drag and drop images here</div>');
											ImageGird.showlistImages.html('<div class="showlist-sync-image-notice">Showlist is in Sync mode</div>');
											// Resize content
											ImageGird.contentResize();
											//Save showlist
											ImageGird.saveShowlist();
											//Add sync
											ImageGird.jsnjTree.sync();
											//Close dialog
											jsnConfirm.dialog('close');
											// remove class catselected
											//imageGrid.removecatSelected();	
											ImageGird.contents.categories.find('ul li').each(function(){							
												$(this).addClass('catsyn');
											});
										},
										'Cancel': function(){
											jsnConfirm.dialog('close');
										}
									}
								}
						    );
						}else{
							//Change mode to normal
							ImageGird.setOption('selectMode', 'sync');
							//Hide delete showlist button
							ImageGird.deleteImageShowlistBtt.hide();
							//Hide edit showlist button
							ImageGird.editImageShowlistBtt.hide();
							//Hide move video from source video
							ImageGird.moveImageToShowlistBtt.hide();
							//Set empty source images
							ImageGird.sourceImages.html('<div class="video-no-found">No images found</div>');
							//Uncheck selected category item
							ImageGird.contents.categories.find('a.jtree-selected').removeClass('jtree-selected');
							//Add sync button to active
							syncButton.addClass('active');
							//Remove all video in the showlist
							//ImageGird.showlistImages.html('<div class="showlist-drag-drop-video-notice">Drag and drop images here</div>');							
							ImageGird.showlistImages.html('<div class="showlist-sync-image-notice">Showlist is in Sync mode</div>');
							//ImageGird.showlistImages.html('<div class="showlist-sync-image-notice">Showlist is in Sync mode</div>');
							// Resize content
							ImageGird.contentResize();
							//Save showlist
							ImageGird.saveShowlist();
							//Add sync
							ImageGird.jsnjTree.sync();
						}
					}
				}
			});
			
			//Restore source videos showtype
			if ( ImageGird.cookie.exists('source_video_is_showlist') && ImageGird.cookie.get('source_video_is_showlist') == 'true' ){
				ImageGird.sourcePanelHeader.children('button.video-show-grid').removeClass('active');
				ImageGird.sourcePanelHeader.children('button.video-show-list').addClass('active');
				ImageGird.sourceImages.removeClass('showgrid').addClass('showlist');
			}
			//Restore showlist videos showtype
			if ( ImageGird.cookie.exists('showlist_video_is_showlist') && ImageGird.cookie.get('showlist_video_is_showlist')  == 'true' ){
				ImageGird.showlistPanelHeader.children('button.video-show-grid').removeClass('active');
				ImageGird.showlistPanelHeader.children('button.video-show-list').addClass('active');
				ImageGird.showlistImages.removeClass('showgrid').addClass('showlist');
			}
			/**
			 * Resize content
			 */
			ImageGird.contentResize();
		};
		/**
		 * Active buttons action
		 */
		ImageGird.activeButtonsAction = function(){
			/**
			 * Edit and Delete video showlist
			 */			
			if ( ImageGird.multipleselect.hasChildSelected(ImageGird.showlistImages) ){								
				ImageGird.editImageShowlistBtt.addClass('active');				
				ImageGird.deleteImageShowlistBtt.addClass('active');				
				ImageGird.editImageShowlistBtt.unbind("click").click(function(){					
					ImageGird.editImage( $(ImageGird.multipleselect.getAll(ImageGird.showlistImages)) );
				});
				
				//ImageGird.deleteVideoShowlistBtt.unbind("click").click(function(){
                ImageGird.deleteImageShowlistBtt.unbind("click").click(function(){                	
					var videosMultipleSelected = ImageGird.multipleselect.getAll(ImageGird.showlistImages);		
								
					var jsnConfirm = $.JSNUIConfirm('Confirmation', '<div style="width:100%; text-align:center; font-size:16px; font-weight:500; margin-top:30px; ">Are you sure you want to remove selected images?</div>',
				    {
						width:  500,
						height: 180,
						modal:  true,
						buttons: {
							'Ok': function(){								
								//Delete images confirmation
                                ImageGird.showlistImages.find('div.image-item-multiple-select').each(function(){  
                                        var id = $(this).attr('id');
										$.post(baseUrl + 'administrator/index.php?option=com_imageshow&controller=images&task=deleteimageshowlist',{
											showListID : ImageGird.options.showListID,
											sourceName : ImageGird.options.sourceName,
											sourceType : ImageGird.options.sourceType,
											imageID    : id											
										}).success( function( responce ){												
											// remove class "catselected" of each album menu when haven't any image in showlist image											
										});	

										//$(this).trigger("ImageGird.execute.completed");
										$(this).fadeOut(1500,function(){											
											ImageGird.indexImages();		
											ImageGird.contentResize();
											ImageGird.multipleselect.init();			
											$('div[id="'+id+'"]', ImageGird.sourceImages).each(function(){
												$(this).removeClass('image-item-is-selected').addClass('video-item').children('div.img-mark-isselected').remove();
												$(this).children('.clr').remove();
											});								
											$(this).remove();
											ImageGird.removeselectedAlbum();
										}); 																			
								});                                
                                //Close dialog
								jsnConfirm.dialog('close');
								showLoading({removeall:false});
								setTimeout('showLoading({removeall:true})',2000);
							},
							'Cancel': function(){
								jsnConfirm.dialog('close');
							}
						}
					});
//					ImageGird.indexImages();		
//					ImageGird.contentResize();
//					ImageGird.multipleselect.init();



				});
				
			}else{				
				ImageGird.editImageShowlistBtt.removeClass('active').unbind('click');
				ImageGird.deleteImageShowlistBtt.removeClass('active').unbind('click');				
			}
			
			/**
			 * Move selected video source
			 */			
			if ( ImageGird.multipleselect.hasChildSelected( ImageGird.sourceImages ) ){				
				ImageGird.moveImageToShowlistBtt.addClass('active');
				ImageGird.moveImageToShowlistBtt.unbind("click").click(function(){
					ImageGird.contents.categories.find('a.jtree-selected').parent().addClass('catselected');
					showLoading({removeall:false});
					var i = 1;
					var _append;
					var videosMultipleSelected = ImageGird.multipleselect.getAll(ImageGird.sourceImages);
					var totalVideo =  videosMultipleSelected.length;
					
					ImageGird.queueExecute(videosMultipleSelected, 0, function(obj){					   		
						
						ImageGird.moveVideoToShowlist(obj, _append, totalVideo, i);
						
						if (i == totalVideo)
						{
							i = 0;
						}						
						i++;						
					});
				});
			}else{
				ImageGird.moveImageToShowlistBtt.removeClass('active').unbind('click');
			}
		};
		
		index = new Array();		
		/**
		 * Multiple select
		 */
		ImageGird.multipleselect  = {			
			/**
			 * Init multiple select element
			 */
			init : function(){			
				ImageGird.multipleselect.multiselectable();
				/**
				 * Deselect all selected video from source
				 */
				ImageGird.contents.sourceimages.unbind("click").click(function(e){
					
					if ( ImageGird.multipleselect.hasChildSelected(ImageGird.sourceImages) && !$(e.target).parents('div.video-item').length > 0 && !$(e.target).parents('div.image-item-is-selected').length > 0 ){
						$('#showlist-videos div').each(function(){
							$(this).removeAttr('start');
							$('#start_image_showlist').val('');
							$('#stop_image_showlist').val('');
						});
						$('#source-images div').each(function(){
							$(this).removeAttr('start');
							$('#start').val('');
							$('#stop').val('');
						});
						ImageGird.multipleselect.deSelectAll(ImageGird.sourceImages);
						ImageGird.activeButtonsAction();
					}
				});
				/**
				 * Deselecte all selected video from showlist
				 */
				ImageGird.contents.showlistimages.unbind("click").click(function(e){
					if ( ImageGird.multipleselect.hasChildSelected(ImageGird.showlistImages) && !$(e.target).parents('div.video-item').length > 0 ){
						$('#showlist-videos div').each(function(){
							$(this).removeAttr('start');
							$('#start_image_showlist').val('');
							$('#stop_image_showlist').val('');
						});
						$('#source-images div').each(function(){
							$(this).removeAttr('start');
							$('#start').val('');
							$('#stop').val('');
						});
						ImageGird.multipleselect.deSelectAll(ImageGird.showlistImages);
						ImageGird.activeButtonsAction();
					}
				});
			},
			
			multiselectable: function()
			{
				ImageGird.sourceImages.find('div.video-item').unbind("click").click(function(e) {
					var item = $(this),
						parent = item.parent(),
						myIndex = parent.children().index(item),
						prevIndex = parent.children().index(parent.find('.multiselectable-previous'));
					
					if(item.hasClass('image-item-multiple-select')){		//deselect item if it selected currently					
						item.removeClass('image-item-multiple-select');
					}else{
						if (!e.ctrlKey && !e.metaKey)
						{	
							parent.find('.image-item-multiple-select').removeClass('image-item-multiple-select')							
						}
						else {
							if (item.not('.child').length) {
								if (item.hasClass('image-item-multiple-select'))
									item.nextUntil(':not(.child)').removeClass('image-item-multiple-select')
								else
									item.nextUntil(':not(.child)').addClass('image-item-multiple-select')
							}
						}
						
						if (e.shiftKey && prevIndex >= 0) {							
							parent.find('.multiselectable-previous').toggleClass('image-item-multiple-select')
							if (prevIndex < myIndex)
								item.prevUntil('.multiselectable-previous').toggleClass('image-item-multiple-select')
							else if (prevIndex > myIndex)
								item.nextUntil('.multiselectable-previous').toggleClass('image-item-multiple-select')

							$('.image-item-is-selected', ImageGird.sourceImages).removeClass('image-item-multiple-select');	
						}
						
						item.toggleClass('image-item-multiple-select')
						parent.find('.multiselectable-previous').removeClass('multiselectable-previous')
						item.addClass('multiselectable-previous')
						ImageGird.multipleselect.select($(this));	
						ImageGird.activeButtonsAction();
					}
					
				}).disableSelection()
				
				ImageGird.showlistImages.find('div.video-item').unbind("click").click(function(e) {						
					var item = $(this),
						parent = item.parent(),
						myIndex = parent.children().index(item),
						prevIndex = parent.children().index(parent.find('.multiselectable-previous'));
					
					if(item.hasClass('image-item-multiple-select')){			//deselect item if it selected currently				
						item.removeClass('image-item-multiple-select');
					}else{
						if (!e.ctrlKey && !e.metaKey)
						{	
							parent.find('.image-item-multiple-select').removeClass('image-item-multiple-select')							
						}
						else {
							if (item.not('.child').length) {
								if (item.hasClass('image-item-multiple-select'))
									item.nextUntil(':not(.child)').removeClass('image-item-multiple-select')
								else
									item.nextUntil(':not(.child)').addClass('image-item-multiple-select')
							}
						}
						
						if (e.shiftKey && prevIndex >= 0) {
							parent.find('.multiselectable-previous').toggleClass('image-item-multiple-select')
							if (prevIndex < myIndex)
								item.prevUntil('.multiselectable-previous').toggleClass('image-item-multiple-select')
							else if (prevIndex > myIndex)
								item.nextUntil('.multiselectable-previous').toggleClass('image-item-multiple-select')
						}
						
						item.toggleClass('image-item-multiple-select')
						parent.find('.multiselectable-previous').removeClass('multiselectable-previous')
						item.addClass('multiselectable-previous')
						ImageGird.multipleselect.select($(this));	
						ImageGird.activeButtonsAction();
					}
				}).disableSelection()									
			},			
			/**
			 * Destroy 
			 */
			destroy : function(){
				ImageGird.sourceImages.find('div.video-item').unbind("click");
				ImageGird.showlistImages.find('div.video-item').unbind("click");
				ImageGird.contents.sourceimages.unbind("click");
				ImageGird.contents.showlistimages.unbind("click");
			},
			/**
			 * Get all elements was selected for multiple
			 */
			getAll : function(obj){
				return $(ImageGird.classMultiple, obj);
			},
			/**
			 * Count multiple element
			 */
			getTotal : function(obj){
				return $(ImageGird.classMultiple, obj).length;
			},
			/**
			 * Select element
			 */
			select : function(obj){
				obj.addClass(ImageGird.classMultiple.replace('.', ''));
			},
			/**
			 * Deselect element
			 */
			deSelect : function(obj){
				obj.removeClass('.multiselectable-previous'.replace('.', ''));
				obj.removeClass(ImageGird.classMultiple.replace('.', ''));
			},
			/**
			 * Deselect all elements
			 */
			deSelectAll : function(obj){ 
				ImageGird.multipleselect.getAll(obj).removeClass('.multiselectable-previous'.replace('.', ''));
				ImageGird.multipleselect.getAll(obj).removeClass(ImageGird.classMultiple.replace('.', ''));
			},
			/**
			 * Check element multiple
			 */
			hasSelected : function(obj){
				return obj.hasClass(ImageGird.classMultiple.replace('.', ''));
			},
			/**
			 * Check parent have child element are multiple
			 */
			hasChildSelected : function(obj){
				return ( $(ImageGird.classMultiple, obj ).length > 0 ? true : false );
			}
		};
		/**
		* 
		* Init function to set events and data
		* 
		* @param: (array) (objs) is arrray elements
		* @param: (int) (i) is index item need init
		* @return: Init 
		*/
		ImageGird.sortable        = function(){
			ImageGird.sourceImages.sortable({
				connectWith: 'div.showlist-videos',
				opacity: 0.6,
				scroll: true,
				dropOnEmpty: false,
				forceHelperSize: true,				
				cancel: 'div.image-item-is-selected, div.video-no-found, div.showlist-drag-drop-video-notice, div.sync',
				scrollSensitivity: 50,
				helper: function(e, item){	
				
					if ( ImageGird.multipleselect.hasSelected( $(item) ) && ImageGird.multipleselect.getTotal( ImageGird.sourceImages ) > 1 ){
						var container = $('<div />', {
							'class' : 'jsn-video-item-multiple-select-container',
							'id'    : 'jsn-video-item-multiple-select-container'
						});
						var sumHeight = 0, i = 0;						
						ImageGird.multipleselect.getAll(ImageGird.sourceImages).each(function(){
							sumHeight += $(this).height() + 9;														
							var dragElement = $(this).clone(true);
							container.append( dragElement );
							$(this).data('i', i)
						});
						
						container.css({
							'height': sumHeight,																					
						});
					}else{
						container = $(item).clone(true);
					}
					$(item).show();
					
					return container;
				},
				start: function(event, ui) {					
					var parent = ui.item.parent()		
					if (parent.attr('id') == 'source-images')
					{
						var copy = $('.ui-sortable-placeholder').prev().clone(true);					
						$('.ui-sortable-placeholder').after(copy);
						copy.show();
					}
					ImageGird.receive = true;
				},
				over : function(event, ui){
					if ( $(this).hasClass('showlist-videos') && ImageGird.showlistImages.find('div.video-item').length == 1 && ImageGird.showlistImages.find('div.ui-sortable-placeholder').length ){
						ImageGird.showlistImages.find('div.showlist-drag-drop-video-notice').remove();
					}
					
				},
				out : function(event, ui){	
					
					if ( ImageGird.showlistImages.find('div.video-item').length == 0 && ImageGird.showlistImages.find('div.showlist-drag-drop-video-notice').length == 0){
						ImageGird.showlistImages.html('<div class="showlist-drag-drop-video-notice">Drag and drop images here</div>');
					}
				},				
				update : function(event, ui){					
					if ($(this).attr('id') == ImageGird.showlistImages.attr('id'))
					{	
						ImageGird.activeButtonsAction();
						ImageGird.contentResize();
					}
				},
				stop: function(event, ui){					
					var parent = ui.item.parent();					
				//	ImageGird.saveShowlist();
					ImageGird.showlistImages.find('div.video-no-found').remove();	    
					var elementID = ui.item.attr('id');				
					if ( $('div[id="'+elementID+'"]', ImageGird.sourceImages).length > 1){
						var index = 0;
						$('div[id="'+elementID+'"]', ImageGird.sourceImages).each(function(){
							if (index > 0){
								$(this).remove();
							}
							index++;
						});
					}
					
					if ( $('div[id="'+elementID+'"]', ImageGird.showlistImages).length > 1){
						var index = 0;
						$('div[id="'+elementID+'"]', ImageGird.showlistImages).each(function(){
							if (index > 0){
								$(this).remove();
							}
							index++;
						});
					}
					ImageGird.sourceImages.find('div.image-item-is-selected').each(function(){
						$(this).removeAttr('start');
					});
					ImageGird.showlistImages.find('div.video-item').each(function(){
						$(this).removeAttr('start');
					});
					ImageGird.indexImages();
					ImageGird.multipleselect.init();
					ImageGird.editImageShowlistBtt.removeClass('active');				
					ImageGird.deleteImageShowlistBtt.removeClass('active');	
					if (parent.attr('id') != undefined && parent.attr('id') == 'showlist-videos')
					{
						ImageGird.contents.categories.find('a.jtree-selected').parent().addClass('catselected');
					}
					if ((parent.attr('id') != undefined && parent.attr('id') == 'showlist-videos') && (!ImageGird.receive))
					{
						ImageGird.saveShowlist();
						setTimeout('showLoading({removeall:true})',1000);
					}
					
					ImageGird.receive = false;
				}
			}).disableSelection();			
	 		
			ImageGird.showlistImages.sortable({				
				opacity: 0.6,
				scroll: true,
				dropOnEmpty: false,
				forceHelperSize: true,				
				cancel: 'div.image-item-is-selected, div.video-no-found, div.showlist-drag-drop-video-notice, div.sync',
				scrollSensitivity: 50,
				helper: function(e, item){
					if ( ImageGird.multipleselect.hasSelected( $(item) ) && ImageGird.multipleselect.getTotal( ImageGird.showlistImages ) > 1 ){
						var container = $('<div />', {
							'class' : 'jsn-video-item-multiple-select-container',
							'id'    : 'jsn-video-item-multiple-select-container'
						});
						var sumHeight = 0, i = 0;						
						ImageGird.multipleselect.getAll(ImageGird.showlistImages).each(function(){							
							sumHeight += $(this).height() + 9;														
							var dragElement = $(this).clone(true);
							$(this).hide();
							container.append( dragElement );
							$(this).data('i', i);
							i++;
						});
						
						container.css({
							'height': sumHeight																					
						});
					}else{
						container = $(item).clone(true);
					}
					$(item).show();
					
					return container;
				},
				receive: function(event, ui){		
					showLoading({removeall:false});
					ImageGird.receive = true;										
				},
				
				update : function(event, ui){					
					var elementID = ui.item.attr('id');	
					if(ImageGird.receive){
						var isMultipleVideo = ImageGird.multipleselect.getTotal(ImageGird.sourceImages);
						
						//showLoading({removeall:false});
						$('div[id="'+elementID+'"]', ImageGird.sourceImages)
						.removeClass('video-item')
						.addClass('image-item-is-selected')
						.append('<div class="clr"></div><div class="img-mark-isselected">&nbsp;</div>');
						if (isMultipleVideo)
						{				
							var i = 1;
							ui.item.children('div.move-to-showlist').remove();
							ImageGird.multipleselect.deSelect( $('div[id="'+elementID+'"]', ImageGird.sourceImages));
							var totalVideoMoving = ImageGird.multipleselect.getAll(ImageGird.sourceImages);
							
							var _append = function(obj){
								ui.item.after(obj);
							};
							var totalVideo =  totalVideoMoving.length;							
							ImageGird.queueExecute(totalVideoMoving, 0, function(obj){								
								ImageGird.moveVideoToShowlist(obj, _append, totalVideo, i);
								_append = function(obj){
									ui.item.after(obj);
								};								
								if (i == totalVideo)
								{
									i = 0;
								}								
								i++;								
							});
							if (totalVideoMoving.length == 0)
							{
								ImageGird.saveOneImage();						
							}							
						}else{
							ImageGird.saveOneImage();
						}
						
						
					}else{			
						var isMultipleVideo = ImageGird.multipleselect.hasSelected(ui.item);
						showLoading({removeall:false});
						
						ui.item.children('div.move-to-showlist').remove();	
						ImageGird.showlistImages.children('div.clr').remove();
						var totalVideoMoving = ImageGird.multipleselect.getAll(ImageGird.showlistImages);						
						$('#showlist-videos .image-item-multiple-select').removeClass('image-item-multiple-select');
						var _append = function(obj){
							ui.item.after(obj);
						};					
						
						totalVideoMoving.each( function (){	
							if(ui.item.attr('id') != $(this).attr('id')){
								$(this).removeAttr('style');								
								_append($(this));
							}																
						});
						
						ImageGird.saveShowlist();							
						setTimeout('showLoading({removeall:true})',1000);					
					}
					
					ImageGird.indexImages();
					ImageGird.contentResize();
					//ImageGird.removeselectedAlbum();
					
				},
				stop: function (){			
					$('.image-item-multiple-select',ImageGird.showlistImages).removeAttr('style');
					ImageGird.receive = false;
				},
				over: function (){
					$('.showlist-drag-drop-video-notice').hide();
				},
				out: function (){
					if(!$('.video-item'),ImageGird.showlistImages){
						$('.showlist-drag-drop-video-notice').show();
					}
				}
					
			}).disableSelection();
		};


			

		/**
		 * Index video items
		 */
		ImageGird.indexImages     = function(){
//			//Index source
//			var totalVideos = $('.image-item-is-selected', ImageGird.sourceImages).length + $('.video-item', ImageGird.sourceImages).length;
//			i = 1;		
//			ImageGird.sourceImages.children('div.video-item').each(function(){
//				
//				if ( $(this).hasClass('image-item-is-selected') || $(this).hasClass('video-item') ){
//					$(this).children('div.video-index').html( i++ + '/' + totalVideos );
//					if (ImageGird.options.selectMode != 'sync'){
//						var moveVideoToShowlist = $('<button />',{
//							'class' : "move-to-showlist"
//						}).html('&nbsp;');
//						$(this).children('div.video-index').append(moveVideoToShowlist);
//						moveVideoToShowlist.unbind("click").click(function(){	
//							showLoading({removeall:false});
//							ImageGird.contents.categories.find('a.jtree-selected').parent().addClass('catselected');
//							ImageGird.moveVideoToShowlist( $(this).parents('div.video-item') );
//						});
//					}
//				}
//			});
			
			var totalVideos = $('.video-item', ImageGird.showlistImages).length;			
							  i = 1;
			ImageGird.showlistImages.children().each(function(){
				if ( $(this).hasClass('video-item') ){
					$(this).children('div.video-index').html( i++ + '/' + totalVideos );
					if (ImageGird.options.selectMode != 'sync'){
						$(this).children('div.video-index').append('<button class="delete-video"></button><button class="edit-video"></button>');
						$(this).children('div.video-index').children('button.delete-video').click(function(){
							var video = $(this).parents('div.video-item');							
							var jsnConfirm = $.JSNUIConfirm('Confirmation', '<div style="width:100%; text-align:center; font-size:16px; font-weight:500; margin-top:50px; ">Are you sure you want to remove selected images?</div>',
						    {
								width:  500,
								height: 180,
								modal:  true,
								buttons: {
									'Ok': function(){
										//Delete images confirmation
										ImageGird.deleteImage(video);
										//Close dialog
										jsnConfirm.dialog('close');
										showLoading({removeall:false});
									},
									'Cancel': function(){
										jsnConfirm.dialog('close');
									}
								}
							});
						});
						$(this).children('div.video-index').children('button.edit-video').click(function(){
							ImageGird.editImage($(this).parents('div.video-item'));
						});
					}
				}
			});
			
			if ( ImageGird.showlistImages.find('div.video-item').length == 0 ){
				if (ImageGird.showlistImages.find('div.showlist-drag-drop-video-notice').length == 0 ){
					// remove duplicate noice in sync mode
					//ImageGird.showlistImages.append('<div class="showlist-drag-drop-video-notice">Drag and drop images here</div>');
				}
			}else{
				ImageGird.showlistImages.find('div.showlist-drag-drop-video-notice').remove();
			}
			ImageGird.sourceImages.children('div.video-item').each(function(){
				$(this).dblclick(function(){
					// get current image click
					$(this).find('div.video-thumbnail img').each(function(){
						imgsrc  = $(this).attr('src');
					});
					//show popup with image detail					
					$("#dialogboxdetailimage").html('<div class="img-box" style="background-color:#F4F4F4;width:440px;height:400px;display:table-cell; vertical-align:middle;"><img style="max-width: 400px; max-height: 360px;margin: 5px;" src="'+imgsrc+'"></div>')
					.dialog({
								width: 460, 
								height: 620, 
								modal: true, 
								title: '<span style="font-size: 15px; font-weight:bold;">View Image</span>',
								buttons: [
								    {
								        text: "Close",
								        click: function() { $(this).dialog("close"); }
								    }
								] 
							});	
				});
			});	

			//show popup edit image when double click to image

			ImageGird.showlistImages.children('div.video-item').each(function(){
				$(this).unbind("dblclick").dblclick(function(){	
					if ( ImageGird.options.selectMode != 'sync'){
						ImageGird.editImage($(this));	
					}
				});					
			});

		};
		/**
		 * Queue execute 
		 */
		ImageGird.queueExecute = function(queueArr, n, _callFunc){			
			if ( n == queueArr.length ){
				return;
			}else{
				$(queueArr[n]).unbind("ImageGird.execute.completed").bind("ImageGird.execute.completed", function(){
					ImageGird.queueExecute(queueArr, n+1, _callFunc);
				});
				if ( $.isFunction( _callFunc ) ){
					_callFunc( $(queueArr[n]) );
				}
			}
		};
		/**
		 * Move an element 
		 */
		ImageGird.moveVideoToShowlist = function(obj, _callFunc, total, i){			
			$('.showlist-drag-drop-video-notice').remove();
			//$('#showlist-videos .image-item-multiple-select').removeClass('image-item-multiple-select');
			//Deselect item
			ImageGird.multipleselect.deSelect(obj);
			//Disable move button
			ImageGird.activeButtonsAction();
			//Copy an video-item and append to showlist			
			var copy = obj.clone(true);			
			copy.removeAttr('style');
			ImageGird.showlistImages.children('div.clr').remove();
			ImageGird.showlistImages.children('div.clr').remove();
			if ( $.isFunction(_callFunc) ){
				_callFunc(copy);
			}else{
				copy.appendTo(ImageGird.showlistImages);
			}			
			ImageGird.showlistImages.append('<div class="clr"></div>');		
			obj.removeClass('video-item').addClass('image-item-is-selected').append('<div class="clr"></div><div class="img-mark-isselected">&nbsp;</div>');
			
			//Save showlist
			if ( $.isFunction(_callFunc) ){				
				ImageGird.saveShowlist();
			}
			else
			{				
				ImageGird.saveShowlistMovedAll(obj, total, i);
			}
			
			if(i <= total){
				//Re-index
				ImageGird.indexImages();
				//Resize layout
				ImageGird.contentResize();
				//Scroll to bottom
				if ( $.isFunction(_callFunc) ){
					ImageGird.contents.showlistimages.animate({
						scrollTop : ImageGird.contents.showlistimages.prop('scrollHeight')
					}, 1500, function(){
						if (i == total)
						{	
							if (ImageGird.options.sourceName == 'folder')
								ImageGird.checkThumb();
							else
								showLoading({removeall:true});
						}else{
							obj.trigger("ImageGird.execute.completed");
						}
						
					});
				}else{
					//if (ImageGird.options.sourceName != 'folder')
					//{
					//	obj.trigger("ImageGird.execute.completed");
						//showLoading({removeall:true});
					//}
				}
			}
		};
		
		
		//moving images is show list
		ImageGird.moveImageInShowlist = function(obj, _callFunc, total, i){
			//Deselect item
			ImageGird.multipleselect.deSelect(obj);
			//Disable move button
			ImageGird.activeButtonsAction();
			//Copy an video-item and append to showlist			
			var copy = obj.clone(true);
			
			copy.removeAttr('style');
			ImageGird.showlistImages.children('div.clr').remove();
			if ( $.isFunction(_callFunc) ){
				_callFunc(copy);
			}else{
				copy.appendTo(ImageGird.showlistImages);
			}			
			ImageGird.showlistImages.append('<div class="clr"></div>');	
			
			
			//Save showlist
			if ( $.isFunction(_callFunc) ){
				//ImageGird.saveShowlist();
			}
			else
			{
//				if (ImageGird.options.sourceName == 'folder')
//					//ImageGird.saveShowlistMovedAll(obj, total, i);
//				else
//					//ImageGird.saveShowlist();	
			}	
			
			if(i <= total){
				//Re-index
				ImageGird.indexImages();
				//Resize layout
				ImageGird.contentResize();
				//Scroll to bottom
				if ( $.isFunction(_callFunc) ){
					ImageGird.contents.showlistimages.animate({
						scrollTop : ImageGird.contents.showlistimages.prop('scrollHeight')
					}, 1500, function(){						
						obj.trigger("ImageGird.execute.completed");
					});
				}else{
					//if (ImageGird.options.sourceName != 'folder')
					//{
					//	obj.trigger("ImageGird.execute.completed");
						//showLoading({removeall:true});
					//}
				}
			}
		};
		/**
		 * Convert array to JSON data
		 */
		ImageGird.toJSON          = function( arr ){
			var json = new Array();
			var i = 0;
			for(k in arr){
				if (typeof arr[k] != 'function'){
					if (typeof arr[k] == 'Array' || typeof arr[k] == 'object'){
						json[i] = '"'+k+'":'+ImageGird.toJSON(arr[k]);
					}else{
						json[i] = '"'+k+'":"'+arr[k]+'"'; 
					}
					i++;
				}
			}
			return '{'+json.join(',')+'}';
		};
		/**
		 * Check session ajax-responce 
		 */
		ImageGird.checkResponse   = function(res){
			$('input[type="hidden"]', res).each(function(i){
				if ($(this).attr('name') == 'task' && $(this).val() == 'login'){				    
					window.location.reload(true);
				}
	        });
		};
		
		ImageGird.editImage = function(vEl){
			var params ='&showListID='+ImageGird.options.showListID+'&imageID=';
			if(vEl.length>1){
				vEl.each(function(){
					params += $(this).attr('id')+"|";				
				});
			}
			else
				params += vEl.attr('id');
	 		url = 'index.php?option=com_imageshow&controller=image&task=editimage&sourceName='+ImageGird.options.sourceName+'&sourceType='+ImageGird.options.sourceType+'&tmpl=component'+params;		
			showLoading({removeall:false});
			$("#dialogbox").load(url, function(response, status, xhr) {
				if(status == "success"){
					showLoading({removeall:true});
					$("#dialogbox").dialog({
						width: 600, 
						height: 410,
						modal: true,
						title: '<span style="font-size: 15px; font-weight:bold;">Edit Image Details</span>',
						close: function (){
							$('#image_detail div').html('');
						}
					});
				}
			})
		}	
		/**
		 * Delete video
		 */
		ImageGird.deleteImage    = function(vEl){		

			$.post(baseUrl + 'administrator/index.php?option=com_imageshow&controller=images&task=deleteimageshowlist',{
				showListID : ImageGird.options.showListID,
				sourceName : ImageGird.options.sourceName,
				sourceType : ImageGird.options.sourceType,
				imageID    : vEl.attr('id')
			}).success( function( responce ){
				ImageGird.checkResponse(responce);
				var vID = vEl.attr('id');				
				vEl.trigger("ImageGird.execute.completed");
				vEl.fadeOut(300, function(){										
					$(this).remove();
					$('div[id="'+vID+'"]', ImageGird.sourceImages).each(function(){
						$(this).removeClass('image-item-is-selected').addClass('video-item').children('div.img-mark-isselected').remove();
					});		
					ImageGird.indexImages();		
					ImageGird.contentResize();
					ImageGird.multipleselect.init();					
					//ImageGird.removeselectedAlbum();	
				});				
				showLoading({removeall:true});
			});
		};
		
		/**
		 * Check Thumb
		 */
		ImageGird.checkThumb = function(){		

			$.post(baseUrl + 'administrator/index.php?option=com_imageshow&controller=images&task=checkthumb',{
				showListID : ImageGird.options.showListID,
				sourceName : ImageGird.options.sourceName,
				sourceType : ImageGird.options.sourceType
			}).success( function(responce){
				//JSON.parse(responce).each(function(obj){
					//ImageGird.createThumb(obj);
				//});	
				var i = 1;
				var total = JSON.parse(responce).length;
				if(!total){
					showLoading({removeall:true});
				}
				ImageGird.queueExecute(JSON.parse(responce), 0, function(obj){
					ImageGird.createThumb(obj, total, i);
					if (i == total)
					{
						i = 0;
					}						
					i++;
				});				
			});
		};
		/**
		 * Create Thumb
		 */
		ImageGird.createThumb = function(obj, total, i){			 		
			var cloneobj={
					image_big:obj[0].image_big,
					image_extid:obj[0].image_extid,
					album_extid:obj[0].album_extid
			}
			$.post(baseUrl + 'administrator/index.php?option=com_imageshow&controller=images&task=createthumb',{
				showListID : ImageGird.options.showListID,
				sourceName : ImageGird.options.sourceName,
				sourceType : ImageGird.options.sourceType,
				image	   : cloneobj
			}).success(function(responce){	
				obj.trigger("ImageGird.execute.completed");
				if (i == total)
				{
					showLoading({removeall:true});
				}
			});
		};
		/**
		 *
		 * Save your drag and drop modulesList
		 *		 
		 * @return: Save to the database
		 * if (not success){
		 *	undo drag
		 *}
		 */		
		ImageGird.saveOneImage    = function(){								
			var images = new Array(), i = 0;	
			ImageGird.showlistImages.find('div.video-item').each(function(){
				images[i] = new Array();
				images[i]['source_type']   = ImageGird.options.sourceType;
				images[i]['source_name']   = ImageGird.options.sourceName;
				images[i]['showlist_id']   = ImageGird.options.showListID;
				images[i]['imgid']   = $(this).attr('id');
				images[i]['order'] = $(this).index();
				images[i]['albumid'] = $(this).find('input.img_extid').val();
				images[i]['img_detail'] = JSON.parse($(this).find('input.img_detail').val());		
				i++;
			});	
			
			$.post(baseUrl + 'administrator/index.php?option=com_imageshow&controller=images&task=saveshowlist',{
				showListID : ImageGird.options.showListID,
				sourceName : ImageGird.options.sourceName,
				sourceType : ImageGird.options.sourceType,
				syncMode   : ImageGird.options.selectMode,
				images     : ImageGird.toJSON(images)
			}).success( function( responce ){
				if (ImageGird.options.sourceName == 'folder')
					ImageGird.checkThumb();
				else
					showLoading({removeall:true});
				
				ImageGird.checkResponse(responce);
				ImageGird.multipleselect.deSelectAll(ImageGird.showlistImages);	
				ImageGird.moveImageToShowlistBtt.removeClass('active').unbind("click");
			});
		};	
		
		//reload source images
		ImageGird.reloadOneImageSource = function (cateName){
			showLoading({removeall:false});
			$.post( baseUrl+'administrator/index.php?option=com_imageshow&controller=images&task=loadSourceImages', {
				showListID : ImageGird.options.showListID,
				sourceType : ImageGird.options.sourceType,
				sourceName : ImageGird.options.sourceName,
				selectMode : ImageGird.options.selectMode,
				cateName   : cateName
			}).success( function(res){													
				ImageGird.sourceImages.html(res);
				//Add image thumbnail
				ImageGird.imageLoading(ImageGird.sourceImages.find('img[alt="video thumbnail"]'));
				/**
				 * Init events
				 */							 
				ImageGird.initEvents();
				ImageGird.sourceImages.find('div.video-thumbnail').addClass('loaded');
				//setTimeout(function(){						
					showLoading({removeall:true});
				//}, 500);
				// add class loaded to each image thumnail
				ImageGird.sourceImages.find('div.video-item').each(function(){
					$(this).find('div.video-thumbnail').addClass('loaded');
				});
			})
		};		
		/**
		 *
		 * Save your drag and drop modulesList
		 *		 
		 * @return: Save to the database
		 * if (not success){
		 *	undo drag
		 *}
		 */
		ImageGird.saveShowlist    = function(){								
				var images = new Array(), i = 0;	
				ImageGird.showlistImages.find('div.video-item').each(function(){
					images[i] = new Array();
					images[i]['source_type']   = ImageGird.options.sourceType;
					images[i]['source_name']   = ImageGird.options.sourceName;
					images[i]['showlist_id']   = ImageGird.options.showListID;
					images[i]['imgid']   = $(this).attr('id');
					images[i]['order'] = $(this).index();
					images[i]['albumid'] = $(this).find('input.img_extid').val();
					images[i]['img_detail'] = JSON.parse($(this).find('input.img_detail').val());		
					i++;
				});
				
			$.post(baseUrl + 'administrator/index.php?option=com_imageshow&controller=images&task=saveshowlist',{
				showListID : ImageGird.options.showListID,
				sourceName : ImageGird.options.sourceName,
				sourceType : ImageGird.options.sourceType,
				syncMode   : ImageGird.options.selectMode,
				images     : ImageGird.toJSON(images)
			}).success( function( responce ){				
				ImageGird.moveImageToShowlistBtt.removeClass('active').unbind('click');
				ImageGird.checkResponse(responce);
				ImageGird.multipleselect.deSelectAll(ImageGird.showlistImages);
				ImageGird.multipleselect.deSelectAll(ImageGird.sourceImages);				
			});
		};

		ImageGird.saveShowlistMovedAll    = function(obj, total, j){								
			var images = new Array(), i = 0;	
			ImageGird.showlistImages.find('div.video-item').each(function(){
				images[i] = new Array();
				images[i]['source_type']   = ImageGird.options.sourceType;
				images[i]['source_name']   = ImageGird.options.sourceName;
				images[i]['showlist_id']   = ImageGird.options.showListID;
				images[i]['imgid']   = $(this).attr('id');
				images[i]['order'] = $(this).index();
				images[i]['albumid'] = $(this).find('input.img_extid').val();
				images[i]['img_detail'] = JSON.parse($(this).find('input.img_detail').val());		
				i++;
			});
			
		$.post(baseUrl + 'administrator/index.php?option=com_imageshow&controller=images&task=saveshowlist',{
			showListID : ImageGird.options.showListID,
			sourceName : ImageGird.options.sourceName,
			sourceType : ImageGird.options.sourceType,
			syncMode   : ImageGird.options.selectMode,
			images     : ImageGird.toJSON(images)
		}).success( function( responce ){
			obj.trigger("ImageGird.execute.completed");
			if (j == total)
			{
				if (ImageGird.options.sourceName == 'folder')
					ImageGird.checkThumb();
				else
					showLoading({removeall:true});
			}
			ImageGird.checkResponse(responce);
			ImageGird.multipleselect.deSelectAll(ImageGird.showlistImages);
			ImageGird.moveImageToShowlistBtt.removeClass('active').unbind("click");
		});
	};
		
		ImageGird.selectlinkBtt.click(function(){					
			$('#dialogbox2').bPopup({
		            closeClass:'close2',
		            content:'iframe',
		            follow:[false, false],
		            loadUrl:baseUrl+'administrator/index.php?option=com_imageshow&controller=image&view=image&task=showlinkpopup&layout=showlinkpopup&tmpl=component'						           
	        	});			
		});
		
		ImageGird.removeSync = function(){

				$.post( baseUrl+'administrator/index.php?option=com_imageshow&controller=images&task=removeallSync', {
					showListID : ImageGird.options.showListID,
					sourceType : ImageGird.options.sourceType,
					sourceName : ImageGird.options.sourceName,
					syncMode   : ImageGird.options.selectMode					
				}).success(function(res){
					
				});
			}

		
		// remove prototies selected of a album if that album haven't any image in showlist after delete. 
		ImageGird.removeselectedAlbum = function(){
			var id_showlistarr = new Array();
			ImageGird.showlistImages.find('div.image_extid').each(function(e){
				var id = $(this).attr('id').replace('cat_','');												
				id_showlistarr[e] = id;												
			});				
			ImageGird.contents.categories.find('li.catselected').each(function(){												
				if($.inArray($(this).attr('id'),id_showlistarr ) > -1){
					// doesn't work anything :).
				}else{
					// remove cat don't have any images on showlist images
					$(this).removeClass('catselected');	
				}
			});
		}
		
		//reload source images
		ImageGird.reloadImageSource = function (){
			$.post( baseUrl+'administrator/index.php?option=com_imageshow&controller=images&task=loadSourceImages', {
				showListID : ImageGird.options.showListID,
				sourceType : ImageGird.options.sourceType,
				sourceName : ImageGird.options.sourceName,
				selectMode : ImageGird.options.selectMode,
				cateName   : $('#jsn-jtree-categories .jtree-selected').parents('li').attr('id')
			}).success( function(res){													
				ImageGird.sourceImages.html(res);
				//Add image thumbnail
				ImageGird.imageLoading(ImageGird.sourceImages.find('img[alt="video thumbnail"]'));
				/**
				 * Init events
				 */							 
				ImageGird.initEvents();
				ImageGird.sourceImages.find('div.video-thumbnail').addClass('loaded');
				setTimeout(function(){						

					showLoading({removeall:true});
				}, 500);
				// add class loaded to each image thumnail
				ImageGird.sourceImages.find('div.video-item').each(function(){
					$(this).find('div.video-thumbnail').addClass('loaded');
				});
			})
		}
				
		return ImageGird;
	};
	
	showLoading     = function(ops){
		//Option and overwrite option. jQuery extend 
		var _ops = $.extend
		(
			{
				left           : 0,
				top            : 0,
				width          : $(document).width(),
				height         : $(document).height(),
				zIndex         : $.topZIndex(),
				showImgLoading : true,
				removeall      : false
			}, 
			ops
		);		
		if ( _ops.removeall ){
			$('body').find('div.ui-widget-overlay').remove();
			return;
		}
		
		var widgetOverlay = $('body').children('div.ui-widget-overlay');
		if ( widgetOverlay.length > 0 ){
			return;
		} 
		if ( widgetOverlay.length == 0 ){
		   	var widgetOverlay = $('<div />', {
				'class' : 'ui-widget-overlay'
           	}).css({
           		'top'    : _ops.top,
           		'left'   : _ops.left,
           		'width'  : _ops.width,
           		'height' : _ops.height,
           		'z-index': _ops.zIndex	
           	}).appendTo($('body'));
			//Add image loading
			if ( _ops.showImgLoading ){

				if ( widgetOverlay.find('.img-box-loading').length ){
					widgetOverlay.find('.img-box-loading').remove();
				}

				var imgBoxLoading = $('<div />', {
					                   'class' : 'img-box-loading'
				                    })
				                    .appendTo(widgetOverlay)
	                                .css({
	                                	'position': 'relative',
	                                	'top'     : $(window).scrollTop() + $(window).height()/2-12+'px',
	                                	'left'    : $(window).scrollLeft() + $(window).width()/2-12+'px'
	                                });

				$('<img />', {
					'src' : baseUrl+'administrator/components/com_imageshow/assets/images/ajax-loader.gif'
				})
				.appendTo(imgBoxLoading)
	            .css({
            		'position': 'relative',
            		'left'    : '12px',
            		'top'     : '12px'
	            });
			}
		}
	}
	
	/**
	 * Manager 
	 */
	var Instances = new Array();
	$.JSNISImageGirdGetInstaces = function(options){
		if (Instances['JSNISImageGird'] != undefined ){
			Instances['JSNISImageGird'].setOption(options);
		}else{
			Instances['JSNISImageGird'] = new $.JSNISImageGird(options);
			var obj = Instances['JSNISImageGird'];
		}
		return Instances['JSNISImageGird'];
	};
})(jQuery);
