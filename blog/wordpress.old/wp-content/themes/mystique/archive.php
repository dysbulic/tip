<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 *
 * @package WordPress
 * @subpackage Mystique
 */

get_header(); ?>

			<div id="content-container">
				<div id="content">
					<h1 class="archive-title">
						<?php if ( is_day() ) : ?>
							<?php printf( __( 'Daily Archives: %s', 'mystique' ), '<span>' . get_the_date() . '</span>' ); ?>
						<?php elseif ( is_month() ) : ?>
							<?php printf( __( 'Monthly Archives: %s', 'mystique' ), '<span>' . get_the_date( 'F Y' ) . '</span>' ); ?>
						<?php elseif ( is_year() ) : ?>
							<?php printf( __( 'Yearly Archives: %s', 'mystique' ), '<span>' . get_the_date( 'Y' ) . '</span>' ); ?>
						<?php else : ?>
							<?php _e( 'Blog Archives', 'mystique' ); ?>
						<?php endif; ?>
					</h1>
					<?php get_template_part( 'loop', 'archive' ); ?>
				</div><!-- #content -->
			</div><!-- #content-container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>