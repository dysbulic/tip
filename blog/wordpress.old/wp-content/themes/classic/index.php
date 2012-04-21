<?php 
get_header();
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<?php the_date('','<h2>','</h2>'); ?>

<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	 <h3 class="storytitle">
	<?php if ( ! is_single() ) : ?>
		<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'classic' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
	<?php else : ?>
		<?php the_title(); ?>
	<?php endif; ?>	 
	 </h3>
	<div class="meta"><?php _e('Filed under:','classic'); ?>  <?php the_category( ', ' ) ?> &#8212; <?php the_tags(__('Tags: ', 'classic'), ', ', ' &#8212; '); ?> <?php the_author() ?> @ <?php the_time() ?> <?php edit_post_link(__('Edit This','classic')); ?></div>

	<div class="storycontent">
		<?php the_content(__('(more...)','classic')); ?>
	</div>

	<div class="feedback">
            <?php wp_link_pages(); ?>
            <?php comments_popup_link(__('Leave a Comment','classic'), __('Comments (1)','classic'), __('Comments (%)','classic')); ?>
	</div>

</div>

<?php comments_template(); // Get wp-comments.php template ?>

<?php endwhile; else: ?>
<p><?php _e('Sorry, no posts matched your criteria.','classic'); ?></p>
<?php endif; ?>

<?php posts_nav_link(' &#8212; ', __('&laquo; Newer Posts','classic'), __('Older Posts &raquo;','classic')); ?>

<?php get_footer(); ?>
