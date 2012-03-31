<?php
/**
 * @package WordPress
 * @subpackage Structure
 */

/*
Template Name: Archive
*/
?>

<?php get_header(); ?>

<div id="content">

	<div id="contentleft">
    
        <div class="postarea">
    
    		<div class="posttitle">
				<h3><?php _e("Archives", 'structuretheme'); ?></h3>       
            </div>
				
				<div class="archive">
		
					<h5><?php _e("By Page:", 'structuretheme'); ?></h5>
						<ul>
							<?php wp_list_pages('title_li='); ?>
						</ul>
				
					<h5><?php _e("By Month:", 'structuretheme'); ?></h5>
						<ul>
							<?php wp_get_archives('type=monthly'); ?>
						</ul>
							
					<h5><?php _e("By Category:", 'structuretheme'); ?></h5>
						<ul>
							<?php wp_list_categories('sort_column=name&title_li='); ?>
						</ul>
		
				</div>
				
				<div class="archive">
					
					<h5><?php _e("By Post:", 'structuretheme'); ?></h5>
						<ul>
							<?php wp_get_archives('type=postbypost&limit=100'); ?> 
						</ul>
				</div>
			            
        </div>
		
	</div>
			
<?php get_sidebar( 'right' ); ?>

</div>

<!-- The main column ends  -->

<?php get_footer(); ?>