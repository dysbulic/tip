<?php
/**
 * @package WordPress
 * @subpackage Koi
 */
get_header(); ?>

	<div id="content">

		<h2 class="post-title"><?php _e( '404 Not Found', 'ndesignthemes' ); ?></h2>
		<p><?php _e( 'Apologies, but the page you requested could not be found. Perhaps searching will help.', 'ndesignthemes' ); ?></p>
		<?php get_search_form(); ?>

	</div>
	<!--/content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>