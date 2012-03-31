<?php
/**
 * @package WordPress
 * @subpackage Structure
 */
get_header();
?>

<div id="content">

	<div id="contentleft">
    
        <div class="postarea">
                
            <h1><?php _e("Not Found, Error 404", 'structuretheme'); ?></h1>
            <p><?php _e("The page you are looking for no longer exists.", 'structuretheme'); ?></p>
            
        </div>
		
	</div>
			
	<?php get_sidebar( 'right' ); ?>

</div>

<!-- The main column ends  -->

<?php get_footer(); ?>