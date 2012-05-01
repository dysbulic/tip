<?php
/**
 * @package WordPress
 * @subpackage Inuit Types
 */
?>
<?php get_header(); ?>

<?php inuit_types_intro(); ?>
    
 <?php if (have_posts()) : ?>
 
	<?php while (have_posts()) : the_post(); ?>
        
		<div class="post">
		
		    <div id="header-about">
    
	            <h1><?php the_title(); ?> <?php edit_post_link('<span class="edit-entry">' . __( 'Edit this entry', 'it' ) . '</span>'); ?></h1>
			
			</div>
			          
            <div class="entry">
				
			    <?php the_content(); ?>
			    <?php wp_link_pages( 'before=<div class="pagination">' . __( 'Pages:' ) . '&after=</div>'); ?>
					
            </div>
            
            <div class="fix"></div>

			<div id="comments">
			
				<?php comments_template(); ?>
				
			</div>		
                
		</div>
	
	<?php endwhile; else: ?>

		<p><?php _e( 'Apologies, but the page you requested could not be found. Perhaps searching will help.', 'it' ); ?></p>
		<?php get_search_form(); ?>

	<?php endif; ?>

</div>

<?php get_sidebar(); ?>
    	    
<?php get_footer(); ?>
