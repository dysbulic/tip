<?php
/*
Template Name: Archives
*/
?>

<?php 
get_header();
?>
<div id="mike_content">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		 <div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
		<h2><?php _e('Archives by Month:', 'supposedly-clean'); ?></h2>
  <ul>
    <?php wp_get_archives('type=monthly'); ?>
  </ul>
<br />
<h2><?php _e('Archives by Subject:', 'supposedly-clean'); ?></h2>
  <ul>
     <?php wp_list_cats(); ?>
  </ul>

            <?php wp_link_pages(); ?>
        
	
	
		   </div><!--close post, WP-loop-->

<?php endwhile; else: ?>
<p><?php _e('Sorry, no posts matched your criteria.', 'supposedly-clean'); ?></p>
<?php endif; ?>
</div><!--close mike content-->
<?php posts_nav_link(' &#8212; ', __('&laquo; Previous Page', 'supposedly-clean'), __('Next Page &raquo;', 'supposedly-clean')); ?>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
