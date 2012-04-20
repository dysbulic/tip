<?php get_header(); ?>


	<div id="primary" class="twocol-stories">
		<div class="inside">
			<?php
				// Here is the call to only make two posts show up on the homepage REGARDLESS of your options in the control panel
				query_posts('showposts=2');
			?>
			<?php if (have_posts()) : ?>
				<?php $first = true; $count = 0; ?>
				<?php while (have_posts()) : the_post(); ?>
					<?php if ( $count < 2) { ?>
					<?php $count++; ?>
					<div class="story<?php if($first == true) echo " first" ?>">
						<h3><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s', 'hemingway' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h3>
						<?php the_excerpt() ?>
						<div class="details">
							<?php printf(__('Posted at %1$s on %2$s', 'hemingway'), get_the_time(), get_the_time(get_option('date_format'))) ; ?> | <?php comments_popup_link(__('leave a comment', 'hemingway'), __('1 comment', 'hemingway'), __('% comments', 'hemingway')); ?> | <?php printf(__('Filed Under: %s', 'hemingway'), get_the_category_list(__(', ', 'hemingway'))); ?> | <?php if (is_callable('the_tags')) the_tags(__('Tagged:', 'hemingway') . ' ', ', ', ' | '); ?> <span class="read-on"><a href="<?php the_permalink() ?>"><?php _e('read on', 'hemingway'); ?></a></span>
						</div>
					</div>
					<?php $first = false; ?>
					<?php } ?>
				<?php endwhile; ?>
			<?php else : ?>
		
				<h2 class="center"><?php _e('Not Found', 'hemingway'); ?></h2>
				<p class="center"><?php _e("Sorry, but you are looking for something that isn't here.", 'hemingway'); ?></p>
				<?php include (TEMPLATEPATH . "/searchform.php"); ?>
		
			<?php endif; ?>
				
			<div class="clear"></div>
		</div>
	</div>
	<!-- [END] #primary -->



<?php get_sidebar(); ?>

<?php get_footer(); ?>
