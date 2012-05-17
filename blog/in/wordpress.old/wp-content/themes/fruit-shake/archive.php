<?php
/**
 * @package WordPress
 * @subpackage Fruit Shake
 */

get_header(); ?>

		<section id="primary">
			<div id="content" role="main">

				<header class="page-header">
					<h1 class="page-title">
						<?php
							if ( is_day() ) :
								printf( __( 'Daily Archives: <span>%s</span>', 'fruit-shake' ), get_the_date() );
							elseif ( is_month() ) :
								printf( __( 'Monthly Archives: <span>%s</span>', 'fruit-shake' ), get_the_date( 'F Y' ) );
							elseif ( is_year() ) :
								printf( __( 'Yearly Archives: <span>%s</span>', 'fruit-shake' ), get_the_date( 'Y' ) );
							else :
								_e( 'Archives', 'fruit-shake' );
							endif;
						?>
					</h1>
				</header>

				<?php rewind_posts(); ?>

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', get_post_format() ); ?>

				<?php endwhile; ?>

			</div><!-- #content -->
		</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>