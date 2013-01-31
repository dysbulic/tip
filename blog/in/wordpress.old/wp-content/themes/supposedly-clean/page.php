<?php 
get_header();
?>
<div id="mike_content">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		 <div <?php post_class('post'); ?> id="post-<?php the_ID(); ?>">
		 <h2><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
		
			<?php the_content(); ?>

            <?php wp_link_pages(); ?>
        
		   </div><!--close post, WP-loop-->

<?php comments_template(); ?>

<?php endwhile; else: ?>
<p><?php _e('Sorry, no posts matched your criteria.', 'supposedly-clean'); ?></p>
<?php endif; ?>
</div><!--close mike content-->
<?php posts_nav_link(' &#8212; ', __('&laquo; Previous Page', 'supposedly-clean'), __('Next Page &raquo;', 'supposedly-clean')); ?>

<?php get_sidebar(); ?>

<?php get_footer(); ?>