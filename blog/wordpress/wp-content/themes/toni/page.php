<?php 
get_header();
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<h2><?php the_title(); ?></h2>
	
<div <?php post_class('post'); ?> id="post-<?php the_ID(); ?>">
	<div class="meta"><?php edit_post_link(__('Edit This')); ?></div>
	
	<div class="storycontent">
		<?php the_content(__('(more...)')); ?>
	</div>
	
	<div class="feedback">
            <?php wp_link_pages(); ?>
            <?php comments_popup_link(__('Leave a Comment'), __('Comments (1)'), __('Comments (%)')); ?>
	</div>

</div>

<?php comments_template(); ?>

<?php endwhile; else: ?>
<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php endif; ?>

<?php posts_nav_link(' &#8212; ', __('&laquo; Previous Page'), __('Next Page &raquo;')); ?>

<?php get_footer(); ?>
