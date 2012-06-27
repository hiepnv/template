var JSNISAccordions = new Class({
	options : {
		closeAnother: false
	},
	
	initialize: function(element, options) 
	{	
		this.options 	= $merge(this.options, options);		
		this.el 		= $(element);
		this.elID 		= element;		
		this.list 		= $$('#' + this.elID + ' div.jsn-accordion-pane');		
		this.headings 	= $$('#' + this.elID + ' .jsn-accordion-title');
		this.button		= $$('#' + this.elID + ' .jsn-accordion-title .jsn-accordion-button');
		
		if(this.options.multiple)
		{
			if (this.options.closeAnother == false) {
				this.multipleOpen(this.options.activeClass, this.options.showFirstElement, this.options.durationEffect);
			} else {
				this.multipleOpenCloseAnother(this.options.activeClass, this.options.showFirstElement, this.options.durationEffect);
			}
		}
		else
		{
			this.singleOpen(this.options.activeClass, this.options.showFirstElement, this.options.durationEffect);
		}
	},
	
	multipleOpen:function(activeClass, showFirstElement, durationEffect)
	{
		var status 			= {'true': 'open', 'false': 'close'};
		var settings 		= null;
		var strCookieParse 	= '';
		
		if(Cookie.read(this.options.cookieName) != undefined && Cookie.read(this.options.cookieName) != 'null' && Cookie.read(this.options.cookieName) != false)
		{
			strCookieParse = Cookie.read(this.options.cookieName).substr(0, Cookie.read(this.options.cookieName).length - 1);
		}
		else
		{
			Cookie.write(this.options.cookieName, settings, {duration: 15});
		}		
		var list     	 = this.list;
		this.collapsibles = new Array();
		var headings	 = this.headings;
		var button		 = this.button;
		var buttonClass	 = 'button-' + activeClass;
		
		headings.each( function(heading, i) 
		{
			var collapsible = new Fx.Slide(list[i], 
			{
				duration: durationEffect,
				transition: Fx.Transitions.linear
			});
			
			this.collapsibles[i] = collapsible;
			
			heading.addEvent('click', function()
			{
				collapsible.toggle();		
			}.bind(this));

			if (showFirstElement && i == 0)
			{
				if(headings[0].className.indexOf(activeClass) == -1)
				{
					headings[0].addClass(activeClass);
					button[0].addClass(buttonClass);
				}
			}
			else
			{
				collapsible.hide();
			}
			
			collapsible.addEvent('onStart', function() 
			{
				if (heading)
				{
					if (heading.className.indexOf(activeClass) == -1)
					{
						heading.addClass(activeClass);
						button[i].addClass(buttonClass);
					}
					else
					{						
						heading.removeClass(activeClass);
						button[i].removeClass(buttonClass);
					}
				}
			}.bind(this));	
			
			collapsible.addEvent('onComplete', function() 
			{
				var strCookie = Cookie.read(this.options.cookieName);
				
				if (strCookie != 'null')
				{
					if (heading.className.indexOf(activeClass) != -1)
					{
						if (strCookie.indexOf(i) == -1)
						{
							strCookie += " " + i + " ";							
							settings = strCookie;
							Cookie.write(this.options.cookieName, settings,  {duration: 15});
						}
					}
					else
					{						
						str = strCookie.replace(i, "");					
						settings = str;
						Cookie.write(this.options.cookieName, settings,  {duration: 15});		
					}
				}
				else
				{
					settings = " " + i + " ";					
					Cookie.write(this.options.cookieName, settings,  {duration: 15});	
				}	
			}.bind(this));
			
		}.bind(this));
	},
	
	loadAccordion: function()
	{
		var strCookie = Cookie.read(this.options.cookieName);
		
		if (strCookie) 
		{
			this.headings.each(function(el, i)
			{
				var status = el.className.contains('down');
				if (this.options.multiple) 
				{
					if (this.options.closeAnother) 
					{
						if (strCookie.indexOf(i) >= 0  && status == false ) {
							el.fireEvent('click');
						} else if (strCookie.indexOf(i) == -1  && status == true) {
							el.fireEvent('click');
						}
					} 
					else 
					{
						if (strCookie.indexOf(i) >= 0  && status == false) {
							el.fireEvent('click');
						}
					}
				} 
			}.bind(this));
		}
	},
	
	multipleOpenCloseAnother:function(activeClass, showFirstElement, durationEffect)
	{
		var status 			= {'true': 'open', 'false': 'close'};
		var settings 		= null;
		var strCookieParse 	= '';
		
		if(Cookie.read(this.options.cookieName) != undefined && Cookie.read(this.options.cookieName) != 'null' && Cookie.read(this.options.cookieName) != false)
		{
			strCookieParse = Cookie.read(this.options.cookieName).substr(0, Cookie.read(this.options.cookieName).length - 1);
		}
		else
		{
			Cookie.write(this.options.cookieName, settings, {duration: 15});
		}		
		var list     	 = this.list;
		this.collapsibles = new Array();
		var headings	 = this.headings;
		var button		 = this.button;
		var buttonClass	 = 'button-' + activeClass;
		
		list.each(function(el) 
		{
			var collapsible = new Fx.Slide(el, 
			{
				duration: durationEffect,
				transition: Fx.Transitions.linear
			});
			this.collapsibles.push(collapsible);
			
		}.bind(this));
		
		headings.each( function(heading, i) 
		{
			var collapsible = this.collapsibles[i];
			
			heading.addEvent('click', function()
			{
				collapsible.toggle();	
				this.collapsibles.each(function(el, elIndex) 
				{
					if (elIndex != i) 
					{
						if(headings[elIndex].className.indexOf(activeClass) > 0)
						{
							el.slideOut();
						}
					}
				});
			}.bind(this));

			if (showFirstElement && i == 0)
			{
				if(headings[0].className.indexOf(activeClass) == -1)
				{
					headings[0].addClass(activeClass);
					button[0].addClass(buttonClass);
				}
			}
			else
			{
				collapsible.hide();
			}
			
			collapsible.addEvent('onStart', function() 
			{
				if (heading)
				{
					if (heading.className.indexOf(activeClass) == -1)
					{
						heading.addClass(activeClass);
						button[i].addClass(buttonClass);
					}
					else
					{						
						heading.removeClass(activeClass);
						button[i].removeClass(buttonClass);
					}
				}
			}.bind(this));	
			
			collapsible.addEvent('onComplete', function() 
			{
				var strCookie = Cookie.read(this.options.cookieName);
				
				if (strCookie != 'null')
				{
					if (heading.className.indexOf(activeClass) != -1)
					{
						if (strCookie.indexOf(i) == -1)
						{
							strCookie += " " + i + " ";							
							settings = strCookie;
							Cookie.write(this.options.cookieName, settings,  {duration: 15});
						}
					}
					else
					{						
						str = strCookie.replace(i, "");					
						settings = str;
						Cookie.write(this.options.cookieName, settings,  {duration: 15});		
					}
				}
				else
				{
					settings = " " + i + " ";					
					Cookie.write(this.options.cookieName, settings,  {duration: 15});	
				}	
			}.bind(this));
			
		}.bind(this));
	},
	
	singleOpen:function(activeClass, showFirstElement, durationEffect)
	{
		var list     		= this.list;
		var collapsibles 	= new Array();
		var headings	 	= this.headings;
		var radios			= $$('.jsn-accordion-radio');
		var countRadios		= radios.length;	
		headings.each( function(heading, i) 
		{
			var collapsible = new Fx.Slide(list[i], {
				duration: durationEffect,
				transition: Fx.Transitions.Circ.easeOut
			});
			collapsibles[i] = collapsible;
			
			heading.addEvent('click', function(){
				
				for (var z = 0; z < countRadios; z++)
				{
					radios[z].checked = false;
				}	
				radios[i].checked = true;
				if (heading.className.indexOf(activeClass) == -1)
				{
					heading.addClass(activeClass);
				}
				else
				{
					//heading.removeClass(activeClass);
					return;
				}
			    for (var j = 0; j < collapsibles.length; j++)
			    {
		            if (j!=i) 
		            {
		            	collapsibles[j].slideOut();		            
		            	if (headings[j].className.indexOf(activeClass) != -1)
						{
		            		headings[j].removeClass(activeClass);
						}
		            }
			    }			    
				collapsible.toggle();
				return false;
			}.bind(this));			
			collapsible.hide();
			
			if(showFirstElement)
			{
				collapsibles[0].toggle();
				if(headings[0].className.indexOf(activeClass) == -1)
				{
					headings[0].addClass(activeClass);
				}			
			}
		});		
	}	
});