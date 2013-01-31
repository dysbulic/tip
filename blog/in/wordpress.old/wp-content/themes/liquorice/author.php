<?php
/**
 * The template for displaying Author archive pages.
 *
 * @package WordPress
 * @subpackage Liquorice
 */

get_header(); ?>

	<div id="primary-content">
	<?php if ( have_posts() ) the_post(); ?>
		<h1 class="archive-title">
		<?php
			printf( __( 'Author Archives: %s', 'liquorice' ), "<span class='vcard'><a class='url fn n' href='" . get_author_posts_url( get_the_author_meta( 'ID' ) ) . "' title='" . esc_attr( get_the_author() ) . "' rel='me'>" . get_the_author() . "</a></span>" );
		?></h1>

		<?php
			rewind_posts();
			get_template_part( 'loop', 'author' );
		?>
	</div><!-- #primary-content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>