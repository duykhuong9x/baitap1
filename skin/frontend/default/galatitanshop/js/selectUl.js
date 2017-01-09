(function($) {
	$.fn.selectUl = function() {
		var config = {
			over: function() {
				if ($(this).parent().children().length > 1) {
					$(this).parent().children('.toolbar-dropdown').children('ul').addClass('over');
				} else {
					$(this).addClass('over');
					// $('.toolbar-dropdown', this).css({width: $(this).width()+50});
				}
				$(this).parent().children('.toolbar-dropdown').children('ul').animate({
					opacity: 1,
					height: 'toggle'
				}, 100);
				//$('.toolbar-dropdown ul', this).animate({opacity:1, height:'toggle'}, 100);
			},
			timeout: 0,
			// number = milliseconds delay before onMouseOut
			out: function() {
				var that = this;
				$(this).parent().children('.toolbar-dropdown').children('ul').animate({
					opacity: 0,
					height: 'toggle'
				}, 100, function() {
					if ($(this).parent().children().length > 1) {
						$(that).parent().children('.toolbar-dropdown').children('ul').removeClass('over');
					} else {
						$(that).removeClass('over');
					}
				});
			}
		};
		$('.toolbar-title select').css('display', 'none');
		$('.toolbar-switch .toolbar-dropdown .current, .toolbar-switch .toolbar-dropdown').hoverIntent(config);
	};
	$.fn.insertUl = function() {
		var numOptions = $(this).children().length;
		$('<div class="toolbar-dropdown"><span class="current"/><ul style="display:none" /></div>').insertAfter($(this).toggleClass('.toolbar-switch').parent());
		var divSpan = $(this).toggleClass('toolbar-switch').parent().parent().find('span');
		divSpan.append($(this).parent().children('select').find('option:selected').text());
		var divUl = $(this).toggleClass('toolbar-switch').parent().parent().find('ul');
		for (var i = 0; i < numOptions; i++) {
			var text = '<li><a href ="' + $(this).find('option').eq(i).val() + '">' + $(this).find('option').eq(i).text() + '</a></li>';
			//$('<li />').text(text).appendTo(divUl);
			divUl.append(text);
		}
	};
	$.fn.insertUlLanguage = function() {
		var numOptions = $(this).children().length;
		$('<div class="toolbar-dropdown"><span class="current"/><ul style="display:none" /></div>').insertAfter($(this).toggleClass('.toolbar-switch').parent());
		var divSpan = $(this).toggleClass('toolbar-switch').parent().parent().find('span');
		divSpan.append($(this).parent().children('select').find('option:selected').text());
		imageUrl = language + ($(this).find('option').eq(i).text()).toLowerCase() + '.png';
		divSpan.css('background-image', 'url(' + language + ($(this).parent().children('select').find('option:selected').text()).toLowerCase() + '.png)');
		divSpan.css('background-repeat', 'no-repeat');
		var divUl = $(this).toggleClass('toolbar-switch').parent().parent().find('ul');
		for (var i = 0; i < numOptions; i++) {
			var text = '<li><a style="background-image:url(' + language + ($(this).find('option').eq(i).text()).toLowerCase() + '.png); background-repeat: no-repeat;" href ="' + $(this).find('option').eq(i).val() + '">' + $(this).find('option').eq(i).text() + '</a></li>';
			divUl.append(text);
		}
	};
})(jQuery);