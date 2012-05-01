<?php
/**
 * @package WordPress
 * @subpackage Inuit Types
 */
?>
<?php get_header(); ?>

    <div class="browsing-archive">
    
	    <?php if (have_posts()) : ?>
		
        		<div id="header-about">
				
				<?php if (is_category()) { ?>
				
            	<h2><?php _e( 'Browsing All Posts filed under', 'it' ); ?> &raquo;<?php single_cat_title(); ?>&laquo;</h2>        
           	
				<?php } elseif (is_day()) { ?>
				<h2><?php _e( 'Browsing All Posts published on', 'it' ); ?> &raquo;<?php the_time('F jS, Y'); ?>&laquo;</h2>

				<?php } elseif (is_month()) { ?>
				<h2><?php _e( 'Browsing All Posts published on', 'it' ); ?> &raquo;<?php the_time('F, Y'); ?>&laquo;</h2>

				<?php } elseif (is_year()) { ?>
				<h2><?php _e( 'Browsing All Posts published in', 'it' ); ?> &raquo;<?php the_time('Y'); ?>&laquo;</h2>
				
				<?php } ?>
				
				</div>
	</div>
	
	<div class="blog">
		
		<?php while (have_posts()) : the_post(); ?>	
            
            <div class="post">
            
			    <h2><a title="<?php _e( 'Permanent link to ', 'it' ); ?><?php the_title_attribute(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
				
			    <div class="date-comments">
                    
				    <p class="fl"><em><?php the_time('F j, Y'); ?></em></p>
                    
				    <p class="fr"><span class="comments"><?php comments_popup_link('0', '1', '%'); ?></span></p>
                
			    </div>
				
			    <div class="fix"></div>
                
			    <p><?php echo inuit_types_excerpt( get_the_excerpt(), get_permalink() ); ?></p>
				
            </div>

		<?php endwhile; ?>
		
		<div class="fix"></div>
	
			<div id="nav-below" class="navigation">
				<div class="nav-previous"><?php next_posts_link(__( '<span class="meta-nav">&larr;</span> Older posts', 'theme' )); ?></div>
				<div class="nav-next"><?php previous_posts_link(__( 'Newer posts <span class="meta-nav">&rarr;</span>', 'theme' )); ?></div>
			</div><!-- #nav-below -->
					
    </div>		
	
	<?php endif; ?>	

</div>

<?php get_sidebar(); ?>
   	    
<?php get_footer(); ?>
