<?php
/**
 * Template Name: Full Width
 *
 * @package WordPress
 * @subpackage The Morning After 
 */
?>

<?php get_header(); ?>

<?php get_template_part( 'top-banner' ); ?>

<div id="post_content" class="column full-width first">

	<?php if ( have_posts() ) : $count = 0; ?>
		
		<?php while ( have_posts() ) : the_post(); $count++; ?>
			
			<div class="column">				
				
				<div class="entry">
					
					<?php the_content(); ?>
					
					<div class="clear"></div>
				
				</div>
				
				<?php wp_link_pages( array( 'before' => '<p><strong>'.__( 'Pages','woothemes' ).':</strong> ', 'after' => '</p>', 'next_or_number' => 'number' ) ); ?>
				
				<?php edit_post_link( __( 'Edit this entry.', 'woothemes' ),'<p>','</p>' ); ?>
				
				<?php comments_template( '', true ); ?>				
			
			</div><!-- end .column -->

		<?php endwhile; else: ?>

			<?php
				printf( __( '<p>Lost? Go back to the <a href="%s">home page</a></p>', 'woothemes' ),
					get_home_url()
				);
			?>

	<?php endif; ?>

</div><!-- end #post_content -->

<?php get_footer(); ?>