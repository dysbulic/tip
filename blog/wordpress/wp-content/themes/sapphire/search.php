<?php get_header(); ?>

	<div id="content" class="narrowcolumn">

	<?php if (have_posts()) : ?>

		<h2 class="pagetitle"><?php _e('Search Results', 'sapphire'); ?></h2>
		
		<div class="navigation">
			<div class="alignleft"><?php next_posts_link( __( '&laquo; Older Entries', 'sapphire' ) ); ?></div>
			<div class="alignright"><?php previous_posts_link( __( 'Newer Entries &raquo;', 'sapphire' ) ); ?></div>			
		</div>


		<?php while (have_posts()) : the_post(); ?>
				
			<div <?php post_class(); ?>>
				<h3 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent Link to', 'sapphire'); ?> <?php the_title(); ?>"><?php the_title(); ?></a></h3>
				<small><?php the_time(get_option('date_format')) ?></small>
				
				<div class="entry">
					<?php the_excerpt() ?>
				</div>
		
				<p class="postmetadata"><?php _e('Posted in', 'sapphire'); ?> <?php the_category(', ') ?> <br /><?php the_tags(__('<strong>Tags:</strong>', 'sapphire') .' ', ', ', '<br />'); ?><strong>|</strong> <?php edit_post_link(__('Edit', 'sapphire'),'','<strong>|</strong>'); ?>  <?php comments_popup_link(__('No Comments &#187;', 'sapphire'), __('1 Comment &#187;', 'sapphire'), __('% Comments &#187;', 'sapphire')); ?></p> 

			</div>
	
		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link( __( '&laquo; Older Entries', 'sapphire' ) ); ?></div>
			<div class="alignright"><?php previous_posts_link( __( 'Newer Entries &raquo;', 'sapphire' ) ); ?></div>			
		</div>
	
	<?php else : ?>

		<h2 class="center"><?php _e('Not Found', 'sapphire'); ?></h2>
		<?php include (TEMPLATEPATH . '/searchform.php'); ?>

	<?php endif; ?>
		
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
