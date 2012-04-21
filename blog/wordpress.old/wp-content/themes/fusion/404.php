<?php
/**
 * @package WordPress
 * @subpackage Fusion
 */
?>
<?php get_header(); ?>

	<div id="mid-content">
		<h1><?php _e( 'Page not found (404)', 'fusion' ); ?></h1>

		<h2><?php _e( 'Try one of these links:', 'fusion' ); ?></h2>
		<ul>
			 <?php wp_list_pages( 'title_li=&depth=1' ); ?>
		</ul>
		
		<h2><?php _e( 'Try searching the site:', 'fusion' ); ?></h2>
		<ul>
			 <?php get_search_form(); ?>
		</ul>
	</div>

</div>
<!-- /mid -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>