/**
 * Galathemes
 *
 * @license commercial software
 * @copyright (c) 2014 Codespot Software JSC - Galathemes.com. (http://www.galathemes.com)
 */
jQuery(function($) {
	var variables = {};
	var panel = $('#demotool_variation').css({
		'left': '-100%',
		'position': 'absolute'
	});
	$('a.btn-toggle').click(function() {
		if (!panel.hasClass('show')) {
			$('.variation-cp').show();
			panel.css('top', Math.max($(document).scrollTop(), Math.min($(this).offset().top, $(document).scrollTop() + $(window).height() - panel.outerHeight())) + 'px').addClass('show').animate({
				'left': $(this).width() + 1 + 'px'
			});
		} else {
			panel.removeClass('show').animate({
				'left': -$(this).width() - panel.outerWidth() - 10 + 'px'
			}, 500, function() {
				$('.variation-cp').hide();
			});
		}
		return false;
	});
	// toogle section content-title
	$('#demotool_variation .content-title').click(function() {
		$(this).next('.wrapper-content').slideToggle('fast');
	}).trigger('click').first().trigger('click');
	// toggle box content
	$('#demotool_variation .box .title').click(function() {
		var box = $(this).parent();
		if (box.children('.content').is(':visible')) {
			box.children('.content').slideUp('fast');
			box.addClass('close');
		} else {
			box.children('.content').slideDown('fast');
			box.removeClass('close');
		}
	});
	// load google fonts
	var fontLoaded = {};
	$('#em_variation_google_font').after('<p id="em_variation_google_font_preview" style="font-size:20px;padding:10px 0"></p>').bind('change', function() {
		var font = $(this).val();
		if (font.length > 0) {
			font = font[font.length - 1];
			if (typeof fontLoaded[font] == 'undefined') {
				$('head').append('<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=' + encodeURIComponent(font) + ':400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic&subset=latin,cyrillic-ext,cyrillic,greek-ext,greek,vietnamese,latin-ext"></link>');
				$('#em_variation_google_font_preview').html(font).css('font-family', font);
			}
		}
	}); 
    /* Box Wide Mode */
	$('#em_box_wide').change(function() {
		var mode = $('#em_box_wide').val();
		if (mode == 'box') {
			$('#emoption_wideslideshow').hide();
			$('#page_boxconfig').show();
			$('.wrapper').addClass('em-box-custom');
			$('.em_area02').parents('.menu_slideshow_container').removeClass('container-fluid').addClass('container');
			if ($('.em_area01').parent().is("div.container")) {
				$(".em_area01").unwrap("<div class='container'></div>");
			}
			$('.em_area02').removeClass('em-wide-custom');
		} else {
			$('.wrapper').removeClass('em-box-custom');
			$('#emoption_wideslideshow').show();
			$('#page_boxconfig').hide();
			if ($('#wide_full').is(':checked')) {
				$('.em_area02').parents('.menu_slideshow_container').removeClass('container').addClass('container-fluid');
				$(".em_area01").wrap("<div class='container'></div>");
				$('.em_area02').addClass('em-wide-custom');
			} else {
				$('.em_area02').parents('.menu_slideshow_container').removeClass('container-fluid').addClass('container');
				if ($('.em_area01').parent().is("div.container")) {
					$(".em_area01").unwrap("<div class='container'></div>");
				}
				$('.em_area02').removeClass('em-wide-custom');
			}
		}
	});
	$('#wide_full').change(function() {
		if ($('#wide_full').is(':checked')) {
			$('.em_area02').parents('.menu_slideshow_container').removeClass('container').addClass('container-fluid');
			$(".em_area01").wrap("<div class='container'></div>");
			$('.em_area02').addClass('em-wide-custom');
		} else {
			$('.em_area02').parents('.menu_slideshow_container').removeClass('container-fluid').addClass('container');
			if ($('.em_area01').parent().is("div.container")) {
				$(".em_area01").unwrap("<div class='container'></div>");
			}
			$('.em_area02').removeClass('em-wide-custom');
		}
	});
	// reset
	$('#demotool_variation .reset-button').click(function() {
		$('#demotool_variation input[type=text], #demotool_variation select, #demotool_variation input[type=hidden]').val('').css('background-color', '');
		resetVariation();
		//box wide
		$('#em_box_wide').val(boxwide_selected);
		var mode = $('#em_box_wide').val();
		if (mode == 'box') {
			$('#emoption_wideslideshow').hide();
			$('#page_boxconfig').show();
			$('.wrapper').addClass('em-box-custom');
			$('.em_area02').parents('.menu_slideshow_container').removeClass('container-fluid').addClass('container');
			if ($('.em_area01').parent().is("div.container")) {
				$(".em_area01").unwrap("<div class='container'></div>");
			}
			$('.em_area02').removeClass('em-wide-custom');
		} else {
			$('.wrapper').removeClass('em-box-custom');
			$('#emoption_wideslideshow').show();
			$('#page_boxconfig').hide();
			if ($('#wide_full').is(':checked')) {
				$('.em_area02').parents('.menu_slideshow_container').removeClass('container').addClass('container-fluid');
				$(".em_area01").wrap("<div class='container'></div>");
				$('.em_area02').addClass('em-wide-custom');
			} else {
				$('.em_area02').parents('.menu_slideshow_container').removeClass('container-fluid').addClass('container');
				if ($('.em_area01').parent().is("div.container")) {
					$(".em_area01").unwrap("<div class='container'></div>");
				}
				$('.em_area02').removeClass('em-wide-custom');
			}
		}
		// slideshow
		if (fullSlideshow) {
			$('#wide_full').prop('checked', true); // Uncheck the checkbox
		} else {
			$('#wide_full').prop('checked', false);
		}
		if ($('#wide_full').is(':checked')) {
			$('.em_area02').parents('.menu_slideshow_container').removeClass('container').addClass('container-fluid');
			$('.em_area02').addClass('em-wide-custom');
		} else {
			$('.em_area02').parents('.menu_slideshow_container').removeClass('container-fluid').addClass('container');
			$('.em_area02').removeClass('em-wide-custom');
		}
		return false;
	});
	// stripes pattern
	$('#demotool_variation a.page_bg_image').click(function() {
		$('#demotool_variation a.page_bg_image').removeClass('selected');
		$(this).addClass('selected');
		$('#demotool_variation input[name=page_bg_image]').val($(this).data('input-value'));
		changeVariation('@page_bg_image', $('#demotool_variation input[name=page_bg_image]').val());
		return false;
	});
	$('#demotool_variation a.header_bg_image').click(function() {
		$('#demotool_variation a.header_bg_image').removeClass('selected');
		$(this).addClass('selected');
		$('#demotool_variation input[name=header_bg_image]').val($(this).data('input-value'));
		changeVariation('@header_bg_image', $('#demotool_variation input[name=header_bg_image]').val());
		return false;
	});
	$('#demotool_variation a.body_bg_image').click(function() {
		$('#demotool_variation a.body_bg_image').removeClass('selected');
		$(this).addClass('selected');
		$('#demotool_variation input[name=body_bg_image]').val($(this).data('input-value'));
		changeVariation('@body_bg_image', $('#demotool_variation input[name=body_bg_image]').val());
		return false;
	});
	$('#demotool_variation a.footer_bg_image').click(function() {
		$('#demotool_variation a.footer_bg_image').removeClass('selected');
		$(this).addClass('selected');
		$('#demotool_variation input[name=footer_bg_image]').val($(this).data('input-value'));
		changeVariation('@footer_bg_image', $('#demotool_variation input[name=footer_bg_image]').val());
		return false;
	});
	changeVariation = function(key, value) {
		var key = key || null;
		var value = value || null;
		var temp = {};
		if (key) {
			if (String(value).indexOf(',') != -1 || String(value).indexOf('.') != -1 || String(value).indexOf('/') != -1 || String(value).indexOf(' ') != -1) variables[key] = '~"' + String(value.replace(/"/g, "'")) + '"';
			else
			variables[key] = String(value);
		}
		less.modifyVars(variables);
	}; /*return default value when input value is null*/
	returnDefault = function(key) {
		if (variables[key]) delete(variables[key]);
		less.modifyVars(variables);
	}; /*reset Variation*/
	resetVariation = function() {
		variables = {};
		less.refresh();
	};
	$('.color-picker').ColorPicker({
		onSubmit: $.throttle(300, function(hsb, hex, rgb, el) {
			$(el).val('#' + hex);
			$(el).css('backgroundColor', '#' + hex);
			//$(el).ColorPickerHide();
		}),
		onChange: $.debounce(300, function(hsb, hex) {
			var el = this.data('colorpicker').el;
			$(el).val('#' + hex);
			$(el).css('backgroundColor', '#' + hex);
			changeVariation('@' + $(el).attr('name'), $(el).val());
		}),
		onBeforeShow: function() {
			$(this).ColorPickerSetColor(this.value);
		}
	}).bind('keyup', $.debounce(300, function() {
		$(this).ColorPickerSetColor(this.value);
		if ($(this).val() == "") {
			$(this).css('background-color', '');
			returnDefault('@' + $(this).attr('name'));
		}
	}));
	$('.colorpicker_submit').click(function() {
		var btn = $(this);
		var tm;
		clearTimeout(tm);
		btn.button('loading');
		tm = setTimeout(function() {
			btn.button('reset');
		}, 1000);
	});
	$('#demotool_variation input.input-text').blur(function(e) {
		if ($(this).val() != "") {
			changeVariation('@' + $(this).attr('name'), $(this).val());
		}
	});
	$('#demotool_variation input.input-text').bind('keyup', function(e, hbs, hex) {
		if ($(this).val() == "") {
			returnDefault('@' + $(this).attr('name'));
		} else {
			if (e.keyCode == 13) {
				changeVariation('@' + $(this).attr('name'), $(this).val());
			}
		}
	});
	//box wide
	var mode = $('#em_box_wide').val();
	if (mode == 'box') {
		$('#emoption_wideslideshow').hide();
		$('#page_boxconfig').show();
		$('.wrapper').addClass('em-box-custom');
		$('.em_area02').parents('.menu_slideshow_container').removeClass('container-fluid').addClass('container');
		if ($('.em_area01').parent().is("div.container")) {
			$(".em_area01").unwrap("<div class='container'></div>");
		}
		$('.em_area02').removeClass('em-wide-custom');
	} else {
		$('.wrapper').removeClass('em-box-custom');
		$('#emoption_wideslideshow').show();
		$('#page_boxconfig').hide();
		if ($('#wide_full').is(':checked')) {
			$('.em_area02').parents('.menu_slideshow_container').removeClass('container').addClass('container-fluid');
			$(".em_area01").wrap("<div class='container'></div>");
			$('.em_area02').addClass('em-wide-custom');
		} else {
			$('.em_area02').parents('.menu_slideshow_container').removeClass('container-fluid').addClass('container');
			if ($('.em_area01').parent().is("div.container")) {
				$(".em_area01").unwrap("<div class='container'></div>");
			}
			$('.em_area02').removeClass('em-wide-custom');
		}
	}
});