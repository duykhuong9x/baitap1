/**
 * EM MegaMenuPro
 *
 * @license commercial software
 * @copyright (c) 2012 Codespot Software JSC - EMThemes.com. (http://www.emthemes.com)
 */
(function($) {
	function isMobileView() {
		return $(window).width() <= 767;
	};
	/**
	 * Make menu support on mobile
	 */

	function mobile() {
		$('.em_nav').each(function(i) {
			var $nav = $('.hnav, .vnav', this);
			// prepend a.arrow into parent LI 
			var timeout = null;
			// bind event when click on a.arrow sliding to the sub menu horizontally
            $('a.arrow', $nav).bind(isWindowPhone ? 'mouseenter' : (isMobile ? 'click mouseenter' : 'click'), function(event) {
			//$('a.arrow', $nav).bind(isMobile ? 'click mouseenter' : 'click', function(event) {
				if (!isMobileView()) return;
				event.preventDefault();
				event.stopPropagation();
				var $li = $(this.parentNode);
				if (timeout) {
					clearTimeout(timeout);
				}
				if (!$li.hasClass('cur-toggle') && $('li.cur-toggle', $nav).length > 0 && !($li.parents('li.cur-toggle').length > 0)) {
					$('li.cur-toggle', $nav).each(function() {
						$(this).removeClass('cur-toggle');
						if ($(this).css('display') != 'none') {
							$(this).children('ul').slideToggle();
						}
					});
				}
				// fix bug event called twice cause menu sub menu showed even not clicked
				// don't know why it happens!!!
				$li.children('ul').slideToggle();
				if ($li.hasClass('cur-toggle')) $li.removeClass('cur-toggle');
				else if ($li.parents('li.cur-toggle').length == 0) $li.addClass('cur-toggle');
			});
		});
	};

	function fixPc() {
		if ($(window).width() > 767) {
			$('.em_nav').each(function(i) {
				var $nav = $('.hnav, .vnav', this);
				//$('a.arrow', $nav).unbind(isMobile ? 'click mouseenter' : 'click');
				var $li = $(this.parentNode);
				$('li', $nav).each(function() {
					$(this).children('ul').css('display', '');
				});
			});
		}
	};
	/**
	 * Fix mega menu drop-down's container overflows the right edge of page.
	 *
	 * Should be called once when document ready
	 */

	function fixMegaMenuOverflow() {
		function fix($container, $nav) {
			var pad = $nav.offset().left + $nav.outerWidth() - ($container.offset().left + $container.outerWidth());
			var pad2 = $container.offset().left + pad - $nav.offset().left;
			if (pad2 < 0) pad = pad - pad2;
			if (pad < 0) {
				$container.css('left', pad + 'px');
			}
		};
		$('.em_nav > .hnav > .menu-item-link > .menu-container').parent().hover(function() {
			var $container = $(this).children('.menu-container');
			if ($(this).hasClass('menu-item-depth-0')) $container.css('left', 0);
			var $nav = $(this).parents('.fix_menu').first();
			fix($container, $nav);
		}, function() {
			$(this).children('.menu-container').css('left', '');
		});
	};

	function menuVertical() {
		if (!isPhone && $(window).width() > 767) {
			if ($('.vnav > .menu-item-link > .menu-container > li.fix-top').length > 0) {
				$('.vnav > .menu-item-link > .menu-container > li.fix-top').parent().parent().mouseover(function() {
					var $container = $(this).children('.menu-container,ul.level0');
					var $containerHeight = $container.outerHeight();
					var $containerTop = $container.offset().top;
					var $winHeight = $(window).height();
					var $maxHeight = $containerHeight + $containerTop;
					$setTop = $(this).parent().offset().top - $(this).offset().top;
					if (($setTop + $containerHeight) < $(this).height()) {
						$setTop = $(this).outerHeight() - $containerHeight;
					}
					var $grid = $(this).parents('.em_nav').first().parents().first();
					$container.css('top', $setTop);
					if ($maxHeight < $winHeight) {
						$('.vnav ul.level0,.vnav > .menu-item-link').children('.fix-top').parent('.menu-container').first().css('top', $setTop - 9 + 'px');
					}
				});
				$('.vnav ul.level0,.vnav > .menu-item-link').children('.fix-top').parent('.menu-container').parent().mouseout(function() {
					var $container = $(this).children('.menu-container,ul.level0');
					$container.removeAttr('style');
				});
			}
		} else {
			$('.vnav > .menu-item-link > .menu-container > li.fix-top').parent().parent().off('mouseover mouseon');
			$('.vnav > .menu-item-link > .menu-container > li.fix-top').parent().off('mouseover mouseon');
			$('.vnav .menu-item-link > .menu-container,.vnav ul.level0').parent().off('mouseover mouseon');
		}
	};
	var tm;
	$(document).ready(function() {
		$('.em_nav').each(function(i) {
			var $nav = $('.hnav, .vnav', this);
			$('.em-catalog-navigation li.parent, .menu-item-link.menu-item-parent', $nav).each(function() {
				$(this).prepend('<a href="javascript:void(0)" class="arrow"><span>&gt;</span></a>');
			});
		});
		menuVertical();
		clearTimeout(tm);
        if(disableResponsive!=1){
            tm = setTimeout(function() {
    			mobile();
    		}, 100);
        }		
		fixMegaMenuOverflow();
	});
    
	var temp;
	$(window).resize(function() {
	   if(disableResponsive!=1){
    		menuVertical();
    		clearTimeout(temp);
    		temp = setTimeout(function() {
    			fixPc();
    		}, 100);
    		fixMegaMenuOverflow();
        }
	});
    
})(jQuery);