p2_instapost_overrides = {
	init: function(jq) {
		// Don't load in published post as instapost handles this.
		jq(document).bind( 'published_post_rendered.ipt', function() {
			toggleUpdates('unewposts');
			post = jq( 'ul#postlist li:first-child' );
			postsOnPageQS+= "&vp[]=" + instapost.post_id;

			bindActions( post, 'post' );
			localizeMicroformatDates(post);
			
			setTimeout( function() {
				toggleUpdates('unewposts');
			}, 10000 );
		});
	}
}
jQuery( function() { p2_instapost_overrides.init( jQuery ); } );
