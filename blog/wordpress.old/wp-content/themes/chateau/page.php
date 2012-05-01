<?php
/**
 * The template for displaying all pages.
 *
 * @package WordPress
 * @subpackage Chateau
 */
get_header(); ?>

<?php
	$options = chateau_get_theme_options();
	$current_layout = $options['theme_layout'];
	$two_columns = array( 'content-sidebar', 'sidebar-content' );
	$no_columns = array( 'content' );

	if ( in_array( $current_layout, $two_columns ) )
		$content_width = 630;
	elseif ( in_array( $current_layout, $no_columns ) )
		$content_width =  862;
?>

<div id="primary">
	<div id="content" class="clear-fix" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'page' ); ?>

			<?php comments_template( '', true ); ?>

		<?php endwhile; // end of the loop. ?>

	</div><!-- end #content -->
</div><!-- end #primary -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>