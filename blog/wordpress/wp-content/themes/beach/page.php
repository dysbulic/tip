<?php
/**
 * @package WordPress
 * @subpackage Beach
 */

get_header(); ?>

		<div id="primary">
			<div id="content" role="main">

				<?php the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

				<?php comments_template( '', true ); ?>

			</div><!-- #content -->

			<?php get_sidebar(); ?>
		</div><!-- #primary -->

<?php get_footer(); ?>