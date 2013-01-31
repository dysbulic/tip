<?php
/**
 * The template that displays the latest four posts that have a thumbnail in a horizontal row on the front page.
 * @package WordPress
 * @subpackage Selecta
 */
?>

<?php
	/**
	 * Begin the latest posts section.
	 *
	 * Find the latest four posts that are not marked as "sticky".
	 * We limit the latest posts at four.
	 */
	$latest_args = array(
		'order' => 'DESC',
		'posts_per_page' => 4,
		'post__not_in' => get_option( 'sticky_posts' ),
		'no_found_rows' => true,
	);

	// The Featured Posts query.
	$latest = new WP_Query( $latest_args );
?>

<?php // Proceed only if published, non-sticky posts exist
if ( $latest ->have_posts() ) : ?>
	<div id="latest-posts-container" class="clearfix">
		<h2 class="latest-posts-header"><?php _e( 'Latest Posts', 'selecta' ); ?></h2>
			<ul id="latest-posts">

				<?php while ( $latest->have_posts() ) : $latest->the_post(); ?>
					<li class="latest-post">
						<?php if( has_post_thumbnail() ) : ?>
							<div class="latest-post-wrapper">
								<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s', 'selecta' ), the_title_attribute( 'echo=0' ) ) ); ?>">
									<?php the_post_thumbnail( 'normal', array( 'class' => 'post-thumbnail', 'alt' => get_the_title(), 'title' => get_the_title() ) ); ?>
								</a>
							</div><!-- .latest-post-wrapper -->
						<?php else : ?>
							<div class="latest-post-wrapper">
								<span><?php echo strip_shortcodes( strip_tags( selecta_short_excerpt( '150' ), '<a>' ) ); ?></span>
							</div><!-- .latest-post-wrapper -->
						<?php endif; ?>
						<h2 class="latest-post-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s', 'selecta' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h2>
					</li>
				<?php endwhile;	?>

		</ul><!-- #latest-posts -->
	</div><!-- #latest-posts-container -->
<?php endif; ?>