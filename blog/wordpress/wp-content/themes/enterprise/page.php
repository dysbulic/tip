<?php
/**
 * @package WordPress
 * @subpackage Enterprise
 */
?>
<?php get_header(); ?>

<div id="content">
    
	<div id="content-left">
                        
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                
            <div <?php post_class(); ?>>
            
                <div class="entry">            
                	<h1><?php the_title(); ?></h1>
                	<?php the_content(__('Read more', 'enterprise'));?><div class="clear"></div>
                	<?php edit_post_link(__('(Edit)', 'enterprise'), '', ''); ?><div class="clear"></div>
					<?php wp_link_pages( array( 'before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number') ); ?>
                </div>
                
            </div>
                
        <?php endwhile; else: ?>
        <p><?php _e('Sorry, no posts matched your criteria.', 'enterprise'); ?></p><?php endif; ?>
        
        <?php if ( comments_open() ) : // If comments are open, but there are no comments ?>        
         <div id="comments">
            <?php comments_template('',true); ?>
        </div>          
        <?php endif; ?>
        
    </div>
            		    
<?php get_sidebar(); ?>
			
</div>

<?php get_footer(); ?>