<?php
/**
 * @package WordPress
 * @subpackage Enterprise
 */
?>
<?php get_header(); ?>

<div id="content">

	<div id="content-left">
	
    	<div class="post">
    	
        	 <div class="entry">
				<h1><?php _e("Not Found, Error 404", 'enterprise'); ?></h1>
        		<p><?php _e("The page you are looking for no longer exists. Perhaps you can return back to the site's", 'enterprise'); ?> <a href="<?php bloginfo('siteurl');?>"><?php _e("homepage", 'enterprise'); ?></a> <?php _e("and see if you can find what you are looking for.", 'enterprise'); ?></p>
        	</div>
        	
		</div>
		
	</div>
            		    
<?php get_sidebar(); ?>
			
</div>

<?php get_footer(); ?>