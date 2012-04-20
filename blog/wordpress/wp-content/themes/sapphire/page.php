<?php get_header(); ?>

	<div id="content" class="narrowcolumn">

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div <?php post_class('post'); ?>>
		<h2 id="post-<?php the_ID(); ?>"><?php the_title(); ?></h2>
			<div class="entrytext">
				<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>
	
				<?php link_pages('<p><strong>' . __('Pages:', 'sapphire') . '</strong> ', '</p>', 'number'); ?>
	
			</div>
		</div>
	  <?php endwhile; endif; ?>
	<?php edit_post_link(__('Edit this entry.', 'sapphire'), '<p>', '</p>'); ?>
	<?php comments_template(); ?>
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
