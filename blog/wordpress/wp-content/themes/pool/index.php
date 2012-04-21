<?php get_header(); ?>
			
	<div id="bloque">
		
		<div id="noticias">
		
<?php is_tag(); ?>
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
			<div <?php post_class( 'entrada' ); ?>>
				<h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h2>
				<small><?php printf( __('%1$s at %2$s'), get_the_time(get_option('date_format')), get_the_time() ); ?> | <?php printf( __('Posted in %s'), get_the_category_list(', ') ); ?> | <?php comments_popup_link(__('Leave a Comment'), __('1 Comment'), __('% Comments') ) ?> <?php edit_post_link(__('Edit this post'), ' | ', ''); ?><br /><?php the_tags(__('Tags:').' ', ', ', '<br />'); ?></small>
						
			<?php the_content(__("Continue reading").' '.the_title('', '', false)."..."); ?>
							
				<div class="feedback"><?php link_pages('<p><strong>Pages:</strong> ', '</p>', 'number'); ?></div>

			</div>
				
		<?php comments_template(); // Get wp-comments.php template ?>
			
		<?php endwhile; else: ?>
		<h2 class="center">Not Found</h2>
		<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
		<?php endif; ?>

		<?php posts_nav_link(' &#8212; ', __('&laquo; Previous Page'), __('Next Page &raquo;')); ?>
		</div>

<?php get_footer(); ?>
