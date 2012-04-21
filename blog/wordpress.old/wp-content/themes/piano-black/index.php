<?php
/**
 * @package WordPress
 * @subpackage Piano Black
 */

get_header(); ?>

		<div id="primary">
			<div id="content" role="main">
				
				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>
					
					<?php get_template_part( 'content', get_post_format() ); ?>

				<?php endwhile; ?>
				
				<?php /* Display navigation to next/previous pages when applicable */ ?>
				<?php if ( $wp_query->max_num_pages > 1 ) : ?>
					<nav id="nav-below" class="post-nav">
						<h1 class="section-heading"><?php _e( 'Post navigation', 'piano-black' ); ?></h1>
						<div class="nav-previous"><?php next_posts_link( __( 'Older posts', 'piano-black' ) ); ?></div>
						<div class="nav-next"><?php previous_posts_link( __( 'Newer posts', 'piano-black' ) ); ?></div>
					</nav><!-- #nav-below -->
				<?php endif; ?>				

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>