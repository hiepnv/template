/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_updater.js 13228 2012-06-12 09:18:53Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */

var JSNISUpdater = new Class({
	options : {
		lightCartErrorCode: {}
	},
	initialize: function(options)
	{
		this.options = $merge(this.options, options);
		this.downloadRequests = [];
		this.countDownloadRequest = 0;
		this.error = false;
		this.errorMsg = '';
	},
	
	startDownload: function() 
	{
		this.totalDownloadRequest = this.downloadRequests.length;
		
		if (this.totalDownloadRequest > 0) {
			this.downloadRequests[this.countDownloadRequest].post();
		}
	},
	
	onUpdate: function()
	{
		// check commercail, if true, set information about customer to options
		if (this.options.authentication) {
			if (!this.onLogin()){
				return false;
			};

			// if have login form, hide it
			//$(this.options.loginFormID).style.display = 'none';
		}
		
		// hide update button
		$(this.options.buttonID).style.display = 'none';
		$(this.options.linkCancelID).style.display = 'none';
		
		// add download requests
		this.options.core.each(function(core) {
			core.type		 = 'core';
			core.task 		 = 'downloadImageShowCore';
			core.installTask = 'installImageShowCore';
			this.addDownloadRequest(core);
		}.bind(this));

		this.options.themes.each(function(theme) {
			theme.type		 	= 'theme';
			theme.task 			= 'downloadShowcaseTheme';
			theme.installTask 	= 'installShowcaseTheme';
			this.addDownloadRequest(theme);
		}.bind(this));
		
		this.options.sources.each(function(source) {
			source.type		 	= 'imagesource';
			source.task 		= 'downloadImageSource';
			source.installTask 	= 'installImageSource';
			this.addDownloadRequest(source);
		}.bind(this));
		
		this.startDownload();
	},
	
	onLogin: function(){
		return true;
	},
	
	addDownloadRequest: function(data) 
	{
		if (this.options.authentication) {
			data.username = this.options.username;
			data.password = this.options.password;
		}
		data.upgrade = 'yes';
		var jsonRequest = new Request.JSON({
			url: 'index.php?option=com_imageshow&controller=installer&rand='+ Math.random(), 
			data: data,
			onRequest: function()
			{
				this.currentData = data;
				this.callProcessBox();
				this.currentElement.addClass('jsn-onrequest-download');
			}.bind(this),
			
			onSuccess: function(jsonObj)
			{
				if(jsonObj.success) {
					this.currentElement.addClass('jsn-onsuccess-download');
					this.installPackage(jsonObj.package_path, data);	
				} else {
					this.error = true;
					this.checkError();
					this.currentElement.addClass('jsn-onsuccess-download-failure');
					
					if (this.options.lightCartErrorCode[jsonObj.message]) {
						alert(this.options.lightCartErrorCode[jsonObj.message]);
					} else {
						alert(jsonObj.message);
						this.currentData.downloadStep = true;
						this.manualInstall();
					}
				}
			}.bind(this),
			
			onCancel: function(){
				this.currentElement.addClass('jsn-oncancel-download');
			}.bind(this),
			
			onFailure: function(){
				this.currentElement.addClass('jsn-onfailure-download');
			}.bind(this)
		});
		
		this.downloadRequests.push(jsonRequest);
	},
	
	callNextDownloadRequest: function() 
	{
		this.countDownloadRequest = this.countDownloadRequest + 1;
		
		if (this.countDownloadRequest < this.totalDownloadRequest) {
			this.downloadRequests[this.countDownloadRequest].post();
		} 
		else // check error after all requests are called 
		{
			if (this.error == false){
				this.onUpdateSuccess();
			}else {
				this.checkError();
			}
		}
	},
	
	onUpdateSuccess: function(){
		$(this.options.buttonID).style.display = 'block';
		$(this.options.buttonID).addClass('jsn-updater-successfull');
		$(this.options.successID).style.display = 'block';
	},
	
	installPackage: function(packagePath, el)
	{
		var jsonRequest = new Request.JSON({
			url: 'index.php', 
			onRequest: function(){
				this.currentElement.addClass('jsn-onrequest-install');
			}.bind(this),
			onSuccess: function(jsonObj)
			{
				if(jsonObj.success == true) {
					this.currentElement.addClass('jsn-onsuccess-install');
					this.callNextDownloadRequest();
				}else {
					this.error = true;
					this.errorMsg = jsonObj.message;
					this.checkError();
					this.currentElement.addClass('jsn-onsuccess-install-failure');
				}
			}.bind(this), 
			onFailure: function()
			{
				this.currentElement.addClass('jsn-onfailure-install');
			}.bind(this)
			}).get({'option': 'com_imageshow',
					 'controller': 'installer',
					 'task': el.installTask,
					 'package_path': packagePath,
					 'rand': Math.random(),
					 'redirect': 0
					});	
	},
	
	checkError: function()
	{
		if (this.error == true) {
			$('jsn-updater-install-message').innerHTML = this.errorMsg;
			$(this.options.buttonID).style.display = 'block';
			$(this.options.buttonID).addClass('jsn-updater-cancel');
		}
	},
	
	callProcessBox: function()
	{
		this.currentElement = $(this.currentData.elementID);
		
		if (this.processBox) {
			this.processBox.innerHTML = '';
		} 
		
		this.createProcessBox();
		this.currentElement.appendChild(this.processBox);
	},
	
	createProcessBox: function() {
		var wrap = new Element('div', {'class': 'jsn-updater-processing-box'});
		var loading = new Element('img', {'class' : 'jsn-updater-loading', 'src' :'components/com_imageshow/assets/images/ajax-loader-circle.gif'});
		var success = new Element('span', {'class': 'jsn-updater-success'});
		var failure = new Element('span', {'class': 'jsn-updater-failure'});
		var contain = new Element('ul');
		var tmpspan = new Element('span');
		var tmpOptions = {};
		tmpOptions.processText 	= this.options.process_text;
		tmpOptions.waitText 	=  this.options.wait_text;	
		tmpOptions.textTag 		= "span";	

		var tmpOptions1 = {};
		tmpOptions1.processText 	= this.options.process_text;
		tmpOptions1.waitText 	=  this.options.wait_text;	
		tmpOptions1.textTag 		= "span";	
		
		var download = new Element('li', {'id': 'jsn-updater-download','class': 'jsn-updater-download'});
		download.innerHTML = this.options.languageText.downloadText;
		download.appendChild(loading.clone());
		download.appendChild(success.clone());
		download.appendChild(failure.clone());
		
			downloadMessage = new Element('span', {'id': 'jsn-updater-download-message', 'class': 'jsn-updater-process-message'});
			downloadMessage.appendChild(tmpspan.clone());
			download.appendChild(downloadMessage);
			contain.appendChild(download);
			tmpOptions.parentID 	= "jsn-updater-download-message";			
			setTimeout(
				function(){
					var changetext 			= new JSNISInstallChangeText(tmpOptions);
				}, 3000);				
			
		var install = new Element('li', {'id' : 'jsn-updater-install','class': 'jsn-updater-install'});
		install.innerHTML = this.options.languageText.installText;
		install.appendChild(loading.clone());
		install.appendChild(success.clone());
		install.appendChild(failure.clone());
		
			installMessage = new Element('span', {'id': 'jsn-updater-install-message', 'class': 'jsn-updater-process-message'});
			installMessage.appendChild(tmpspan.clone());
			install.appendChild(installMessage);
			contain.appendChild(install);
			tmpOptions1.parentID 	= "jsn-updater-install-message";
			setTimeout(
			function(){
				var changetext1 			= new JSNISInstallChangeText(tmpOptions1);
			}, 3000);
			
		this.currentElement.appendChild(loading);
		this.currentElement.appendChild(success);
		this.currentElement.appendChild(failure);
		wrap.appendChild(contain);		
		this.processBox = wrap;	
	},
	
	manualInstall: function()
	{
		$(this.options.buttonID).style.display = 'none';
		if (this.currentData.downloadStep) {
			this.currentData.parentId = 'jsn-updater-download';
		} 
		
		if (this.currentData.installStep) {
			this.currentData.parentId = 'jsn-updater-install';
		}
		
		if (this.currentData.type == 'imagesource') 
		{
			this.currentData.downloadLink = this.options.downloadLink + 
			'&identified_name=' + encodeURI(this.currentData.identify_name) + 
			'&edition=' + encodeURI(this.currentData.edition) + 
			'&joomla_version=' + encodeURI(this.currentData.joomla_version) +
			'&language=' + encodeURI(this.options.language) +
			'&based_identified_name=imageshow&upgrade=yes';
			
			this.currentData.actionForm = 'index.php?option=com_imageshow&controller=installer&task=installImagesourceManual';
		} 
		else if (this.currentData.type == 'theme')
		{
			this.currentData.downloadLink = this.options.downloadLink + 
			'&identified_name=' + encodeURI(this.currentData.identify_name) + 
			'&edition=' + encodeURI(this.currentData.edition) + 
			'&joomla_version=' + encodeURI(this.currentData.joomla_version) +
			'&language=' + encodeURI(this.options.language) +
			'&based_identified_name=imageshow&upgrade=yes';
			
			this.currentData.actionForm = 'index.php?option=com_imageshow&controller=installer&task=installThemeManual';
		}
		else if (this.currentData.type == 'core')
		{
			this.currentData.downloadLink = this.options.downloadLink + 
			'&identified_name=' + encodeURI(this.currentData.identify_name) + 
			'&edition=' + encodeURI(this.currentData.edition) + 
			'&joomla_version=' + encodeURI(this.currentData.joomla_version) +
			'&language=' + encodeURI(this.options.language) +
			'&based_identified_name=&upgrade=yes';
			
			this.currentData.actionForm = 'index.php?option=com_imageshow&controller=installer&task=installImageShowCoreManual&redirect=0';
		}
		
		if(this.options.authentication) {
			this.currentData.downloadLink += '&username=' + encodeURI(this.currentData.username) + '&password=' + encodeURI(this.currentData.password);
		}
		
		this.currentData.downloadPluginText = this.options.languageText.manualDownloadText;
		this.currentData.redirectLink = this.options.redirectLink;
		this.currentData.manualInstallButton 	= this.options.languageText.manualInstallButton;
		this.currentData.manualThenSelectItText = this.options.languageText.manualThenSelectItText;
		this.currentData.dowloadInstallationPackageText = this.options.languageText.dowloadInstallationPackageText;
		this.currentData.selectDownloadPackageText = this.options.languageText.selectDownloadPackageText;	
		this.currentData.manualDownloadText = this.options.languageText.manualDownloadText;	
		this.currentData.formUpdatePage = true;
		var manualInstall = new JSNInstallManual(this.currentData); 
		manualInstall.startManualInstall();
	},
	
	setNextButtonState: function(form, button) {
		var buttonDisable = true;
		var username = form.username.value;
		var password = form.password.value;
		if (typeof button !== "undefined")
		{
			if (username != '' && password != '' && form.jsn_upgrade_edition == undefined)
			{
				buttonDisable = false;
				$('jsn-upgrader-btn-next').removeClass("disabled");
			}
			else if (form.jsn_upgrade_edition != undefined && form.jsn_upgrade_edition.value !='') 
			{
				buttonDisable = false;
				$('jsn-upgrader-btn-next').removeClass("disabled");
			}
			else
			{
				buttonDisable = true;
				$('jsn-upgrader-btn-next').addClass("disabled");
				
			}		
			button.disabled = buttonDisable;
		}
	}	
});