<?php get_header(); ?>
<div id="content">
	<?php if (have_posts()) :?>
		<?php $postCount=0; ?>
		<?php while (have_posts()) : the_post();?>
			<?php $postCount++;?>
	<div <?php post_class('entry entry-' . $postCount); ?> id="post-<?php the_ID(); ?>">
		<div class="entrytitle">
			<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h2> 
			<?php if (!is_page()) { ?>
			<h3><?php the_time(get_option('date_format')) ?></h3>
			<?php } ?>
		</div>
		<div class="entrybody">
			<?php the_content('Read the rest of this entry &raquo;'); ?>
			<?php wp_link_pages(); ?>
		</div>
		
		<div class="entrymeta"><p>Posted in <?php the_category(', ') ?> | <?php edit_post_link(__('Edit','treba'), '', ' | '); ?>  <?php comments_popup_link(__('Leave a Comment &#187;','treba'),__('1 Comment &#187;','treba'),__('% Comments &#187;','treba')); ?><br /><?php the_tags('Tags: ', ', ', '<br />'); ?></p>
		</div>
		
	</div>
	<div class="commentsblock">
		<?php comments_template(); ?>
	</div>
		<?php endwhile; ?>
		<div class="navigation">
			<div class="alignleft"><?php next_posts_link(__('&laquo; Previous Entries','treba')); ?></div>
			<div class="alignright"><?php previous_posts_link(__('Next Entries &raquo;','treba')); ?></div>
		</div>
		
	<?php else : ?>

		<h2>Not Found</h2>
		<div class="entrybody">Sorry, but you are looking for something that isn't here.</div>

	<?php endif; ?>
	<?php get_footer(); ?>
	</div>


<?php get_sidebar(); ?>

</div>
<?php wp_footer(); ?>
</body>
</html>
