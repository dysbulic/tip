<?php get_header(); ?>

	<div id="content" class="sanda">

	<?php if (have_posts()) : ?>

		<h2 class="pagetitle"><?php _e('Search Results'); ?></h2>

		<?php while (have_posts()) : the_post(); ?>
				
			<div <?php post_class(); ?>>
				<h3 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent link to %s' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a><br />
				<?php the_time(get_option('date_format')) ?></h3>
				
				<div class="entry">
					<?php the_excerpt() ?>
				</div>
				
				<p class="postmetadata"><?php _e('Posted in','daydream'); ?> <?php the_category(', ') ?> | <?php edit_post_link(__('Edit','daydream'), '', ' | '); ?>  <?php comments_popup_link(__('Leave a Comment &#187;','daydream'), __('1 Comment &#187;','daydream'), __('% Comments &#187;','daydream')); ?></p>
			</div>
	
		<?php endwhile; ?>
		
		<?php 
			// This young snippet fixes something too difficult to explain
			
			$numposts = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish'");
			$perpage = $wpdb->get_var("SELECT option_value FROM $wpdb->options WHERE option_name = 'posts_per_page'");

			if ($numposts > $perpage) {
		?>
				<div class="navigation">
                                	<div class="alignleft"><?php next_posts_link(__('&laquo; Previous Entries','daydream')) ?></div>
                			<div class="alignright"><?php previous_posts_link(__('Next Entries &raquo;','daydream')) ?></div>
				</div>
		<?php
			}
		?>
	
	<?php else : ?>

		<h4><?php _e('No posts found. Try a different search?', 'daydream'); ?></h4>
		<?php get_search_form(); ?>
		<div style="width: 100%; height: 40px;"></div>

	<?php endif; ?>
		
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
