/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_imageshow.js 13076 2012-06-06 09:26:10Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
var JSNISImageShow = {
	ChooseProfileFolder:function(){
		if($('add_image_manual_auto').checked == true){			
			$('user_select_folder').disabled = false;
		}
		if($('add_image_manual').checked == true){
			$('user_select_folder').disabled = true;
		}
	},
	ShowListCheckAlternativeContent:function(){
			var value = $('alternative_status').options[$('alternative_status').selectedIndex].value;
			if(value == 2){
				$('wrap-btt-article').setStyle('display', '');	
			}else{
				$('wrap-btt-article').setStyle('display', 'none');	
			}
			if(value == 1){
				$('wrap-btt-module').setStyle('display', '');	
			}else{
				$('wrap-btt-module').setStyle('display', 'none');	
			}
			
			if(value == 3){
				$('wrap-btt-image').setStyle('display', '');	
			}else{
				$('wrap-btt-image').setStyle('display', 'none');	
			}
	},
	ShowListCheckSeoContent:function(){
		var value = $('seo_status').options[$('seo_status').selectedIndex].value;
		if(value == 1){
			$('wrap-seo-article').setStyle('display', '');	
		}else{
			$('wrap-seo-article').setStyle('display', 'none');	
		}
		if(value == 2){
			$('wrap-seo-module').setStyle('display', '');	
		}else{
			$('wrap-seo-module').setStyle('display', 'none');	
		}
	},
	ShowListCheckAuthorizationContent:function(){
			var value = $('authorization_status').options[$('authorization_status').selectedIndex].value;
			if(value == 1){
				$('wrap-aut-article').setStyle('display', '');	
			}else{
				$('wrap-aut-article').setStyle('display', 'none');	
			}
	},

	Maintenance:function(){
		try
		{
			$('linkconfigs').addEvent('click', function() { 
				window.top.location='index.php?option=com_imageshow&controller=maintenance&type=configs';
			});
			$('linkmsgs').addEvent('click', function() { 
				window.top.location='index.php?option=com_imageshow&controller=maintenance&type=msgs';
			});
			$('linklangs').addEvent('click', function() { 
				window.top.location='index.php?option=com_imageshow&controller=maintenance&type=inslangs';
			});
			$('linkdata').addEvent('click', function() { 
				window.top.location='index.php?option=com_imageshow&controller=maintenance&type=data';
			});
			$('linkprofile').addEvent('click', function() { 
				window.top.location='index.php?option=com_imageshow&controller=maintenance&type=profiles';
			});
			$('linkthemes').addEvent('click', function() { 
				window.top.location='index.php?option=com_imageshow&controller=maintenance&type=themes';
			});
		}
		catch (e)
		{
			
		}
	},
	SlideMessage: function(){
		$$( '.jsn-more-msg-info-wrapper' ).each(function(item){
			var thisSlider = new Fx.Slide( item.getElement( '.jsn-more-msg-info' ), { duration: 300 } );
			thisSlider.hide();
			if(item.getElement( '.jsn-link-readmore-messages' ) != null){
				item.getElement( '.jsn-link-readmore-messages' ).addEvent( 'click', function(){
					if(item.getElement( '.jsn-more-msg-info' ).innerHTML != ''){
						thisSlider.toggle(); 
					}
				});
			}
			thisSlider.addEvent('onStart', function(){
				var a = $E('a', item);
				if(a){
					var newHTML = a.innerHTML == '[+]' ? '[-]' : '[+]';
					a.setHTML(newHTML);
				}
			});
		});		
	},
	SetStatusMessage:function(token, msg_id){
		var url  = 'index.php?option=com_imageshow&controller=maintenance&task=setstatusmsg&msg_id='+msg_id+'&'+token+'=1';	
		var ajax = new Request({
			url: url,
			method: 'get',
			onComplete: function(response) {
			}
		});
		ajax.send();
	},
	setDisplayMessage:function(){
		$$( '.jsn-link-delete-messages' ).each(function(item, i){
			item.addEvent( 'click', function(){
				var thisSlider = new Fx.Slide( $$( '.jsn-more-msg-info-wrapper' )[i], { duration: 300 } );
				thisSlider.toggle();
			});
		});
	},
	ReplaceVals: function (n) {
		if (n == "a") { n = 10; }
		if (n == "b") { n = 11; }
		if (n == "c") { n = 12; }
		if (n == "d") { n = 13; }
		if (n == "e") { n = 14; }
		if (n == "f") { n = 15; }
		
		return n;
	},
	hextorgb: function (strPara) {
		var casechanged=strPara.toLowerCase(); 
		var stringArray=casechanged.split("");
		if(stringArray[0] == '#'){
			for(var i = 1; i < stringArray.length; i++){			
				if(i == 1 ){
					var n1 = JSNISImageShow.ReplaceVals(stringArray[i]);				
				}else if(i == 2){
					var n2 = JSNISImageShow.ReplaceVals(stringArray[i]);
				}else if(i == 3){
					var n3 = JSNISImageShow.ReplaceVals(stringArray[i]);
				}else if(i == 4){
					var n4 = JSNISImageShow.ReplaceVals(stringArray[i]);
				}else if(i == 5){
					var n5 = JSNISImageShow.ReplaceVals(stringArray[i]);
				}else if(i == 6){
					var n6 = JSNISImageShow.ReplaceVals(stringArray[i]);
				}			
			}
			
			var returnval = ((16 * n1) + (1 * n2));
			var returnval1 = 16 * n3 + n4;
			var returnval2 = 16 * n5 + n6;
			return new Array(((16 * n1) + (1 * n2)), ((16 * n3) + (1 * n4)), ((16 * n5) + (1 * n6)));
		}
		return new Array(255, 0, 0);
	},
	
	switchShowcaseTheme: function(me)
	{
		$('adminForm').redirectLinkTheme.value = me.href;
		$('adminForm').task.value = 'switchTheme';
		$('adminForm').submit();
	},
	
	resizeFlex: function(direction, userID)
	{	
		var heightCookieName 		= 'jsn-height-flex-' + userID;
		var heightLevelCookieName  	= 'jsn-height-flex-level-' + userID;
		var heightCookieStatus 		= JSNISUtils.getCookie(heightCookieName);
		var levelCookieStatus 		= JSNISUtils.getCookie(heightLevelCookieName);
		var wrapperFlex 			= $('jsn-showlist-flex');
		var height 					= wrapperFlex.getSize().y;
		var count;
		
		if (height <= 50) return false;
		
		if (heightCookieStatus == null || heightCookieStatus == '')
		{
			JSNISUtils.setCookie(heightCookieName, height, 15);		
		}

		if (levelCookieStatus == null || levelCookieStatus == '')
		{
			JSNISUtils.setCookie(heightLevelCookieName, 1, 15);
		}

		height = JSNISUtils.getCookie(heightCookieName);
		count  = JSNISUtils.getCookie(heightLevelCookieName);
		height = height.toInt();
		count  = count.toInt();
		
		var newHeight = height;
		
		if (direction == 'increase' && count <= 6)
		{
			newHeight = height + 50;	
			count++;
		}
		
		if (direction == 'reduce' && count > 1)
		{
			newHeight = height - 50;
			count--;
		}
		
		var increaseButton = $$('.jsn-panel-increase');
		var decreaseButton = $$('.jsn-panel-decrease');
		
		if (count >= 7)
		{
			increaseButton.addClass('disabled');
		}
		else if (count <= 1)
		{
			decreaseButton.addClass('disabled');
		}
		else
		{
			increaseButton.removeClass('disabled');
			decreaseButton.removeClass('disabled');
		}

		JSNISUtils.setCookie(heightCookieName, newHeight, 15);
		JSNISUtils.setCookie(heightLevelCookieName, count, 15);
		
		var effect = new Fx.Tween(wrapperFlex, {
			property : 'height',
			duration: 'short',
			onComplete: function()
			{
				if (direction == 'increase' && count <= 7)
				{
					var scroll = new Fx.Scroll(window, {duration: 300});
					scroll.toBottom();
				}
			}
		});
		
		effect.start(height, newHeight);
	},
	
	setCookieSettingFlex: function(name, value)
	{
		if (userID != '' || userID != undefined)
		{
			JSNISUtils.setCookie('jsn-flex-cookie-' + name + '-' + userID, value, 15);
		}
	},
	
	loadCookieSettingFlex: function()
	{
		var heightLevelFlex = JSNISUtils.getCookie('jsn-height-flex-level-' + userID);
		
		if (heightLevelFlex >= 7)
		{
			$$('.jsn-panel-increase').addClass('disabled');
		}
		
		if (heightLevelFlex <= 1 || heightLevelFlex == null || heightLevelFlex == '')
		{
			$$('.jsn-panel-decrease').addClass('disabled');
		}
	},
	
	jsnMenuSaveToLeave: function(action, link)
	{
		if (action != 'save')
		{
			window.top.location = link;
		}
		else
		{
			if ($('jsn-menu-link-redirect'))
			{
				$('jsn-menu-link-redirect').destroy();
			};
			var linkElement = new Element('input', {'type' : 'hidden', 'id':'jsn-menu-link-redirect', 'name':'jsn-menu-link-redirect', 'value' : link});
			linkElement.injectInside(document.adminForm);
			Joomla.submitbutton('save');
		}
	},
	
	jsnMenuEffect: function()
	{
		var jsnMenu = $$('#jsn-menu li.menu-name')[0];
		var subMenu = $$('.jsn-submenu')[0];
		
		function hideSubMenu()
		{
			subMenu.style.left = 'auto';
			subMenu.style.right = '0';
			
			setTimeout(function(){
				subMenu.style.left = '';
				subMenu.style.right = '';
			}, 500);
		}
		
		jsnMenu.addEvent('mouseleave', function(e)
		{
			var event = new Event(e);
			event.stop();
			hideSubMenu();
		});
		
		subMenu.addEvent('mouseleave', function(e)
		{
			var event = new Event(e);
			event.stop();
			hideSubMenu();
		});
	},

	flexShowlistLoadStatus: false,
	
	flexShowlistLoadCallBack: function()
	{
		JSNISImageShow.flexShowlistLoadStatus = true;
		JSNISImageShow.showlistSaveButtonsStatus('');
	},
	
	showlistSaveButtonsStatus: function(status)
	{
		$('jsn-showlist-toolbar-css').innerHTML = ''; // remove style css
	},
	
	simpleSlide: function(clickID, slideID, arrowID, titleID, arrowAddClass, titleAddClass)
	{
		$(clickID).addEvent('click', function(el)
		{
			var slide 		= $(slideID);
			var slideParent = slide.getParent();
			
			if (slideParent.tagName.toLowerCase() == 'div' && slide.getStyle('margin') == '0px')
			{
				var sizeSlideParent = slideParent.getSize();
				slideParent.style.height = sizeSlideParent.y + 'px';
			}
			
			$(arrowID).toggleClass(arrowAddClass);
			
			var mySlide = new Fx.Slide(slide);
			
			mySlide.toggle().chain
			( 
				function()
				{
					if (slide.getStyle('margin') == '0px'){
						$(slideID).getParent().style.height = 'auto';
					}
				}
			);

			$(titleID).toggleClass(titleAddClass);
		});
	},
	
	setCookieHeadingTitleStatus: function(name)
	{
		var headingStatus  = JSNISUtils.getCookie(name);
		
		if (headingStatus == null || headingStatus == '')
		{
			JSNISUtils.setCookie(name, 'close', 15);
		}
		else
		{
			if (headingStatus == 'close')
			{
				JSNISUtils.setCookie(name, 'open', 15);
			}
			
			if (headingStatus == 'open')
			{
				JSNISUtils.setCookie(name, 'close', 15);
			}
		}
	},
	
	checkEditProfile: function(url, params)
	{
		if ($('submit-new-profile-form')) {
			$('submit-new-profile-form').disabled = true;
			$('submit-new-profile-form').addClass('button-disabled');
		}
		JSNISImageShow.toggleLoadingIcon('jsn-create-source', true);		
		var ajax = new Request({
			url: url,
			method: 'get',
			onComplete: function(stringJSON)
			{
				var data = JSON.decode(stringJSON);

				if (data.success == true)
				{
					alert(data.msg);	
					JSNISImageShow.toggleLoadingIcon('jsn-create-source', false);	
					if ($('submit-new-profile-form')) {
						$('submit-new-profile-form').disabled = false;
						$('submit-new-profile-form').removeClass('button-disabled');
					}
					
					return;
				}		
				JSNISImageShow.validateProfile(params.validateURL);
			}
		});
		ajax.send();
	},
	
	validateProfile: function (url)
	{
		var ajax = new Request({
			url: url,
			method: 'get',
			onComplete: function(stringJSON)
			{
				var data = JSON.decode(stringJSON);
				if (data.success == false) {
					alert(data.msg);
					JSNISImageShow.toggleLoadingIcon('jsn-create-source', false);
					if ($('submit-new-profile-form')) {
						$('submit-new-profile-form').disabled = false;
						$('submit-new-profile-form').removeClass('button-disabled');
					}
					
					return;
				}

				JSNISImageShow.submitForm();// override in view
			}
		});
		ajax.send();
	},
	
	deleteObsoleteThumbnails:function(token){
		var url  = 'index.php?option=com_imageshow&controller=maintenance&task=deleteobsoletethumbnails&'+token+'=1';	
		var smallLoader 		= $('jsn-creating-thumbnail');
		var smallSuccessful 	= $('jsn-creat-thumbnail-successful');
		var smallUnsuccessful 	= $('jsn-creat-thumbnail-unsuccessful');
		smallSuccessful.removeClass ('disable-icon-check');
		var button				= $('jsn-button-delete-obsolete-thumnail');
		smallLoader.setStyle('display', 'inline-block');	
		smallSuccessful.setStyle('display', 'none');	
		smallUnsuccessful.setStyle('display', 'none');	

		button.disabled = true;
		button.addClass('button-disabled');
		var ajax = new Request({
			url: url,
			method: 'get',
			onComplete: function(response) {			
				var data = JSON.decode(response);
				if(data.existed_folder)
				{
					smallSuccessful.setStyle('display', 'inline-block');
					setTimeout("$('jsn-creat-thumbnail-successful').addClass('disable-icon-check')", 3000);
				}
				else
				{
					alert(data.message);
					smallUnsuccessful.setStyle('display', 'inline-block');
					setTimeout("$('jsn-creat-thumbnail-unsuccessful').setStyle('display', 'none')", 3000);
				}
				button.removeClass('button-disabled');
				button.disabled = false;
				smallLoader.setStyle('display', 'none');				
			}
		});
		ajax.send();
	},
	
	initShowlist: function(){ 
		// will be define in view 
	},
	
	getScriptCheckThumb: function(showlistID)
	{
		var ajax = new Request({
			url: 'index.php?option=com_imageshow&controller=flex&task=getScriptCheckThumb&showlist_id='+showlistID +'&rand=' + Math.random(),
			method: 'get',
			noCache: true,
			onComplete: function(response)
			{
			var script   = document.createElement('script');
				script.type  = 'text/javascript';
				script.text  = response;			
				document.body.appendChild(script);
			}
		});
		ajax.send();
	},
	
	checkThumbCallBack: function()
	{
		// will be defined base on view layout
	},
	
	confirmChangeSource: function($msg, showlistID, countImage)
	{
		var confirmBox = false;
		
		if (countImage > 0) 
		{
			var confirmBox = confirm($msg);
		}
		
		if (confirmBox == true || countImage == 0)
		{
			var ajax = new Request({
				url: 'index.php?option=com_imageshow&controller=showlist&task=changeSource&showlist_id='+showlistID+'&rand='+ Math.random(),
				method: 'get',
				onComplete: function(response)
				{
					window.top.location.reload(true);
				}
			});
			ajax.send();
		}
	},
	
	getFormInput: function(formID)
	{
		var options = {};
		
		$(formID).getElements('input, select, textarea', true).each(function(el)
		{
			if (el.disabled == false)
			{
				var name = el.name;
				
				if (el.type == 'radio')
				{
					if (el.checked == true) {
						var value = el.getProperty('value');
					}
				}
				else
				{
					var value = el.getProperty('value');
				}
				
				options[name] = value;
			}
		});	
		
		return options;
	},
	
	submitProfile: function(formID)
	{
		var values = JSNISImageShow.getFormInput(formID);
		var link = '';
			
		try {
			var link = 'index.php?option=' + values['option'] + '&controller=' + values['controller'] + '&task=' + values['task'];
		}catch(err){}
		var ajax = new Request({
			url: link,
			method: 'post',
			data: values,
			onComplete: function(response)
			{
				SqueezeBox.close(); 
				window.top.location.reload(true);
			}
		});
		ajax.send();
	},
	
	parseVersionString: function (str)
	{
		if (typeof(str) != 'string') {return false;}
		var x = str.split('.');		 
		return x;		 
	},
	
	checkVersion: function (runningVersionParam, latestVersionParam)
	{
		var check				= false;
		var self				= this;
		var runningVersion		= JSNISImageShow.parseVersionString(runningVersionParam);
		var countRunningVersion	= runningVersion.length;
		var latestVersion 		= JSNISImageShow.parseVersionString(latestVersionParam);
		var countLatestVersion 	= latestVersion.length;
		var count = 0;

		if	(countRunningVersion > countLatestVersion) {
			count = countLatestVersion;
		} else {
			count = countRunningVersion;
		}
		
		var minIndex = count - 1;
		
		for (var i = 0; i < count; i++)
		{					
			if (runningVersion[i] < latestVersion[i])
			{
				check = true;
				break;
			}
			else if(runningVersion[i] == latestVersion[i] && i == minIndex && countRunningVersion < countLatestVersion)
			{
				check = true;
				break;
			}			
			else if(runningVersion[i] == latestVersion[i])
			{
				continue;
			}
			else
			{
				break;
			}
		}
		
		return check;
	},
	
	toggleListProfile: function(source, profileClass)
	{
		var el = $(source);
		el.toggleClass('jsn-image-source-title-close');
		$$('.' + profileClass).toggleClass('jsn-image-source-profile-close');
	},
	
	deleteSource: function()
	{
		$('adminForm').submit();
		window.top.setTimeout('SqueezeBox.close(); window.top.location.reload(true);', 1000);
	},
	
	confirmChangeTheme: function($msg, showcaseID)
	{
		var r = confirm($msg);
		if (r == true)
		{
			var ajax = new Request({
				url: 'index.php?option=com_imageshow&controller=showcase&task=changeTheme&showcase_id='+showcaseID+'&rand='+ Math.random(),
				method: 'get',
				onComplete: function(response)
				{
					window.top.location='index.php?option=com_imageshow&controller=showcase&task=edit&cid[]='+showcaseID;
				}
			});
			ajax.send();
		}
		else
		{
		  return;
		}
	},
	
	profileShowHintText: function()
	{
		var hintIcons	 = $$('.hint-icon');
		var hintContents = $$('.jsn-preview-hint-text-content');
		var hintCloses 	 = $$('.jsn-preview-hint-close');
		
		hintIcons.each( function(hintIcon, i) 
		{
			hintIcon.addEvent('click', function()
			{
				hintContents.each(function(el, z)
				{
					if (z == i) {
						el.toggleClass('hint-active');
					} else {
						el.removeClass('hint-active');
					}
				});
			});
		});
		
		hintCloses.each(function(close, x)
		{
			close.addEvent('click', function()
			{
				hintContents.each(function (el, z)
				{
					if (z == x) {
						el.removeClass('hint-active');
					}
				});
			});
		});
	},
	
	toggleLoadingIcon: function(elementID, toggle) {
		var element = $(elementID);
		if (toggle)
		{
			element.addClass('show-loading');
		}
		else
		{
			element.removeClass('show-loading');
		}	
	},
	
	_openModal: function()
	{
		var sizes		= window.getSize();
		$('jsn-is-tmp-sbox-window').setStyles({
			display: 'block',
			width: window.getCoordinates().width,
			height: window.getScrollSize().y
		});
		
		$('jsn-is-img-box-loading').setStyles({
			'display'	: 'block',
			'left'		: (window.getCoordinates().width - $('jsn-is-img-box-loading').getStyle('width').toInt()) / 2,
			'top'		: (window.getCoordinates().height - $('jsn-is-img-box-loading').getStyle('height').toInt()) / 2
		});		
	}
};