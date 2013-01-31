<?php
/**
 * The template for displaying Author archive pages.
 *
 * @package WordPress
 * @subpackage Mystique
 */

get_header(); ?>

 			<div id="content-container">
	 			<div id="content">
					<h1 class="archive-title">
					<?php
						printf( __( 'Author Archives: %s', 'mystique' ), "<span class='vcard'><a class='url fn n' href='" . get_author_posts_url( get_the_author_meta( 'ID' ) ) . "' title='" . esc_attr( get_the_author() ) . "' rel='me'>" . get_the_author() . "</a></span>" );
					?>
					</h1>
					<?php get_template_part( 'loop', 'author' ); ?>
				</div><!-- #content -->
			</div><!-- #content-container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>