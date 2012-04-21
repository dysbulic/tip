<?php
/**
 * @package Adventure_Journal
 */

get_header(); ?>

	<div id="content" class="clearfix">
		<div id="main-content">
			<?php if ( have_posts() ) the_post(); ?>
				<h1 class="entry-title">
				<?php if ( is_day() ) : ?>
					<?php printf( __( 'Daily Archives: <span>%s</span>', 'adventurejournal' ), get_the_date() ); ?>
				<?php elseif ( is_month() ) : ?>
					<?php printf( __( 'Monthly Archives: <span>%s</span>', 'adventurejournal' ), get_the_date( 'F Y' ) ); ?>
				<?php elseif ( is_year() ) : ?>
					<?php printf( __( 'Yearly Archives: <span>%s</span>', 'adventurejournal' ), get_the_date( 'Y' ) ); ?>
				<?php else : ?>
					<?php _e( 'Blog Archives', 'adventurejournal' ); ?>
				<?php endif; ?>
				</h1>
	
				<?php
					rewind_posts();
					get_template_part( 'loop', 'archive' );
				?>
			</div><!-- #main-content -->

		<?php get_sidebar(); ?>
	</div><!-- #content -->

<?php get_footer(); ?>