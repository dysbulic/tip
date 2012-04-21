<?php
/**
 * @package WordPress
 * @subpackage Manifest
 */

get_header(); ?>

	<?php if ( is_category() ) : ?>
	<h2 class="archive-title"><?php _e( 'Category:', 'manifest' ); ?> <strong><?php single_cat_title(); ?></strong></h2>

	<?php elseif ( is_tag() ) : ?>
	<h2 class="archive-title"><?php _e( 'Tag:', 'manifest' ); ?> <strong><?php single_tag_title(); ?></strong></h2>

	<?php elseif ( is_month() ) : ?>
	<h2 class="archive-title"><?php _e( 'Month:', 'manifest' ); ?> <strong><?php the_time( 'F, Y' ); ?></strong></h2>

	<?php endif; ?>

	<div id="core-content" class="hfeed">

	<?php if ( have_posts() ) : ?>

		<?php /* Start the Loop */ ?>
		<?php while ( have_posts() ) : the_post(); ?>
		
			<?php get_template_part( 'content', get_post_format() ); ?>

		<?php endwhile; ?>

	<div class="navigation">
		<div class="prev"><?php next_posts_link( __( '&lt; Older Posts', 'manifest' ) ); ?></div>
		<div class="next"><?php previous_posts_link( __( 'Newer Posts &gt;', 'manifest' ) ); ?></div>
	</div>

	<?php else : ?>

	<h2><?php _e( 'Not Found', 'manifest' ); ?></h2>
	<p><?php _e( "Sorry, but you are looking for something that isn't here.", 'manifest' ); ?></p>

	<?php endif; ?>

	</div><!-- #core-content -->

<?php get_footer(); ?>