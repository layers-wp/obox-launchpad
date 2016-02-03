jQuery(function($){
	ts = Date.parse(launchpad.date_launch);
	now = Date.parse(launchpad.date_now);

	$().countdown({
		timestamp: ts,
		timenow: now,
		callback: function(days, hours, minutes, seconds){

			var message = "";
			$(".days strong").text(days);
			$(".hours strong").text(hours);
			$(".minutes strong").text(minutes);
			$(".hours strong").text(hours);
			$(".seconds strong").text(seconds);
			$(".days small").text("Day" + ( days==1 ? '':'s' ));
			$(".hours small").text("Hour" + ( hours==1 ? '':'s' ));
			$(".minutes small").text("Minute" + ( minutes==1 ? '':'s' ));
			$(".seconds small").text("Second" + ( seconds==1 ? '':'s' ));

			if( 0 >= ( +days + +hours + +minutes + +seconds ) && false == ( null == document.getElementById( "auto-launch" ) ) ){
				setTimeout(function(){
					location.reload();
				}, 1000 );
			}

		}
	});

});
