// Mystique sidebar tabbed widget
jQuery(document).ready(function() {

	// When page loads...
	jQuery(".tab-content").hide(); // Hide all content
	jQuery("ul.sidebar-tabs li:first").addClass("active").show(); // Activate first tab
	jQuery(".tab-content:first").show(); // Show first tab content

	// On Click Event
	jQuery("ul.sidebar-tabs li").click(function() {

		jQuery("ul.sidebar-tabs li").removeClass("active"); // Remove any "active" class
		jQuery(this).addClass("active"); // Add "active" class to selected tab
		jQuery(".tab-content").hide(); // Hide all tab content

		var activeTab = jQuery(this).find("a").attr("href"); // Find the href attribute value to identify the active tab + content
		jQuery(activeTab).fadeIn(); // Fade in the active ID content
		return false;
	});

});