<?php
get_header();
?>
<div id="content">
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
  <div <?php post_class( 'entry' ); ?>>
    <h3 class="entrytitle" id="post-<?php the_ID(); ?>"><?php the_title(); ?></h3>
    <div class="entrymeta">
        Posted <?php the_time(get_option('date_format')); ?><br />
        Filed under: <?php the_category(', ') ?> | <?php the_tags('Tags: ', ', ', ' | '); ?> <?php edit_post_link(__(' Edit')); ?>
	</div>

    <div class="entrybody">
      <?php the_content(__('Read more &raquo;'));?>
      <?php wp_link_pages(); ?>
<div class="sociable"><?php if(function_exists('wp_email')) { email_link(); } ?>
</div>
    </div>

	<div class="navigation nav-single">
		<div class="alignleft"><?php previous_post_link( '%link', '<span>&laquo; %title</span>' ); ?></div>
		<div class="alignright"><?php next_post_link( '%link', '<span>%title &raquo;</span>' ); ?></div>
	</div><!-- #nav-below -->

  </div>
  <?php comments_template(); // Get wp-comments.php template ?>
  <?php endwhile; else: ?>
  <p>
    <?php _e('Sorry, no posts matched your criteria.'); ?>
  </p>
  <?php endif; ?>

</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>