<?php 
get_header();
?>
<div id="mike_content">
<?php is_tag(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		 <div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
		 <h2><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a> <span>| <?php the_time(get_option('date_format')); ?></span></h2>
		
			<?php the_content(); ?>

            <?php wp_link_pages(); ?>
        
	
		 <hr class="post_line" />
	<div class="category"><?php printf(__('Posted in %s', 'supposedly-clean'), get_the_category_list(__(', ', 'supposedly-clean'))); ?><br /><?php the_tags('Tags: ', ', ', '<br />'); ?></div>
		 </div><!--close post, WP-loop-->
		

<?php comments_template(); // Get wp-comments.php template ?>

<?php endwhile; else: ?>
<p><?php _e('Sorry, no posts matched your criteria.', 'supposedly-clean'); ?></p>
<?php endif; ?>

</div><!--close mike content-->
<?php posts_nav_link(' &#8212; ', __('&laquo; Previous Page', 'supposedly-clean'), __('Next Page &raquo;', 'supposedly-clean')); ?>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
