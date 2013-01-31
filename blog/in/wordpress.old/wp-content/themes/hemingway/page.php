<?php get_header(); ?>

	<div id="primary">
	<div class="inside">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<h1><?php the_title(); ?></h1>
		<?php the_content('<p class="serif">' . __('Read the rest of this page &raquo;', 'hemingway') . '</p>'); ?>
	
		<?php wp_link_pages('<p><strong>' . __('Pages:', 'hemingway') . '</strong> ', '</p>', 'number'); ?>
		<br class="clear" />
		<?php edit_post_link(__('Edit this entry.', 'hemingway'), '<p>', '</p>'); ?>

	<?php endwhile; endif; ?>
	</div>
	</div>

	<hr class="hide" />
	<div id="secondary">
		<div class="inside">
			<?php comments_template(); ?>
	</div>
	</div>
<?php get_sidebar(); ?>

<?php get_footer(); ?>
