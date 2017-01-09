/**
 * Galathemes
 *
 * @license commercial software
 * @copyright (c) 2014 Codespot Software JSC - Galathemes.com. (http://www.galathemes.com)
 */
var timeout = null;
var touch = false;
var animate = false;
(function($) {
	if (typeof EM == 'undefined') EM = {};
	if (typeof EM.tools == 'undefined') EM.tools = {};
	var domLoaded = false;
	var windowLoaded = false;
	/**
	 *   Add class mobile
	 **/

	function addClassMobile() {
		if (isMobile) {
			$('body').addClass('mobile-view');
		}
	};
	/**
	 * Fix iPhone/iPod auto zoom-in when text fields, select boxes are focus
	 */

	function fixIPhoneAutoZoomWhenFocus() {
		var viewport = $('head meta[name=viewport]');
		if (viewport.length == 0) {
			$('head').append('<meta name="viewport" content="width=device-width, initial-scale=1.0"/>');
			viewport = $('head meta[name=viewport]');
		}
		var old_content = viewport.attr('content');

		function zoomDisable() {
			viewport.attr('content', old_content + ', user-scalable=0');
		}

		function zoomEnable() {
			viewport.attr('content', old_content);
		}
		$("input[type=text], textarea, select").mouseover(zoomDisable).mousedown(zoomEnable);
	}; /* hover language currency */

	function hoverUl() {
		if (!isMobile) {
			$('#select-language').each(function() {
				$(this).insertUlLanguage();
				$(this).selectUl();
			});
			$('.currency').each(function() {
				$(this).insertUl();
				$(this).selectUl();
			});
			$('#select-store').each(function() {
				$(this).insertUl();
				$(this).selectUl();
			});
		}
	}; /* Menu Left */

	function doMenuLeft() {
		var wi = $(window).width();
		$('.all_categories').attr('id', 'menuleftText');
		$('#menu-default').css('display', 'none');
		var container = $("#menu-default");
		if (disableResponsive != 1) {
			if ((!isPhone) && (wi > 767)) {
				$("#menuleftText").unbind("click");
				$(".menu-wrapper").unbind('hover');
				//container.show();
				container.hide();
				$(".menu-wrapper").hover(

				function() {
					container.fadeIn(500);
					$("#menuleftText").toggleClass('hidden-arrow');
					$("#menuleftText").attr('title', 'hide-option');
				}, function() {
					container.fadeOut(100);
					$("#menuleftText").removeClass('hidden-arrow');
					$("#menuleftText").attr('title', 'show-option');
				});
			} else {
				$("#menu-default").css("display", "none");
				$(".menu-wrapper").unbind('hover');
				$("#menuleftText").unbind("click");
				$("#menuleftText").removeClass('hidden-arrow');
				$("#menuleftText").attr('title', 'show-option');
				$("#menuleftText").click(

				function(event) {
					event.preventDefault();
					if (container.is(":visible")) {
						container.hide();
						$("#menuleftText").removeClass('hidden-arrow');
						$("#menuleftText").attr('title', 'show-option');
					} else {
						container.show();
						$("#menuleftText").toggleClass('hidden-arrow');
						$("#menuleftText").attr('title', 'hide-option');
					}
				});
			}
		} else {
            if(!isMobile){
    			$("#menuleftText").unbind("click");
    			$(".menu-wrapper").unbind('hover');
    			//container.show();
    			container.hide();
    			$(".menu-wrapper").hover(
    
    			function() {
    				container.fadeIn(500);
    				$("#menuleftText").toggleClass('hidden-arrow');
    				$("#menuleftText").attr('title', 'hide-option');
    			}, function() {
    				container.fadeOut(100);
    				$("#menuleftText").removeClass('hidden-arrow');
    				$("#menuleftText").attr('title', 'show-option');
    			});
            }else{
                $("#menuleftText").click(

				function(event) {
					event.preventDefault();
					if (container.is(":visible")) {
						container.hide();
						$("#menuleftText").removeClass('hidden-arrow');
						$("#menuleftText").attr('title', 'show-option');
					} else {
						container.show();
						$("#menuleftText").toggleClass('hidden-arrow');
						$("#menuleftText").attr('title', 'hide-option');
					}
				});
            }
		}
	};

	function persistentMenu() {
		if (FREEZED_TOP_MENU != 0 && !isPhone) {
			var sticky_navigation = function() {
				var scroll_top = $(window).scrollTop();
				var navpos = $('#navpos').offset().top;
				if ($(window).width() > 767) {
					if (scroll_top > navpos) {
						$('.header-bottom-right').addClass('navbar-fixed-top');
					} else {
						$('.header-bottom-right').removeClass('navbar-fixed-top');
					}
				} else {
					$('.header-bottom-right').removeClass('navbar-fixed-top');
				}
			};
			$(window).scroll(function() {
				sticky_navigation();
			});
		}
		if ($(window).width() <= 767) {
			$('.header-bottom-right').removeClass('navbar-fixed-top');
		}
	};
    
    function freezedMenuNoResponsive(){
        if (FREEZED_TOP_MENU != 0 && !isPhone) {
			var sticky_navigation = function() {
				var scroll_top = $(window).scrollTop();
				var navpos = $('#navpos').offset().top;
				if (scroll_top > navpos) {
					$('.header-bottom-right').addClass('navbar-fixed-top');
				} else {
					$('.header-bottom-right').removeClass('navbar-fixed-top');
				}
			};
			$(window).scroll(function() {
				sticky_navigation();
			});
		}
		if (isMobile) {
			$('.header-bottom-right').removeClass('navbar-fixed-top');
		}
    };
	// Back to top

	function backToTop() {
		// hide #back-top first
		$("#back-top").hide();
		// fade in #back-top
		if (!isMobile) {
			$(window).scroll(function() {
				if ($(this).scrollTop() > 100) {
					$('#back-top').fadeIn();
				} else {
					$('#back-top').fadeOut();
				}
			});
			// scroll body to 0px on click
			$('#back-top a').click(function() {
				$('body,html').animate({
					scrollTop: 0
				}, 800);
				return false;
			});
		}
	};
	/**
	 Tab On Details Page
	 */

	function decorateProductCollateralTabs() {
		if ($('.box-collateral').length > 1) {
			$('.product-collateral').each(function(i) {
				$(this).wrap('<div class="tabs_wrapper_detail collateral_wrapper" />');
				$(this).prepend('<ul class="tabs_control"></ul>');
				$(this).children(".product-collateral-item").addClass("ui-slider-tabs-content-container");
				$('.box-collateral', this).addClass('tab-item').each(function(j) {
					var id = 'box_collateral_' + i + '_' + j;
					$(this).addClass('content_' + id);
					$(this).attr('id', id);
					$('.tabs_wrapper_detail ul.tabs_control').append('<li><a href="#' + id + '">' + $('h2', this).html() + '</a></li>');
				});
				$("div.tabs_wrapper_detail .product-collateral").sliderTabs();
			});
		}
	};
	/**
	 *   showReviewTab
	 **/

	function showReviewList() {
		if (jQuery('#customer_review_list').size()) {
			// scroll to customer review
			jQuery('html, body').animate({
				scrollTop: jQuery('#customer_review_list').offset().top
			}, 500);
		} else {
			return false;
		}
		return true;
	};

	function showReviewForm() {
		if (jQuery('#customer_review_form').size()) {
			// scroll to customer review
			jQuery('html, body').animate({
				scrollTop: jQuery('#customer_review_form').offset().top
			}, 500);
		} else {
			return false;
		}
		return true;
	};

	function setupReviewLink() {
		jQuery('.product-view .product-essential .link_review_list').click(function(e) {
			if (showReviewList()) {
				e.preventDefault();
			}
		});
		jQuery('.product-view .product-essential .link_review_form').click(function(e) {
			if (showReviewForm()) {
				e.preventDefault();
			}
		});
	};
	/**
	 Do Slider
	 */

	function doSlider($e, $move, $circular, $direction) {
		if ($($e + ' ul > li').size() > 1) {
			$($e + ' > ul').addClass('slides');
			$($e).csslider({
				move: $move,
				circular: $circular,
				direction: $direction,
				parentHidden: 'div.slider'
			});
		}
	}; /* Store Switcher */

	function toogleStore() {
		if (!isMobile) {
			doSlider('#slider_storeview', 1, 0, 'horizontal');
			$('.storediv').hide();
			$(".btn_storeview").click(function() {
				store_show();
			});
			$(".btn_storeclose").click(function() {
				store_hide();
			});

			function store_show() {
				var bg = $("#bg_fade_color");
				bg.css("opacity", 0.5);
				bg.css("display", "block");
				var top = ($(window).height() - $(".storediv").height()) / 2;
				var left = ($(window).width() - $(".storediv").width()) / 2;
				$(".storediv").show();
				$(".storediv").css('top', top + 'px');
				$(".storediv").css('left', left + 'px');
			};

			function store_hide() {
				var bg = $("#bg_fade_color");
				$(".storediv").hide();
				bg.css("opacity", 0);
				bg.css("display", "none");
			};
		}
	};

	function qrCode() {
		$('#qr_code .qr_code_name a').mouseover(function(e) {
			var $img = $('img', this);
			$img.css({
				'display': 'block'
			}).attr('src', $img.data('originalImg'));
			var parent = $img.offsetParent().offset();
			//var left = e.pageX - parent.left + 15;
			var top = e.pageY - parent.top + 15;
			if ((parent.top + top + $img.height()) > ($(document).scrollTop() + $(window).height())) top = $(document).scrollTop() + $(window).height() - $img.height() - parent.top;
			if ((parent.top + top) < $(document).scrollTop()) top = $(document).scrollTop() - parent.top;
			$img.css({
				'left': 48 + 'px',
				'position': 'absolute',
				'top': 0 + 'px',
				'width': 'auto',
				'height': 'auto'
			}).attr('src', $img.data('originalImg'));
		});
		$('#qr_code .qr_code_name a').mouseout(function(e) {
			var $img = $('img', this);
			$img.css({
				'display': 'none'
			}).attr('src', $img.data('originalImg'));
		});
	};
	/**
	 * Adjust elements to make it responsive
	 */

	function responsive() {
		var position = $('.products-grid .item').css('position');
		if (position != 'absolute' && position != 'fixed' && position != 'relative') $('.products-grid .item').css('position', 'relative');
	};
	/**
	 *   Toogle Footer Information Mobile View
	 **/

	function toogleFooter() {
		var wi = $(window).width();
		if (isPhone || (wi <= 767)) {
			$('.link_content > div > .content_links').css('display', 'none');
			$('.link_content > div > p.h5').addClass('toogle-icon');
			$('.link_content > div > p.h5').unbind('click');
			$('.link_content > div > p.h5').removeClass('active');
			$('.link_content > div > p.h5').on('click', function() {
				$(this).toggleClass("active").parent().find(".content_links").slideToggle();
			});
		} else {
			$('.link_content > div > p.h5').unbind('click');
			$('.link_content > div > p.h5').removeClass('toogle-icon');
			$('.link_content > div > p.h5').removeClass('active');
			$('.link_content > div > .content_links').css('display', 'block');
		}
	};

	function buildBootrapJs() {
		$('.link_connect a.icon').tooltip();
		$('.reset-button').click(function() {
			var btn = $(this);
			btn.button('loading');
			var tm;
			clearTimeout(tm);
			tm = setTimeout(function() {
				btn.button('reset');
			}, 1000);
		});
	};

	function dataAnimate() {
		if ($('[data-animate]')) {
			$('[data-animate]').each(function() {
				var $toAnimateElement = $(this);
				var toAnimateDelay = $(this).attr('data-delay');
				var toAnimateDelayTime = 0;
				if (toAnimateDelay) {
					toAnimateDelayTime = Number(toAnimateDelay);
				} else {
					toAnimateDelayTime = 200;
				}
				if (!$toAnimateElement.hasClass('animated')) { /*$toAnimateElement.addClass('not-animated');*/
					var elementAnimation = $toAnimateElement.attr('data-animate');
					setTimeout(function() {
						$toAnimateElement.waypoint({
							handler: function() {
								$toAnimateElement.removeClass('not-animated').addClass(elementAnimation + ' animated');
							},
							offset: '100%',
							triggerOnce: false
						});
					}, toAnimateDelayTime);
				}
				if ($toAnimateElement.hasClass('not-animated')) animate = false;
			});
		}
	};

	function ieVersion() {
		var rv = -1; // Return value assumes failure.
		if (navigator.appName == 'Microsoft Internet Explorer') {
			var ua = navigator.userAgent;
			var re = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
			if (re.exec(ua) != null) rv = parseFloat(RegExp.$1);
		}
		return rv;
	};

	function doAnimate() { /* DETECT PLATFORM */
		$.support.touch = 'ontouchend' in document;
		if ($.support.touch) {
			touch = true;
			$('body').addClass('touch');
		} else {
			$('body').addClass('notouch');
		}
		if (touch == false && ANIMATION_LOADING == 0) {
			if (ieVersion() == -1 || ieVersion() > 9) $('[data-animate]').addClass('not-animated');
		}
	};

	function setAnimate() {
		$('.header-top').children('div').each(function() {
			$(this).children('div').each(function() {
				if (!$(this).hasClass('clear') && !$(this).hasClass('bkg-hover')) $(this).attr('data-animate', animate_header);
			});
		});
		$('.em_col_left').children('div').each(function() {
			$(this).children('div').each(function() {
				if (!$(this).hasClass('clear') && !$(this).hasClass('bkg-hover')) $(this).attr('data-animate', animate_left);
			});
		});
		$('.em_col_right').children('div').each(function() {
			$(this).children('div').each(function() {
				if (!$(this).hasClass('clear') && !$(this).hasClass('bkg-hover')) $(this).attr('data-animate', animate_right);
			});
		});
		$('.em_col_main, .em_area03').children('div').each(function() {
			$(this).children('div').each(function() {
				if (!$(this).hasClass('clear') && !$(this).hasClass('bkg-hover')) $(this).attr('data-animate', animate_main);
			});
		});
		$('.em_area04, .em_area05, .inner_footer, #footer-information').children('div').each(function() {
			$(this).children('div').each(function() {
				if (!$(this).hasClass('clear') && !$(this).hasClass('bkg-hover')) $(this).attr('data-animate', animate_footer);
			});
		});
	};

	function fixClickMobile() {
		var currentName;
		if (isMobile) {
			$('.products-grid li.item a').each(function() {
				$(this).bind('click', function(event) {
					if (currentName != this) {
						event.preventDefault();
						// next time click this element will open link
						currentName = this;
					}
				});
			});
		}
	};

	function colorVariation() {
		var screen = "<div id='bg_fade_color'></div>";
		$(document.body).append(screen);
		$("#bg_fade_color").css("opacity", 0);
		$("#bg_fade_color").css("display", "none");
		$(".btn_color_variation").click(function() {
			var bg = $("#bg_fade_color");
			bg.css("opacity", 0.5);
			bg.css("display", "block");
			var left = ($(window).width() - $(".colordiv").width()) / 2;
			$(".colordiv").show();
			$(".colordiv").css('top', Math.max($(document).scrollTop(), Math.min($(this).offset().top, $(document).scrollTop() + $(window).height() - $(".colordiv").outerHeight())) + 20 + 'px');
			$(".colordiv").css('left', left);
		});
		$(".btn_color_close").click(function() {
			color_hide();
		});

		function color_hide() {
			var bg = $("#bg_fade_color");
			$(".colordiv").hide();
			bg.css("opacity", 0);
			bg.css("display", "none");
		}
	};
	/**
	 Ready Function
	 */
	$(document).ready(function() {
		domLoaded = true;
		setAnimate();
		addClassMobile();
		if (disableVariation != 1) {
			colorVariation();
			qrCode();
			toogleStore();
		}
		// safari hack: remove bold in h5, .h5
		if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1) {
			$('h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6').css('font-weight', 'normal');
		}
        if (disableResponsive != 1) {
            isMobile && fixIPhoneAutoZoomWhenFocus();
        }else{
            freezedMenuNoResponsive();
        }
		hoverUl();
		toolbarCategoty();
		backToTop();
		doMenuLeft();
		//hoverTopCart();
		alternativeProductImage();
		if (useTab) {
			decorateProductCollateralTabs();
		}
		setupReviewLink();
		doSlider('#upsell-product-table', 1, 0, 'horizontal');
		doSlider('#slider_crosell', 1, 0, 'horizontal');
		doSlider('#slider_moreview', 1, 0, 'horizontal');
		doSlider('#slider_related', 1, 0, 'horizontal');
		responsive();
		buildBootrapJs();
		doAnimate();
		//fixClickMobile();
		setupReviewLink();
        if (disableResponsive != 1) {
			toogleFooter();
		}        
	});
	/**
	 Load Function
	 */
	$(window).bind('load', function() {
		windowLoaded = true;
		$.support.touch = 'ontouchend' in document;
		if ($.support.touch) {
			touch = true;
			$('body').addClass('touch');
		} else {
			$('body').addClass('notouch');
		}
		if (touch == false && ANIMATION_LOADING == 0) {
			if (ieVersion() == -1 || ieVersion() > 9) {
				dataAnimate();
			}
		}
        if (ieVersion() != -1 || ieVersion() < 9){
            setTimeout(function() {                
				if (typeof em_slider !== 'undefined') {
					em_slider.reinit();
				}
            },8000);
        }
	});
	var tmresize;
	$(window).resize(function() {
		if (disableResponsive != 1) {
			clearTimeout(tmresize);
			tmresize = setTimeout(function() {                
				doMenuLeft();
				persistentMenu();
				toogleFooter();
				if (typeof em_slider !== 'undefined') {
					em_slider.reinit();
				}
			}, 300);
			if (touch == false && ANIMATION_LOADING == 0) {
				if (ieVersion() == -1 || ieVersion() > 9) dataAnimate();
			}
		}
	});
	$(window).bind('orientationchange', function() {
		if (disableResponsive != 1) {
			toogleFooter();
		}
	});
})(jQuery);
/**
 * Change the alternative product image when hover
 */

function alternativeProductImage() {
	var $ = jQuery;
	var tm;

	function swap() {
		clearTimeout(tm);
		tm = setTimeout(function() {
			el = $(this).find('img[data-alt-src]');
			var newImg = $(el).data('alt-src');
			var oldImg = $(el).attr('src');
			$(el).attr('src', newImg).data('alt-src', oldImg);
		}.bind(this), 200);
	}
	$('.item .product-image img[data-alt-src]').parents('.item').bind('mouseenter', swap).bind('mouseleave', swap);
};

function toolbarCategoty() {
	var $ = jQuery;
	if (!isMobile) {
		$('.toolbar-show').each(function() {
			$(this).insertUl();
			$(this).selectUl();
		});
		$('.sortby').each(function() {
			$(this).insertUl();
			$(this).selectUl();
		});
	}
};
/**
 *   After Layer Update
 **/
window.afterLayerUpdate = function() {
	var $ = jQuery;
	toolbarCategoty();
	alternativeProductImage();
	if ((typeof EM_QUICKSHOP_DISABLED == 'undefined' || !EM_QUICKSHOP_DISABLED) && !isMobile) {
		qs({
			itemClass: '.products-grid li.item, .products-list li.item',
			//selector for each items in catalog product list,use to insert quickshop image
			aClass: 'a.product-image',
			//selector for each a tag in product items,give us href for one product
			imgClass: '.product-image > img' //class for quickshop href
		});
	}
};

function hideAgreementPopup(e) {
	jQuery('#checkout-agreements .agreement-content').hide();
};

function showAgreementPopup(e) {
	jQuery('#checkout-agreements label.a-click').parent().parent().children('.agreement-content').show().css({
		'left': (parseInt(document.viewport.getWidth()) - jQuery('#checkout-agreements label.a-click').parent().parent().children('.agreement-content').width()) / 2 + 'px',
		'top': (parseInt(document.viewport.getHeight()) - jQuery('#checkout-agreements label.a-click').parent().parent().children('.agreement-content').height()) / 2 + 'px'
	});
};
