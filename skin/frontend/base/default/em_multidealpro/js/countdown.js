(function($) {
	$.fn.countdown = function(options, callback) {

		//custom 'this' selector
		//thisEl = $(this);

		//array of custom settings
		var settings = { 
			'date': null,
			'format': null
		};

		//append the settings array to options
		if(options) {
			$.extend(settings, options);
		}
		var interval = new Array($(this).length);
		var timeNow = new Array($(this).length);
		//main countdown function
		function countdown_proc(element,index) {
			
			var thisEl = element;
			
			if(typeof timeNow[index] == 'undefined'){
				timeNow[index] = em_deal_now;
			} else {
				timeNow[index] += 1000;
			}
			var eventDate = Date.parse(thisEl.parent().find('.time').html()) / 1000;
			//var currentDate = Math.floor($.now() / 1000);
			var currentDate = Math.floor(timeNow[index] / 1000);
			
			if(eventDate <= currentDate) {
				callback.call(this);
				clearInterval(interval[index]);
			}
			
			var seconds = eventDate - currentDate;
			
			var days = Math.floor(seconds / (60 * 60 * 24)); //calculate the number of days
			seconds -= days * 60 * 60 * 24; //update the seconds variable with no. of days removed
			
			var hours = Math.floor(seconds / (60 * 60));
			seconds -= hours * 60 * 60; //update the seconds variable with no. of hours removed
			
			var minutes = Math.floor(seconds / 60);
			seconds -= minutes * 60; //update the seconds variable with no. of minutes removed
			
			//conditional Ss
			if (days == 1) { thisEl.find(".timeRefDays").text("Ngày"); } else { thisEl.find(".timeRefDays").text("Ngày"); }
			if (hours == 1) { thisEl.find(".timeRefHours").text("Giờ"); } else { thisEl.find(".timeRefHours").text("Giờ"); }
			if (minutes == 1) { thisEl.find(".timeRefMinutes").text("Phút"); } else { thisEl.find(".timeRefMinutes").text("Phút"); }
			if (seconds == 1) { thisEl.find(".timeRefSeconds").text("Giây"); } else { thisEl.find(".timeRefSeconds").text("Giây"); }
			
			//logic for the two_digits ON setting
			if(settings['format'] == "on") {
				days = (String(days).length >= 2) ? days : "0" + days;
				hours = (String(hours).length >= 2) ? hours : "0" + hours;
				minutes = (String(minutes).length >= 2) ? minutes : "0" + minutes;
				seconds = (String(seconds).length >= 2) ? seconds : "0" + seconds;
			}
			
			//update the countdown's html values.
			if(!isNaN(eventDate)) {
				thisEl.find(".days").text(days);
				thisEl.find(".hours").text(hours);
				thisEl.find(".minutes").text(minutes);
				thisEl.find(".seconds").text(seconds);
			} else { 
				//alert("Invalid date. Here's an example: 12 Tuesday 2012 17:30:00");
				clearInterval(interval[index]); 
			}
		}
		
		//run the function
		$(this).each(function(index){
			var element = $(this);
			countdown_proc(element,index);
			interval[index] = setInterval(function(){
				countdown_proc(element,index);

			}, 1000);
		});
		
		//loop the function
	
		//interval = setInterval(countdown_proc, 1000);
		
	}
}) (jQuery);