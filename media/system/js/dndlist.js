(function ($) {
	$.JSortableList = function (tableWrapper, formId, saveOrderingUrl, options) {
		var root  = this;
		var disabledOrderingElements = '';
		var sortableGroupId = '';	
		var sortableRange;
		
		var ops = $.extend({
			orderingIcon       : 'add-on', //class name of order icon
			orderingWrapper    : 'input-prepend', //ordering control wrapper class name
			orderingGroup	   : 'sortable-group-id', //sortable-group-id	
			sortableClassName  : 'dndlist-sortable',	
			placeHolderClassName: 'dnd-list-highlight dndlist-place-holder',	
		}, options); 

		$('tr', tableWrapper).removeClass(ops.sortableClassName).addClass(ops.sortableClassName).css({ 'cursor': 'move'});
		$('#'+formId).attr('autocomplete','off');
		$(tableWrapper).sortable({
			axis: 'y',
			cursor: 'move',
			items: 'tr.' + ops.sortableClassName,
			placeholder: ops.placeHolderClassName,
			
			helper: function (e, ui) {
						ui.children().each(function() {
							$(this).width($(this).width());
						});
						$(ui).children('td').addClass('dndlist-dragged-row');				
						return ui;
					},
			
			
			start: function (e, ui){			
				root.sortableGroupId = ui.item.attr(ops.orderingGroup);
				if(sortableGroupId)	{
					root.sortableRange = $('[sortable-group-id=' + sortableGroupId + ']');
				}else{
					root.sortableRange = $('.' + ops.sortableClassName);
				}	
				//disable sortable for other categories records		
				root.disableOtherGroupSort(e, ui);
				//hide order up, down... 
				root.disableOrderingControl();				
			},
			
			stop: function (e, ui) {					
				$('td', $(this)).removeClass('dndlist-dragged-row');
				$(ui.item).css({opacity:0});
				$(ui.item).animate({
				    opacity: 1,
				  }, 800);
				  
				root.enableOtherGroupSort(e, ui);

				root.enableOrderingControl();
			
				root.rearrangeOrderingControl(root.sortableGroupId, ui);
				if(saveOrderingUrl){
					//clone and check all the checkboxes in sortable range to post
					root.cloneMarkedCheckboxes();

					//serialize form then post to callback url
					var formData = $('#'+formId).serialize();
					formData = formData.replace('task','');
					$.post(saveOrderingUrl, formData);
					
					//remove cloned checkboxes
					root.removeClonedCheckboxes();	
							
				}				
				root.disabledOrderingElements = '';
			}
		}).disableSelection();

		this.disableOtherGroupSort = function (e, ui){
			if ( root.sortableGroupId) {											
				$('[sortable-group-id!=' + root.sortableGroupId + ']',$(tableWrapper)).removeClass(ops.sortableClassName);
				$(tableWrapper).sortable('refresh');
			}							
		}
		
		this.enableOtherGroupSort = function (e, ui){
			$('tr',$(tableWrapper)).removeClass(ops.sortableClassName).addClass(ops.sortableClassName);
			$(tableWrapper).sortable('refresh');				
		}

		this.disableOrderingControl = function (){
			$('.'+ ops.orderingWrapper +' .add-on a',root.sortableRange).hide();
		}
		
		this.enableOrderingControl = function (){			
			$('.'+ ops.orderingWrapper +' .add-on a',root.disabledOrderingElements).show();
		}

		this.rearrangeOrderingControl = function (sortableGroupId, ui) {
			var range;		
			if(sortableGroupId)	{
				root.sortableRange = $('[sortable-group-id=' + sortableGroupId + ']');
			}else{
				root.sortableRange = $('.' + ops.sortableClassName);
			}
			range = root.sortableRange;			
			var count = range.length;			
			var i = 0;			
			if(count > 1){			
				range.each(function () {
					//firstible, add both ordering icons for missing-icon item
					var upIcon = $('.'+ ops.orderingWrapper + ' .add-on:first a', $(this)); //get orderup icon of current dropped item
					var downIcon = $('.'+ ops.orderingWrapper + ' .add-on:last a', $(this)); //get orderup icon of current dropped item					
					if (upIcon.get(0) && downIcon.get(0)){
						//do nothing
					}else if (upIcon.get(0)){						
						upIcon.removeAttr('title');
						upIcon   = $('.'+ ops.orderingWrapper + ' .add-on:first', $(this)).html();
						downIcon = upIcon.replace('icon-uparrow', 'icon-downarrow');
						downIcon = downIcon.replace('.orderup', '.orderdown');
						$('.'+ ops.orderingWrapper + ' .add-on:last', $(this)).html(downIcon);				
					}else if (downIcon.get(0)){						
						downIcon.removeAttr('title');
						downIcon = $('.'+ ops.orderingWrapper + ' .add-on:last', $(this)).html();
						upIcon   = downIcon.replace('icon-downarrow', 'icon-uparrow');
						upIcon   = upIcon.replace('.orderdown', '.orderup');
						$('.'+ ops.orderingWrapper + ' .add-on:first', $(this)).html(upIcon);					
					}						
				});	
								
				//remove orderup icon for first record
				$('.'+ ops.orderingWrapper + ' .add-on:first a', range[0]).remove();
				//remove order down icon for last record
				$('.'+ ops.orderingWrapper + ' .add-on:last a', range[(count-1)]).remove();
				
				//recalculate order number							
				if(ui.originalPosition.top > ui.item.offset().top) //if item moved up
				{	
					$('[type=text]',ui.item).attr('value',parseInt($('[type=text]',ui.item.next()).attr('value')));
					$(range).each(function (){						
						var _top = $(this).offset().top;						
						if(_top > ui.item.offset().top && _top <= ui.originalPosition.top ) {														
							var newValue = parseInt($('[type=text]',$(this)).attr('value')) + 1;
							$('[type=text]',$(this)).attr('value',newValue);							
						}						
					});
				} else if (ui.originalPosition.top < ui.item.offset().top){					
					$('[type=text]',ui.item).attr('value',parseInt($('[type=text]',ui.item.prev()).attr('value')));
					$(range).each(function (){
						var _top = $(this).offset().top;												
						if(_top < ui.item.offset().top && _top >= ui.originalPosition.top ) {							
							var newValue = parseInt($('[type=text]',$(this)).attr('value')) - 1;
							$('[type=text]',$(this)).attr('value',newValue);							
						}
					});
				}
			}	
		}

		this.cloneMarkedCheckboxes = function () {
			$('[name="order[]"]', $(tableWrapper)).attr('name','order-tmp');
			$('[type=checkbox]', root.sortableRange).each (function () {
				var _shadow = $(this).clone();
				$(_shadow).attr({'checked':'checked','shadow':'shadow','id':''});
				$('#' + formId).append( $( _shadow));
				
				$('[name="order-tmp"]',$(this).parents('tr')).attr('name','order[]');
			});
		}
		
		this.removeClonedCheckboxes = function () {
			$('[shadow=shadow]').remove();
			$('[name="order-tmp"]', $(tableWrapper)).attr('name','order[]');
		}
	}	
})(jQuery);