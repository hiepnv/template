 /**
 * @author Jenna "Blueberry" Fox (http://creativepony.com/#contact)
 * @copyright Jenna "Blueberry" Fox
 * @modified JoomlaShine.com Team
 */
var JSNISContentClip = new Class({
	options: {
		startingSlide: false, 
		activeButtonClass: 'active', 
		activationEvent: 'click',
		wrap: false,
		slideEffect: {
		  duration: 400
		},
		animateHeight: true,
		rightOversized: 0
	},	
	current: null,
	buttons: false,
	navigation: null,
	outerSlidesBox: null,
	innerSlidesBox: null,
	panes: null,
	fx: null,
	heightFx: null,
   
	initialize: function(buttonContainer, slideContainer, previousButton, nextButton, options) {
		if (buttonContainer) 
		{ 
			this.buttons = $(buttonContainer).getChildren(); 
		}
		
		this.outerSlidesBox = $(slideContainer);
		this.innerSlidesBox = this.outerSlidesBox.getFirst();
		this.panes = this.innerSlidesBox.getChildren();
		this.navigation = this.outerSlidesBox.getPrevious();
		this.previousButton = $(previousButton);
		this.nextButton 	= $(nextButton);
		this.previousButton.addClass('disabled');
		this.setOptions(options);
		this.fx = new Fx.Scroll(this.outerSlidesBox, this.options.slideEffect);
		this.heightFx = new Fx.Tween(this.outerSlidesBox, {
							duration: 'long',
							transition: 'circ:out',
							property: 'height'
						});
		
		this.current = this.options.startingSlide ? this.panes.indexOf($(this.options.startingSlide)) : 0;
		
		if (this.buttons) 
		{ 
			this.buttons[this.current].addClass(this.options.activeButtonClass); 
		}
		
		this.outerSlidesBox.setStyle('overflow', 'hidden');
		//this.outerSlidesBox.setStyle('width', this.outerSlidesBox.offsetWidth.toInt() - this.options.rightOversized + 'px');
		this.panes.each(function(pane, index) {
		  pane.setStyles({
		   'float': 'left',
		   'overflow': 'hidden'
		  });
		}.bind(this));
		
		this.innerSlidesBox.setStyle('float', 'left');
		
		if (this.options.startingSlide) this.fx.toElement(this.options.startingSlide);

		
		if (this.buttons) 
		{
			this.buttons.each(function(button) {
				button.addEvent(this.options.activationEvent, this.buttonEventHandler.bindWithEvent(this, button));
			}.bind(this));
		}
		if (this.options.animateHeight)
		{
			this.heightFx.set('height', this.panes[this.current].offsetHeight);
		}
		this.changeTo(this.panes[this.current]);
		this.recalcWidths();
		this.showNavigation();
	}, 
	changeTo: function(element, animate) {
		
		if ($type(element) == 'number') element = this.panes[element - 1];
		if (!$defined(animate)) animate = true;
		var event = { cancel: false, target: $(element), animateChange: animate };
		element.fireEvent('change', event);
		
		if (event.cancel == true) 
		{ 
			return; 
		}
		
		if (this.buttons) 
		{ 
			this.buttons[this.current].removeClass(this.options.activeButtonClass); 
		}
		
		this.current = this.panes.indexOf($(event.target));
		
		if (this.buttons) 
		{
			this.buttons[this.current].addClass(this.options.activeButtonClass); 
		}
			
		if (event.animateChange) 
		{	   
			this.fx.cancel();
			this.fx.toElement(event.target);
		}
		else 
		{
			this.outerSlidesBox.scrollTo(this.current * this.outerSlidesBox.offsetWidth.toInt(), 0);			
		}
		
		if (this.options.animateHeight)
		{
		 	this.heightFx.cancel();
			this.heightFx.start(this.panes[this.current].offsetHeight);
		}
	},
  	
	buttonEventHandler: function(event, button) {
		if (event.target == this.buttons[this.current]) return;
		this.changeTo(this.panes[this.buttons.indexOf($(button))]);
	},
  
	next: function() {
		var next = this.current + 1;
		this.previousButton.removeClass('disabled');
		if (next == this.panes.length) 
		{
			if (this.options.wrap == true) 
			{ 
				next = 0; 
			} 
			else 
			{
				return; 
			}
		}
		if (next == (this.panes.length - 1))
		{
			this.nextButton.addClass('disabled');
		}
		this.changeTo(this.panes[next]);		
	},
  
	previous: function() {
		var prev = this.current - 1;
		this.nextButton.removeClass('disabled');
		if (prev < 0) 
		{
		  if (this.options.wrap == true) 
		  { 
			  prev = this.panes.length - 1; 
		  } 
		  else 
		  { 
			  return ;
		  }
		}
		if (prev == 0)
		{
			this.previousButton.addClass('disabled');
		}
		this.changeTo(this.panes[prev]);
	},
	
	showNavigation: function() {
		if (this.navigation.hasClass('nav-hover')) {
			var nav = this.navigation;
			nav.setStyle('opacity',0);
			//var navFading = new Fx.Style(nav, 'opacity', {duration:200});			
			var navFading = new Fx.Tween(nav, {
				duration: '200',
				transition: 'circ:out',
				property: 'opacity'
			});
			this.outerSlidesBox.getParent().addEvent('mouseenter', function() {
				navFading.start(1);
			});
			this.outerSlidesBox.getParent().addEvent('mouseleave', function() {
				navFading.cancel();
				navFading.start(0);
			});
		}
	},
  
	recalcWidths: function() {
		this.panes.each(function(pane, index) {
			pane.setStyle('width', this.outerSlidesBox.offsetWidth.toInt() - this.options.rightOversized + 'px');
		}.bind(this));

		this.innerSlidesBox.setStyle(
		  'width', (this.outerSlidesBox.offsetWidth.toInt() * this.panes.length) + 'px'
		);
		
		if (this.current > 0) 
		{
		  this.fx.cancel();
		  this.outerSlidesBox.scrollTo(this.current * this.outerSlidesBox.offsetWidth.toInt(), 0);
		}
	}
});

JSNISContentClip.implement(new Options, new Events);