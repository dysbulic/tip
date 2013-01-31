<?php 
get_header();
?>
<div id="mike_content">

<?php is_tag(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		 <div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
		 <h2><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
		 <div class="info"><?php the_time(get_option('date_format')); ?><br /><?php comments_popup_link(__('Leave a Comment', 'supposedly-clean'), __('1 Comment', 'supposedly-clean'), __('% Comments', 'supposedly-clean')); ?></div>

			<?php ($post->post_excerpt != "")? the_excerpt() : the_content(); ?>

            <?php wp_link_pages(); ?>
        
	
		 <hr class="post_line" />
		 <div class="category"><?php printf(__('Posted in %s', 'supposedly-clean'), get_the_category_list(__(', ', 'supposedly-clean'))); ?><br /><?php the_tags('Tags: ', ', ', '<br />'); ?></div>
		 </div><!--close post, WP-loop-->
		


<?php endwhile; else: ?>
<p><?php _e('Sorry, no posts matched your criteria.', 'supposedly-clean'); ?></p>
<?php endif; ?>
<?php posts_nav_link(' &#8212; ', __('&laquo; Previous Page', 'supposedly-clean'), __('Next Page &raquo;', 'supposedly-clean')); ?>

</div><!--close mike content-->


<?php get_sidebar(); ?>

<?php get_footer(); ?>
