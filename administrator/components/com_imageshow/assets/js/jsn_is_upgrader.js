/**
* @author    JoomlaShine.com http://www.joomlashine.com
* @copyright Copyright (C) 2008 - 2011 JoomlaShine.com. All rights reserved.
* @license   GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
*/

var JSNISUpgrader =  new Class({
	options: {},
	initialize: function(options)
	{
		this.options = $merge(this.options, options);
	},
	
	upgrade: function(username, password, edition)
	{		
		this.options.username 		= username;
		this.options.password 		= password;	
		this.options.edition = edition;
		this.download();		
	},
	
	download: function()
	{
		$('jsn-upgrader-upgrading').setStyle('display', 'block');
		$('jsn-upgrader-downloading').setStyle('display', 'inline-block');
		$('jsn-upgrader-cancel').setStyle('display', 'none');
		this.options.task 		= 'downloadImageShowCore';
		var tmpOptions = {};
		tmpOptions.processText 	= this.options.process_text;
		tmpOptions.waitText 	= this.options.wait_text;
		tmpOptions.parentID 	= "jsn-upgrader-downloading-text";	
		tmpOptions.textTag 		= "span";	
		var changetext 			= new JSNISInstallChangeText(tmpOptions);
		var tmpOptions1 = {};
		tmpOptions1.processText = this.options.process_text;
		tmpOptions1.waitText 	= this.options.wait_text;
		tmpOptions1.parentID 	= "jsn-upgrader-installing-text";	
		tmpOptions1.textTag 	= "span";	
		var changetext1 			= new JSNISInstallChangeText(tmpOptions1);			
		var error				= '';
		var tmpCoreOptions 		= new this.cloneObject(this.options);
		tmpCoreOptions.upgrade 	= 'yes';
		delete tmpCoreOptions.process_text;
		delete tmpCoreOptions.wait_text;
		delete tmpCoreOptions.process_text;
		delete tmpCoreOptions.error_code;
		delete tmpCoreOptions.manual_download_text;	
		delete tmpCoreOptions.manual_install_button;	
		delete tmpCoreOptions.manual_then_select_it_text;
		delete tmpCoreOptions.downloadLink;
		this.currentDownload = tmpCoreOptions;

		setTimeout(
			function(){
				$('jsn-upgrader-downloading-text').setStyle('display', 'inline-block');
			}, 3000);
		setTimeout(
				function(){
					$('jsn-upgrader-installing-text').setStyle('display', 'inline-block');
				}, 3000);
		var jsonRequest = new Request.JSON({
			url: 'index.php?option=com_imageshow&controller=installer&rand='+ Math.random(),
			onSuccess: function(jsonObj)
			{
				if(jsonObj.success) 
				{									
					changetext.destroy();
					$('jsn-upgrader-downloading-text').setStyle('display', 'none');
					$('jsn-upgrader-downloading').setStyle('display', 'none');					
					$('jsn-upgrader-successful-downloading').setStyle('display', 'inline-block');
					$('jsn-upgrader-data-package-installing-title').setStyle('display', 'list-item');
					$('jsn-upgrader-installing').setStyle('display', 'inline-block');
					this.installPackage(jsonObj.package_path);	
				} 
				else 
				{									
					changetext.destroy();
					changetext1.destroy();
					$('jsn-upgrader-cancel').setStyle('display', 'inline-block');
					$('jsn-upgrader-unsuccessful-downloading').setStyle('display', 'inline-block');
					$('jsn-upgrader-downloading-text').setStyle('display', 'none');
					$('jsn-upgrader-downloading').setStyle('display', 'none');
					this.manualInstall();
				}				
			}.bind(this),		
			onFailure: function(){
			}.bind(this)
		}).post(tmpCoreOptions);
	},
	
	cloneObject: function(source) 
	{
	    for (i in source) 
	    {
	    	if (typeof source[i] == 'source') 
	        {		
	    		this[i] = new cloneObject(source[i]);
	        }
	        else
	        {
	            this[i] = source[i];
	        }
	    }
	},
	
	installPackage: function(packagePath)
	{
		var form = document.getElementById('jsn_frm_upgrader_install_core');
		form.package_path.value = packagePath;
		form.submit();
	},

	manualInstall: function ()
	{
		this.currentDownload.formImageshowCore = true;
		this.currentDownload.parentId = 'jsn-upgrader-manual-upgrading';
		
		if ($(this.currentDownload.parentId)) {
			$(this.currentDownload.parentId).innerHTML = '';
		}
		
		this.currentDownload.downloadText = this.options.manual_download_text;
		this.currentDownload.actionForm = 'index.php?option=com_imageshow&controller=installer&task=installImageShowCoreManual';
		
		this.currentDownload.downloadLink = this.options.downloadLink + 
				'&identified_name=' + encodeURI(this.currentDownload.identify_name) + 
				'&edition=' + encodeURI(this.currentDownload.edition) + 
				'&joomla_version=' + encodeURI(this.currentDownload.joomla_version) + 
				'&username=' + encodeURI(this.currentDownload.username) +
				'&password=' + encodeURI(this.currentDownload.password) +
				'&language=' + encodeURI(this.options.language) +
				'&based_identified_name=&upgrade=yes';
		
		this.currentDownload.redirectLink = 'index.php?option=com_imageshow&controller=upgrader';
		this.currentDownload.manualInstallButton 	= this.options.manual_install_button;
		this.currentDownload.manualThenSelectItText = this.options.manual_then_select_it_text;
		this.currentDownload.dowloadInstallationPackageText = this.options.dowload_installation_package_text;
		this.currentDownload.selectDownloadPackageText = this.options.select_download_package_text;
		this.currentDownload.manualDownloadText = this.options.manual_download_text;
		
		var manualInstall = new JSNInstallManual(this.currentDownload); 
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