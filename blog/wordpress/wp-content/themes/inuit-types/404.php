<?php
/**
 * @package WordPress
 * @subpackage Inuit Types
 */
?>
<?php get_header(); ?>
            
            <div class="post">
          
                <h2 class="post_title"><?php _e( 'Not Found', 'it' ); ?></h2>
						
            	<div class="entry">
                
					<p><?php _e( 'Apologies, but the page you requested could not be found. Perhaps searching will help.', 'it' ); ?></p>
					<?php get_search_form(); ?>
                
                </div>
                
            </div>

</div><!-- Content -->


<?php get_sidebar(); ?>
 
    	    
<?php get_footer(); ?>