function slideFrame(thumbid, direction, type, match_height) {
	/* Set the new position & frame number */
	move_by = jQuery(thumbid).parent().width();
	frame_left = jQuery(thumbid).css(type).replace("px", "");
	frame = (-(frame_left / move_by));
	maxsize = (jQuery(thumbid).children("li").size() / 3 - 1);

	if (direction === 0) {
		new_frame =  Math.round((frame / 1) + 1);
		if (jQuery.browser.msie)
			maxsize = (maxsize - 1);

		if (maxsize <= frame)
			new_frame = 0;
	} else {
		new_frame = Math.round((frame / 1) - 1);
		if (frame === 0) {
			new_frame = maxsize;
		}
	}

	new_left = -(new_frame * (move_by + 12)) + "px";
	jQuery(thumbid).animate({"left": new_left}, {duration: 500});
} // slideFrame

jQuery(document).ready(function() {
	var is_dirty = false;
	var active_load_state = jQuery("#active").is(":checked");

	jQuery(':input').on('change', function() {
		is_dirty = true;
	});

	// Don't warn when submitting a form!
	jQuery("form").submit(function() {
		jQuery(window).unbind("beforeunload");
	});

	jQuery(window).bind('beforeunload', function() {
		if (is_dirty || (active_load_state !== jQuery("#active").is(":checked")))
			return "The changes you made will be lost if you navigate away from this page.";
	});

	jQuery("#active").iphoneStyle({
		checkedLabel: 'ACTIVE',
		uncheckedLabel: 'OFF'
	});

	jQuery("#clear").live('click', function() {
		if (confirm("Are you sure you want to clear your settings and return to defaults?")) {
			// If the user confirms, don't warn about navigating away!
			jQuery(window).unbind("beforeunload");
		} else {
			return false;
		}
	});

	jQuery("input[id^='clear-']").live("change", function() {
		radionid = jQuery(this).attr("id").replace("clear-", "no-");

		if (jQuery(this).attr("checked") !== "checked") {
			jQuery("#" + radionid).eq(0).attr("checked", "checked");
			jQuery(this).parent().find( '.active').removeClass("active");
			jQuery(this).parent().find( 'div.no_display').slideUp();
		} else {
			jQuery("#"+radionid).eq(0).attr("checked", "");
			jQuery(this).parent().find( 'div.no_display').slideDown();
		}
	});

	jQuery("#launchdate").datetimepicker({
		dateFormat: "yy/mm/dd"
	});

	jQuery(".next").live("click", function() {
		elem = jQuery(this).parent().parent().children(".available-headers");
		slideFrame(elem.children("ul"), 0, "left", false);
		return false;
	});

	jQuery(".prev").live("click", function() {
		elem = jQuery(this).parent().parent().children(".available-headers");
		slideFrame(elem.children("ul"), 1, "left", false);
		return false;
	});

	jQuery(".default-header").live("click", function() {
		jQuery(this).parent().children(".active").removeClass("active");
		jQuery(this).addClass("active");
	});

	jQuery(".home-page-order" ).sortable({
		over: function(event, ui) {
			jQuery(this).children().css({cursor: 'move'});
		},
		stop: function() {
			jQuery(this).children().css({cursor: ''});
		}
	});
}); // document.ready
