// Selecta Featured Video Slider
jQuery(document).ready(function() {

	// When page loads...
	jQuery('.featured-posts').css({
		visibility: 'visible'
	}); // Reveal the Featured Posts Container (was hidden in CSS to prevent FOUC)
	jQuery('.featured-posts div.featured-post').hide(); // Hide all content
	jQuery('.feature-slider #featured-1').addClass('active').show(); // Activate first post
	jQuery('.featured-posts #featured-post-1').show(); // Show first post content

	// On Click Event
	jQuery('.feature-slider li').click(function() {

		jQuery('.feature-slider li').removeClass('active'); // Remove any "active" class
		jQuery(this).addClass('active'); // Add "active" class to selected post
		jQuery('.featured-posts div.featured-post').hide(); // Hide all post content

		var activePost = jQuery(this).find('a').attr('href'); // Find the href attribute value to identify the active post + content
		jQuery(activePost).fadeIn(); // Fade in the active post content
		return false;
	});

});