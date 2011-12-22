<?php get_header(); ?>

<div id="content">

	<h2><?php _e( 'Error 404 &ndash; File not Found', 'blix' ); ?></h2>
	<p><?php _e( 'Sorry, but the page you were looking for could not be found. Why not try searching for what you were looking for?', 'blix' ); ?></p>
	
	<?php get_search_form(); ?>
	
</div> <!-- /content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>