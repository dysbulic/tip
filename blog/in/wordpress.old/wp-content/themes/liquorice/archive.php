<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 *
 * @package WordPress
 * @subpackage Liquorice
 */

get_header(); ?>

	<div id="primary-content">
		<?php if ( have_posts() ) the_post(); ?>
			<h1 class="archive-title">
			<?php if ( is_day() ) : ?>
				<?php printf( __( 'Daily Archives: <span>%s</span>', 'liquorice' ), get_the_date() ); ?>
			<?php elseif ( is_month() ) : ?>
				<?php printf( __( 'Monthly Archives: <span>%s</span>', 'liquorice' ), get_the_date( 'F Y' ) ); ?>
			<?php elseif ( is_year() ) : ?>
				<?php printf( __( 'Yearly Archives: <span>%s</span>', 'liquorice' ), get_the_date( 'Y' ) ); ?>
			<?php else : ?>
				<?php _e( 'Blog Archives', 'liquorice' ); ?>
			<?php endif; ?>
			</h1>
			<?php
				rewind_posts();
				get_template_part( 'loop', 'archive' );
			?>
	</div><!-- #primary-content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>