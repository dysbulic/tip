<?php get_header(); ?>

	<div id="content">
		
	<?php if (have_posts()) : ?>
		
		<?php while (have_posts()) : the_post(); ?>

						
					<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
						<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent link to %s' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h2>
						<div class="data"><?php the_time(get_option('date_format')) ?> - <?php comments_popup_link(__('Leave a Response','daydream'),__('One Response','daydream'),__('% Responses','daydream')); ?></div>
						
						<div class="entry">
							<?php the_content(__('Read the rest of this entry &raquo;','daydream')); ?>
							<?php link_pages('<p><strong>' . __('Pages:', 'daydream')  . '</strong> ', '</p>', 'number'); ?>
						</div>

							<p class="postmetadata">
								<?php printf(__('Categorized in %s', 'daydream'), get_the_nice_category(', ', ' ' . __('and', 'daydream') . ' ')); ?> <?php edit_post_link(__('Edit', 'daydream'), ' | ', ''); ?>
<br />
<?php the_tags('Tags: ', ', ', ''); ?>
							</p>

					</div>
				
				<?php endwhile; ?>
		
				<div class="navigation">
					<div class="alignleft"><?php next_posts_link(__('&laquo; Previous Entries','daydream')) ?></div>
					<div class="alignright"><?php previous_posts_link(__('Next Entries &raquo;','daydream')) ?></div>
				</div>
		
	<?php else : ?>

		<h4>Not Found</h4>
		<p class="center"><?php _e("Sorry, but you are looking for something that isn't here.", 'daydream'); ?></p>
		<?php include (TEMPLATEPATH . "/searchform.php"); ?>

	<?php endif; ?>

	</div>
	

<?php get_sidebar(); ?>

<?php get_footer(); ?>
