<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 *
 * @package WordPress
 * @subpackage Matala
 */

get_header(); ?>

	<div id="primary">
		<div id="content" role="main">

			<header class="archive-header">
				<h1 class="archive-title">
					<?php if ( is_day() ) : ?>
						<?php printf( __( 'Daily Archives: %s', 'matala' ), '<span>' . get_the_date() . '</span>' ); ?>
					<?php elseif ( is_month() ) : ?>
						<?php printf( __( 'Monthly Archives: %s', 'matala' ), '<span>' . get_the_date( 'F Y' ) . '</span>' ); ?>
					<?php elseif ( is_year() ) : ?>
						<?php printf( __( 'Yearly Archives: %s', 'matala' ), '<span>' . get_the_date( 'Y' ) . '</span>' ); ?>
					<?php else : ?>
						<?php _e( 'Blog Archives', 'matala' ); ?>
					<?php endif; ?>
				</h1>
			</header><!-- .archive-header -->

			<?php
				get_template_part( 'loop', 'archive' );
			?>

		</div><!-- #content -->

		<?php
			/* Two columns of sidebars.
			 */
			get_sidebar( 'supplementary' );
		?>
		<div id="primary-bottom"></div>
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>