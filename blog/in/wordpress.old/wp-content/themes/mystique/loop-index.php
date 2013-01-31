<?php
/**
 * @package WordPress
 * @subpackage Mystique
 */

/*
	Use WP_Query to remove the post ID of the featured post, if it exists
	Normal (non-featured) sticky posts *should* be displayed in the filtered index list
*/
	$featured_post = mystique_get_featured_post();
	if ( !empty( $featured_post ) ) {

		// Let's filter out the featured post from the posts list
		$filtered_args = array(
			'post__not_in' => (array) $featured_post,
			'paged' => $paged,
		);
		$filtered = new WP_Query();
		$filtered->query( $filtered_args );

		while ( $filtered->have_posts() ) : $filtered->the_post();
			get_template_part( 'content', get_post_format() );
		endwhile; // End the loop

	} else {

		// No featured post, so show the normal post list
		while ( have_posts() ) : the_post();
			get_template_part( 'content', get_post_format() );
		endwhile; // End the loop

	}
?>

<?php if ( $wp_query->max_num_pages > 1 ) : ?>
	<div class="post-navigation">
		<div class="nav-previous"><?php next_posts_link( __( '&larr; Older Posts', 'mystique' ) ); ?></div>
		<div class="nav-next"><?php previous_posts_link( __( 'Newer Posts &rarr;', 'mystique' ) ); ?></div>
	</div><!-- .post-navigation -->
<?php endif; ?>