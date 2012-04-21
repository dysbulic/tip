<?php
/**
 * @package WordPress
 * @subpackage Selecta
 */

/*
	If the Featured Post Slider is showing, use WP_Query to remove the post IDs of sticky posts, if any exist
	Normal (non-featured) sticky posts *should* be displayed in the filtered index list
*/
	// First let's check to see if we are showing the featured post slider
	$options = selecta_get_options();
	if ( 'no' == $options['theme_slider'] ) :
		// If we're not showing the slider, show the loop normally with all posts.
		while ( have_posts() ) : the_post();
			get_template_part( 'content', get_post_format() );
		endwhile; // end the loop
	else :
		// Let's filter out sticky posts from the posts list
		$filtered_args = array(
			'post__not_in' => get_option( 'sticky_posts' ),
			'paged' => $paged,
		);
		$filtered = new WP_Query();
		$filtered->query( $filtered_args );

		while ( $filtered->have_posts() ) : $filtered->the_post();
			get_template_part( 'content', get_post_format() );
		endwhile; // End the loop
	endif; // End check if Featured Post Slider display
?>

<?php selecta_content_nav( 'nav-below' ); ?>