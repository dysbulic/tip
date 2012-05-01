<?php
/**
 * The template for displaying Tag Archive pages.
 *
 * @package WordPress
 * @subpackage Choco
 */
get_header(); ?>

		<h1 class="pagetitle">
			<?php printf( __( 'Tag Archives: %s', 'choco' ), '<span>' . single_tag_title( '', false ) . '</span>' ); ?>
		</h1>
		
		<div class="list-page">
			<?php get_template_part( 'loop', 'tag' ); ?>
		</div><!-- #list-page -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>