jQuery(document).ready( function($) {
	var is_active = false;
	var active_load_state = $("#active").is(":checked");

	$( 'input,textarea,select' ).prop( 'disabled', false );

	$(':input').on('change', function() {
		is_active = true;
	});

	// Don't warn when submitting a form!
	$("form").submit(function() {
		$(window).unbind( "beforeunload" );
	});

	$(window).bind('beforeunload', function() {
		if (is_active || (active_load_state !== $("#active").is(":checked")))
			return "The changes you made will be lost if you navigate away from this page.";
	});

	$("#active").iphoneStyle({
		checkedLabel: 'ACTIVE',
		uncheckedLabel: 'OFF'
	});

	$( "#clear" ).live('click', function() {
		if (confirm("Are you sure you want to clear your settings and return to defaults?")) {
			// If the user confirms, don't warn about navigating away!
			$(window).unbind("beforeunload");
		} else {
			return false;
		}
	});

	$(document).on( 'click', "input[id^='clear-']", function() {

		var radionid = '#no-' + $(this).data( 'input-key' );

		$checked = $(this).prop( "checked" );

		if ( true !== $checked ) {

			$( radionid ).checked;

			$(this).parent().find( '.active').removeClass("active");

			$( '#' + $(this).data( 'input-key' ) + '-list' ).slideUp();
		} else {
			$( radionid ).checked;

			$( '#' + $(this).data( 'input-key' ) + '-list' ).slideDown();
		}

	});

	$("#launchdate").datetimepicker({
		dateFormat: "yy/mm/dd"
	});

	$(document).on("click", ".default-header", function() {

		$(this).parent().children(".active").removeClass("active");
		$(this).addClass("active");
	});

	$(".home-page-order" ).sortable({
		over: function(event, ui) {
			$(this).children().css({cursor: 'move'});
		},
		stop: function() {
			$(this).children().css({cursor: ''});
		}
	});
}); // document.ready
