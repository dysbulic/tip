jQuery(document).ready(function() {
	jQuery(".entry-info").hover(function() {
		jQuery(this).children('.data', this).stop().animate({opacity:1},200);
	},function() {
		jQuery(this).children('.data', this).stop().animate({opacity:0},200);
	});

	jQuery("#colophon .search").click(function(event) {
		event.preventDefault();
		jQuery("#searchform").slideToggle();
		if (jQuery("#s").val() == "") {
			jQuery("#s").val("Search");
			jQuery("#s").focus(function() {
				if (jQuery("#s").val() == "Search") {
					jQuery("#s").val("");
				}
			});
			jQuery("#s").blur(function() {
				if (jQuery("#s").val() == "") {
					jQuery("#s").val("Search");
				}
			});
		}
	});

	jQuery(".format-audio").hover(function() {
		jQuery(this).find(".cassette").removeClass("hidden");
	}, function() {
		jQuery(this).find(".cassette").addClass("hidden");
	});

	jQuery(".format-video").hover(function() {
		jQuery(this).find(".projector").removeClass("hidden");
	}, function() {
		jQuery(this).find(".projector").addClass("hidden");
	});
});