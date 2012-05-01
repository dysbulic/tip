<?php get_header(); ?>

	<div id="primary" class="single-post">
		<div class="inside">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<div class="primary">
				<h1><?php the_title(); ?></h1>
				<?php the_content('<p class="serif">' . __('Read the rest of this entry &raquo;', 'hemingway') .'</p>'); ?>
				<?php wp_link_pages(); ?>
			</div>
			<hr class="hide" />
			<div class="secondary">
				<h2><?php _e('About this entry', 'hemingway'); ?></h2>
				<div class="featured">
					<p><?php printf(__('You&rsquo;re currently reading &ldquo;%1$s,&rdquo; an entry on %2$s', 'hemingway'), the_title('', '', false), get_bloginfo('name')); ?></p>
                                        <dl>
						<dt><?php _e('Published:', 'hemingway'); ?></dt>
						<dd><?php the_time(get_option('date_format')) ?> / <?php the_time() ?></dd>
					</dl>
					<dl>
						<dt><?php _e('Category:', 'hemingway'); ?></dt>
						<dd><?php the_category(__(', ', 'hemingway')) ?></dd>
					</dl>
					<?php if (is_callable('the_tags')) : ?>
					<dl>
						<dt><?php _e('Tags:', 'hemingway'); ?></dt>
						<dd><?php the_tags(''); ?></dd>
					</dl>
					<?php endif; ?>
					<?php edit_post_link(__('Edit this entry.', 'hemingway'), '<dl><dt>' . __('Edit:', 'hemingway') . '</dt><dd> ', '</dd></dl>'); ?>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<!-- [END] #primary -->
	
	<hr class="hide" />
	<div id="secondary">
		<div class="inside">
			<?php comments_template(); ?>
			
			<?php endwhile; else: ?>
			<p><?php _e('Sorry, no posts matched your criteria.', 'hemingway'); ?></p>
			<?php endif; ?>
		</div>
	</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
