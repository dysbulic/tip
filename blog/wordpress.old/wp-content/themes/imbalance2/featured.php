<?php
/**
 * @package Imbalance 2
 */
?>
<?php
// See if we have any sticky posts and use them to create our featured posts area
$sticky = get_option( 'sticky_posts' );

// Proceed only if sticky posts exist.
if ( ! empty( $sticky ) ) :

	$featured_args = array(
		'post__in' => $sticky,
		'post_status' => 'publish',
		'no_found_rows' => true,
	);

	// The Featured Posts query.
	$featured = new WP_Query( $featured_args );

	// Proceed only if published posts exist
	if ( $featured->have_posts() ) :
		?>
		<div id="featured-posts" class="clear-fix">
			<div id="featured-posts-inner">
			<?php
			// Start the actual featured post loop
			while ( $featured->have_posts() ) : $featured->the_post();

				get_template_part( 'content' );

			endwhile;
			?>
			</div>
		</div><!-- .featured-posts -->
		<?php
	endif; // $featured->have_posts()

endif; // ! empty( $sticky )
?>