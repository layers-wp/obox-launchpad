jQuery(function(){
	ts = new Date(date);

	jQuery().countdown({
		timestamp	: ts,
		callback	: function(days, hours, minutes, seconds){
			
			var message = "";
			jQuery(".days strong").text(days);
			jQuery(".hours strong").text(hours);
			jQuery(".minutes strong").text(minutes);
			jQuery(".hours strong").text(hours);
			jQuery(".seconds strong").text(seconds);
			jQuery(".days small").text("Day" + ( days==1 ? '':'s' ));
			jQuery(".hours small").text("Hour" + ( hours==1 ? '':'s' ));
			jQuery(".minutes small").text("Minute" + ( minutes==1 ? '':'s' ));
			jQuery(".seconds small").text("Second" + ( seconds==1 ? '':'s' ));
		}
	});
	
});
