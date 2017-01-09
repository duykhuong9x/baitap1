/*
	Version : 1.0.0
	date create : 20-10-2013
*/
jQuery(document).ready(function($) {

	var pid		=	$("#product_id").val();
	var pname	=	$("#product_name").val();
	if(pid != ''){
		$("#multidealpro_tabs_form_general_content .show_name").html(pname).show();
		$("#multidealpro_tabs_form_general_content .redir_p").hide();
	}

	$( "#multidealpro_tabs_form_general_content .redir_p" ).click(function() {

		$( "#multidealpro_tabs_form_general" ).removeClass("active");
		$( "#multidealpro_tabs_products" ).addClass("active");

		$( "#multidealpro_tabs_form_general_content" ).hide();
		$( "#multidealpro_tabs_products_content" ).show();
	});

});

	function radioclick(element){
		var radio = jQuery(element);
		var td 	= radio.parents('tr');
		var name = td.children('.em_name');

		jQuery("#product_id").show();
		jQuery("#product_name").show();
		jQuery("#product_id").val(radio.val());
		jQuery("#product_name").val(name.html());
		jQuery("#product_id").hide();
		jQuery("#product_name").hide();
		jQuery("#multidealpro_tabs_form_general_content .show_name").html(name.html()).show();
		jQuery("#multidealpro_tabs_form_general_content .redir_p").hide();

	}