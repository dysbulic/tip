<?php get_header() ?>

<div id="content">

<?php if (have_posts()) : ?>

	<?php while (have_posts()) : the_post(); ?>

		<div <?php post_class('post'); ?> id="post-<?php the_ID(); ?>">
			<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent link to %s' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h2>
			<p class="metadata">
			<?php printf(__('Posted by %1$s.'), get_the_author()); ?>
			<?php edit_post_link(__('Edit'), ' | ', ''); ?></p>
			<div class="entry">
				<?php the_content('<span class="more-link">'.__('Continue reading', 'springloaded').'</span>'); ?>
				<?php wp_link_pages(); ?>
			</div>
		</div>

		<?php comments_template(); ?>

	<?php endwhile; endif; ?>

</div><!-- /content -->

</div><!-- /main -->

<?php get_sidebar() ?>

<?php get_footer() ?>
