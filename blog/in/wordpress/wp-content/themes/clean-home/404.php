<?php
/**
 * @package WordPress
 * @subpackage Clean Home
 */
?>
<?php get_header(); ?>

	<div id="content">
		
		<div class="post">
			<h2 class="center"><?php _e( 'Not found', 'cleanhome' ); ?></h2>
			<p class="center"><?php _e( "Sorry, but you are looking for something that isn't here.", 'cleanhome' ); ?></p>
			<?php get_search_form(); ?>
		</div>

	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
