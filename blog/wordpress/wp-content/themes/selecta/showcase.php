<?php
/**
 * The template that shows the featured posts slider on the front page.
 * @package WordPress
 * @subpackage Selecta
 */
?>

<?php
	/**
	 * Begin the featured posts section.
	 *
	 * See if we have any sticky posts and use them to create our featured posts.
	 */
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

	/**
	 * We will need to count featured posts starting from zero
	 * to create the slider navigation.
	 */
	$counter_slider = 0;

	?>

	<div class="featured-posts">

	<?php
		// Let's roll.
		while ( $featured->have_posts() ) : $featured->the_post();

		// Increase the counter.
		$counter_slider++;

		// If there's only one sticky post, add a class to make the sticky post content area wider.
		if ( $featured->post_count == 1 ) :
			$one_sticky = 'one-sticky';
		endif;
		?>

		<div class="featured-post <?php echo $one_sticky; ?>" id="featured-post-<?php echo $counter_slider; ?>">
			<?php get_template_part( 'content', 'featured' ); ?>
		</div><!-- #featured-post -->

	<?php endwhile;	?>

	<?php
		// Show slider only if we have more than one featured post.
		if ( $featured->post_count > 1 ) :
	?>
	<div class="feature-slider">
		<ul>
		<?php

			// Reset the counter so that we end up with matching elements
			$counter_slider = 0;

			// Begin from zero
			rewind_posts();

			// Let's roll again.
			while ( $featured->have_posts() ) : $featured->the_post();
				$counter_slider++;
				if ( 1 == $counter_slider )
					$class = 'class="active"';
				else
					$class = '';
			?>
			<li id="featured-<?php echo $counter_slider; ?>">
				<a href="#featured-post-<?php echo $counter_slider; ?>" title="<?php printf( esc_attr__( 'Featuring: %s', 'selecta' ), the_title_attribute( 'echo=0' ) ); ?>" class="post-link"><span><?php echo $counter_slider; ?></span></a>
				<div class="feature-slider-entry-info">
					<span class="entry-date"><?php printf( _( '%s' ), get_the_time( 'j, M, Y' ) ); ?></span>
					<h2 class="featured-entry-title"><?php the_title(); ?></h2>
				</div><!-- .featured-slider-entry-info -->
			</li>
		<?php endwhile;	?>
		</ul>
	</div><!-- .feature-slider -->
	<?php endif; // End check for more than one sticky post. ?>
	</div><!-- .featured-posts -->
	<?php endif; // End check for published posts. ?>
<?php endif; // End check for sticky posts. ?>