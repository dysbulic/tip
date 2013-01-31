<?php
/**
 * @package WordPress
 * @subpackage Skeptical
 */
?>
<?php $sticky = get_option( 'sticky_posts' ); ?>
<?php
	// Proceed only if sticky posts exist.
	if ( ! empty( $sticky ) ) :
	$args = array(
		'post__in' => $sticky,
		'posts_per_page' => 3,
		'ignore_sticky_posts' => 1
	);
	$the_query = new WP_Query( $args );
?>
	<div id="footer-secondary">
		<div class="col-full">
		<?php if ( $the_query->have_posts() ) : $counter = 0; ?>
			<div id="sticky-posts">
				<h3 class="footer-secondary-headings"><?php _e( 'Featured Posts','woothemes' ); ?><span class="bg">&nbsp;</span></h3>
				<?php while ( $the_query->have_posts() ) : $the_query->the_post(); $counter++; ?>
					<div class="sticky-post fl <?php if ( $counter == 3 ) { echo 'last'; } ?>">
						<h4><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'woothemes' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h4>
						<?php the_excerpt(); ?>
						<a class="more" href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'woothemes' ), the_title_attribute( 'echo=0' ) ); ?>"><?php _e( 'Continue Reading', 'woothemes' ); ?></a>
					</div><!-- /.sticky-post -->
				<?php endwhile; wp_reset_query(); ?>
				<div class="fix"></div>
			</div><!-- /#sticky-posts -->
		<?php endif; ?>
		</div><!-- /.col-full -->
	</div><!-- /#footer-secondary -->
<?php endif; ?>

	<?php get_sidebar( 'footer' ); ?>

	<div id="footer">
		<div class="footer-inside">
			<div id="copyright" class="col-left">
				<p><a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
			</div>
			<div id="credit" class="col-right">
				<p><a href="http://wordpress.org/" rel="generator">Proudly powered by WordPress</a>. <?php printf( __( 'Theme: %1$s by %2$s.', 'woothemes' ), 'Skeptical', '<a href="http://www.woothemes.com/" rel="designer">WooThemes</a>' ); ?></p>
			</div>
			<div class="fix"></div>
		</div>
	</div><!-- /#footer  -->
</div><!-- /#wrapper -->
<?php wp_footer(); ?>
</body>
</html>