<?php get_header(); ?>

	<div id="content" class="narrowcolumn">
	
	<?php if (have_posts()) : ?>
		
		<?php while (have_posts()) : the_post(); ?>

			<div <?php post_class(); ?>>
				<h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php esc_attr_e( 'Permanent Link to', 'sapphire' ); ?> <?php the_title(); ?>"><?php the_title(); ?></a></h2> 
				<small><?php _e('Posted', 'sapphire'); ?> <?php the_time(get_option('date_format')) ?> <?php _e('by', 'sapphire'); ?> <?php the_author(); ?><br /> 
				<strong><?php _e('Categories:', 'sapphire'); ?></strong> <?php the_category(', ') ?></small>
				<br />
				<?php the_tags(__('<strong>Tags:</strong>', 'sapphire') .' ', ', ', '<br />'); ?>
				<div class="entry">
					<?php the_content(__('Read the rest of this post &raquo;', 'sapphire')); ?>
				</div>
		
				<p class="postmetadata"><strong><?php _e('Comments:', 'sapphire'); ?></strong> <?php comments_popup_link(__('Be the first to comment', 'sapphire'), __('1 Comment', 'sapphire'), __('% Comments', 'sapphire')); ?> <?php edit_post_link(__('Edit', 'sapphire'),''); ?> </p> 

			</div>
	
		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link( __( '&laquo; Older Entries', 'sapphire' ) ); ?></div>
			<div class="alignright"><?php previous_posts_link( __( 'Newer Entries &raquo;', 'sapphire' ) ); ?></div>			
		</div>
		
	<?php else : ?>

		<h2 class="center"><?php _e('Not Found', 'sapphire'); ?></h2>
		<p class="center"><?php _e("Sorry, but you are looking for something that isn't here.", 'sapphire'); ?></p>
		<?php get_search_form(); ?>

	<?php endif; ?>

	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
