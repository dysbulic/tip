<?php get_header();?>	
	<div id="main">
	<div id="content">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div <?php post_class(); ?>>
		<div class="page-info"><h2 class="page-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link: %s' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h2>
		<?php /*Posted by <?php the_author(); ?>*/ ?><?php edit_post_link(__('(edit this)')); ?></div>

			<div class="page-content">
				<?php the_content(); ?>
	
				<?php link_pages('<p><strong>' . __( 'Pages:', 'connections' ) . '</strong> ', '</p>', 'number'); ?>
	
			</div>
		</div>
	<?php comments_template(); ?>
	<?php endwhile; endif; ?>
	</div>
	<div id="sidebar">
		<?php get_sidebar(); ?>
	</div>

<?php get_footer();?>
</div>
</div>
</body>
</html>
