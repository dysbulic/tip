<?php get_header(); ?>

	<div id="content" class="narrowcolumn">
		<h2 class="center"><?php _e( 'Error 404 - Not Found', 'sunburn' ); ?></h2>

		<p><?php _e( 'Apologies, but the page you requested could not be found. Perhaps searching will help.', 'sunburn' ); ?></p>

		<?php get_search_form(); ?>
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>