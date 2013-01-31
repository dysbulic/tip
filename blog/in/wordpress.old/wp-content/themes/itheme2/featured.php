<?php
/**
 * The featured slider template file.
 *
 * @package WordPress
 * @subpackage iTheme2
 * @since iTheme2 1.1-wpcom
 */

// See if we have any sticky posts with featured images and use them to create our featured posts area
$sticky = get_option( 'sticky_posts' );

// Proceed only if sticky posts exist.
if ( ! empty( $sticky ) ) :

	global $featured_post_id;

	$featured_args = array(
		'post__in' => $sticky,
		'post_status' => 'publish',
		'no_found_rows' => true,
	);

	// The Featured Posts query.
	$featured = new WP_Query( $featured_args );

	// Proceed only if published posts exist
	if ( $featured->have_posts() ) :

		// If we have sticky posts with a thumbnail, let's roll
		if ( itheme2_featuring_posts() ) :

			?>
			<div id="featured" class="slider">
				<ul id="featured-posts" class="slides">
				<?php

				// Start the actual featured post loop
				while ( $featured->have_posts() ) : $featured->the_post();
					if ( '' != get_the_post_thumbnail() ) {

						// Record our featured post IDs so we can exclude them from the regular loop later
						$featured_post_id[] = $post->ID;

						?>
						<li>
							<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'itheme2' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_post_thumbnail( 'small-feature' ); ?></a>
							<a class="feature-post-title" href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'itheme2' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
						</li>
						<?php
					}
				endwhile;

				?>
				</ul>

				<?php if ( 6 <= count( $featured_post_id ) ) : ?>
				<div class="slider-nav">
					<a href="#" id="featured-posts-prev" class="prev-slide">Previous</a>
					<a href="#" id="featured-posts-next" class="next-slide">Next</a>
				</div>
				<?php endif; ?>
			</div><!-- #featured .slider -->
			<?php

		endif; // itheme2_featuring_posts()

	endif; // $featured->have_posts()

endif; // ! empty( $sticky )
?>