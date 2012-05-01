<?php
/**
 * The template for displaying page.
 *
 * @package WordPress
 * @subpackage The Morning After
 */
get_header(); ?>

<?php get_template_part( 'top-banner' ); ?>

<div id="post_content" class="column span-14">
	
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		
		<div class="column span-11 first">
		
			<?php the_content( '<p>'.__( 'Continue reading this post','woothemes' ).'</p>' ); ?>
			
			<div class="clear"></div>
		
			<?php wp_link_pages(array( 'before' => '<div class="page-navigation"><p><strong>'.__( 'Pages','woothemes' ).':</strong> ', 'after' => '</p></div>', 'next_or_number' => 'number' )); ?>
		
			<?php edit_post_link(__( 'Edit this entry.', 'woothemes' ),'<p>','</p>' ); ?>
		
			<?php comments_template( '', true); ?>

		</div><!-- end .span-11 -->

	<?php endwhile; endif; ?>

	<?php get_sidebar(); ?>

</div><!-- end #post_content -->

<?php get_footer(); ?>