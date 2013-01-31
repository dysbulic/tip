<?php
/**
 * @package WordPress
 * @subpackage Andrea
 */
?>
<?php get_header(); ?>

<div id="content">

	<h2 class="page-title"><?php _e( 'Error 404 - Not Found', 'andrea' ); ?></h2>
	<div class="warning">
		<p><?php _e( 'Apologies, but we were unable to find what you were looking for. Perhaps searching will help.', 'andrea' ); ?></p>
		<?php get_search_form(); ?>
	</div>

</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>