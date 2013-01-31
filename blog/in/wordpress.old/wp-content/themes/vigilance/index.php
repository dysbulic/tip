<?php
/**
 * @package Vigilance
 */
?>
<?php get_header(); ?>
		<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="post-header">
				<h2><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s', 'vigilance'), the_title_attribute( 'echo=0' )); ?>"><?php the_title(); ?></a></h2>
				<div class="date"><?php the_time(get_option("date_format")); ?></div>
			</div><!--end post header-->
			<div class="meta clear">
				<div class="tags"><?php the_tags(__('tags: ', ', ', '', 'vigilance')); ?></div>
				<div class="author">
					<?php vigilance_posted_by(); ?>
				</div>
			</div><!--end meta-->
			<div class="entry clear">
				<?php if ( function_exists( 'add_theme_support' ) ) the_post_thumbnail( array(250,9999), array('class' => 'alignleft') ); ?>
				<?php the_content(__('Read more...', 'vigilance')); ?>
				<?php edit_post_link(__('Edit', 'vigilance'),'<p>','</p>'); ?>
				<?php wp_link_pages(); ?>
			</div><!--end entry-->
			<div class="post-footer">
				<div class="comments"><?php comments_popup_link(__('Leave a comment', 'vigilance'), __('1 Comment', 'vigilance'), __('% Comments', 'vigilance')); ?></div>
				<div class="categories"><?php _e('from &rarr;', 'vigilance'); ?> <?php the_category(', '); ?></div>
			</div><!--end post footer-->
		</div><!--end post-->
		<?php endwhile; /* rewind or continue if all posts have been fetched */ ?>
		<div class="navigation index">
			<div class="alignleft"><?php next_posts_link(__('&laquo; Older Entries', 'vigilance')); ?></div>
			<div class="alignright"><?php previous_posts_link(__('Newer Entries &raquo;', 'vigilance')); ?></div>
		</div><!--end navigation-->
		<?php else : ?>
		<?php endif; ?>
	</div><!--end content-->
<?php get_sidebar(); ?>
<?php get_footer(); ?>