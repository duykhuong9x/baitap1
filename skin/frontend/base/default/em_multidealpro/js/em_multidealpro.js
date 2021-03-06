//jQuery(document).ready(function() {
jQuery( window ).load(function() {
	var $ = jQuery;
	
	if($(".show_clock").length > 0){
		$( ".show_clock").each(function( index ) {
			var div = $(this).parent();

			eventClock(div);
		});
	}
	
	function eventClock(div){
		var clock = div.find(".clock");
		var time  = div.find(".time").html();
		var deal_id  = div.find(".deal_data").html();

		var root = div.parents("li");
		if(root.length <= 0)	root = div.parent();
		var info = $.parseJSON(root.find('.deal_info').html());

		if(clock)
		clock.countdown({
				format: "on"
		},
		function() {
			// callback function
			var request = $.ajax({
				url: em_deal_baseurl+'multidealpro/index/edit/',
				type: "POST",
				data: { id : deal_id },
				dataType: "json"
			});

			request.done(function( data ) {
				if(info.type == 1){ // use for main list and recent
					if(data.check == 1){
						if(info.label == 1)		root.find('.sale_off').replaceWith(data.label);
						if(info.price == 1)		div.find('.price-box').replaceWith(data.price);
						div.find('.deal_qty').replaceWith(data.qty);
						div.find('.show_clock').replaceWith(data.clock);
						if(info.cart == 1){
							if(div.find('.add-to-links').length > 0)
							$(data.btn_cart).insertBefore(div.find('.add-to-links'));
						}

						eventClock(div);
					}

					if(data.check == 2){
						div.find('.deal_qty').hide();
						div.find('.show_clock').hide();
						div.find('.btn-cart').hide();
						if(div.find('.add-to-links').length > 0)
							$(data.html).insertBefore(div.find('.add-to-links'));
						else
							div.append(data.html);
					}
				}

				if(info.type == 2){ // use for short include
					if(data.check == 1){
						root.append(data.label);
						if(info.price == 1)		root.find('.price-box').replaceWith(data.price);
						div.find('.deal_qty').replaceWith(data.qty);
						div.find('.show_clock').replaceWith(data.clock);

						eventClock(div);
					}
					if(data.check == 2){
						div.find('.deal_qty').hide();
						div.find('.show_clock').hide();
						root.find('.btn-cart').hide();
						div.append(data.html);
					}
				}

				if(info.type == 3){ // use for details page
					if(data.check == 1){
						root.find('.price-box').replaceWith(data.price);
						root.find('.deal_qty').replaceWith(data.qty);
						root.find('.show_clock').replaceWith(data.clock);

						root.find('.show_details span').show();
						root.find('.show_details span.qty_left').hide();

						eventClock(div);
					}
					if(data.check == 2){
						root.find('.title').hide();
						root.find('.deal_qty').hide();
						root.find('.show_clock').hide();
						root.find('.add-to-cart').hide();
						div.append(data.html);
					}
				}

			});
		});

	}

});