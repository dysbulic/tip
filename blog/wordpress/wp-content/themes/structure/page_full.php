<?php
/**
 * @package WordPress
 * @subpackage Structure
 */

/*
Template Name: Full Width
*/
?>

<?php get_header(); ?>

<div id="content">

	<div id="contentwide">
    
        <div class="postarea">
    
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            
            <div class="posttitle">
            	<h3><?php the_title(); ?></h3>
            </div>
            
            <?php the_content(__('Read More'));?>
            <?php wp_link_pages( 'before=<div class="page-link">' . __( 'Pages:', 'theme' ) . '&after=</div>'); ?>
            <div style="clear:both;"></div>
			<?php edit_post_link('(Edit)', '', ''); ?>
            
            <?php endwhile; else: ?>
            
            <p><?php _e('Sorry, no posts matched your criteria.'); ?></p><?php endif; ?>
            
        </div>

		<div class="postcomments">
			<?php comments_template('',true); ?>
		</div>        
		
	</div>
			
</div>

<!-- The main column ends  -->

<?php get_footer(); ?>