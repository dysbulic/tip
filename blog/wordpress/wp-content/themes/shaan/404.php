<?php
/**
 * @package Shaan
 */

get_header(); ?>

<div id="container">

	<div id="content" class="narrow">

		<h1 class="page-title"><?php _e( '404 Page Not Found', 'shaan' ); ?></h1>

		<p><?php _e( 'Apologies, but the page you requested could not be found. Try searching.', 'shaan' ); ?></p>

		<?php get_search_form(); ?>

	</div><!-- #content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>