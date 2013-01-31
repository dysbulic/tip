<?php
/**
 * @package WordPress
 * @subpackage zBench
 *
 * 404 error template
 */
?>
<?php get_header(); ?>

	<div id="maincontent">
		<div id="maincontent_inner">

		<div class="post">

			<h2 class="title"><?php _e( '404 Error - Not found', 'zbench' ); ?></h2>
			<div class="post-info">
			</div>

			<p><?php _e( 'Apologies, but the page you requested could not be found. Perhaps searching will help.', 'zbench' ); ?></p>

			<?php get_search_form(); ?>

		</div>

	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>