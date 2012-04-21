<?php
/**
 * @package WordPress
 * @subpackage Spectrum
 */
?>

<?php get_header(); ?>

<div class="main-title"><h3><?php _e( 'Not found', 'spectrum' ); ?></h3></div>
	<div class="entry">
		<?php _e( 'Perhaps searching will help.', 'spectrum' ); ?>
		<?php get_search_form(); ?>
	</div>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>