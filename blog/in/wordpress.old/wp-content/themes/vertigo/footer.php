<?php
/**
 * @package Vertigo
 */
?>

	</div><!-- #main -->

	<nav id="access" role="navigation">
		<h1 class="section-heading"><?php _e( 'Main menu', 'vertigo' ); ?></h1>
		<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
	</nav><!-- #access -->
	
	<?php
		/* A sidebar in the footer? Yep. You can can customize
		 * your footer with three columns of widgets.
		 */
		get_sidebar( 'footer' );
	?>

	<footer id="colophon" role="contentinfo">

		<a id="wordpress-logo" title="<?php esc_attr_e( 'Proudly powered by WordPress', 'vertigo' ); ?>" href="http://wordpress.com/">WordPress</a>

		<?php get_search_form(); ?>

		<ul id="controls">
			<li class="search">
				<a href="#" title="<?php esc_attr_e( 'Search', 'vertigo' ); ?>"><?php _e( 'Search', 'vertigo' ); ?></a>
			</li>
			<li class="feed">
				<a href="<?php bloginfo( 'rss_url' ); ?>" title="<?php esc_attr_e( 'RSS feed', 'vertigo' ); ?>"><?php _e( 'RSS feed', 'vertigo' ); ?></a>
			</li>
			<li class="random">
				<?php
					query_posts( array( 'orderby' => 'rand', 'posts_per_page' => 1, 'ignore_sticky_posts' => 1 ) );
				 	if ( have_posts() ) : while ( have_posts() ) : the_post();
				 ?>
				<a href="<?php the_permalink(); ?>" title="<?php esc_attr_e( 'Random', 'vertigo' ); ?>"><?php _e( 'Random', 'vertigo' ); ?></a>
				<?php endwhile; endif; wp_reset_query(); ?>
			</li>
		</ul>

		<?php
			if ( is_single() ) {
		?>
				<nav id="nav-below">
					<div class="nav-previous"><?php previous_post_link( '%link', '%title' ); ?></div>
					<div class="nav-next"><?php next_post_link( '%link', '%title' ); ?></div>
				</nav>
		<?php
			} else {
				if ( $wp_query->max_num_pages > 1 ) : ?>
					<nav id="nav-below" class="clear-fix">
						<div class="nav-previous"><?php next_posts_link( __( 'Older posts', 'vertigo' ) ); ?></div>
						<div class="nav-next"><?php previous_posts_link( __( 'Newer posts', 'vertigo' ) ); ?></div>
					</nav>
		<?php
				endif;

				if ( is_singular() || is_404() ) {
					// do nothing
				} else {
					vertigo_pagination();
				}
			}
		?>

		<div id="site-generator">
			Inspired by the work of <a href="http://saulbass.tv">SAUL BASS</a>, ART GOODMAN, and DAVE NAGATA. Hitchcock typeface by <a href="http://typographica.org/001110.php" target="_blank">MATT TERICH</a>.<br />
			<a href="http://wordpress.org/" rel="generator">Proudly powered by WordPress</a>
<?php printf( __( 'Theme: %1$s by %2$s.', 'vertigo' ), 'Vertigo', '<a href="http://matthewbuchanan.name/" rel="designer">Matthew Buchanan</a>' ); ?>
		</div>

	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>