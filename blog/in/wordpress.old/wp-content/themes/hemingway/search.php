<?php get_header(); ?>

	<div id="primary" class="single-post">
	<div class="inside">
		<div class="primary">

	<?php if (have_posts()) : ?>

		<h1><?php _e('Search Results', 'hemingway'); ?></h1>
		
		<ul class="dates">
		 	<?php while (have_posts()) : the_post(); ?>
			<li>
				<span class="date"><?php the_time(get_option('date_format')) ?></span>
				<a href="<?php the_permalink() ?>"><?php the_title(); ?></a> 
				<?php printf(__('posted in %s', 'hemingway'), get_the_category_list(__(', ', 'hemingway'))); ?>
				<?php if (is_callable('the_tags')) the_tags(__('tagged', 'hemingway') . ' ', ', '); ?>
			</li>
			<?php $results++; ?>
			<?php endwhile; ?>
		</ul>
		
		<div class="navigation">
			<div class="left"><?php next_posts_link(__('&laquo; Previous Entries', 'hemingway')); ?></div>
			<div class="right"><?php previous_posts_link(__('Next Entries &raquo;', 'hemingway')); ?></div>
		</div>
	
	<?php else : ?>

		<h1><?php _e('No posts found. Try a different search?', 'hemingway'); ?></h1>

	<?php endif; ?>
		
	</div>
	
	<div class="secondary">
		<h2><?php _e('Search','hemingway'); ?></h2>
		<div class="featured">
			<p><?php printf(__('You searched for &ldquo;%1$s&rdquo; at %2$s.', 'hemingway'), get_search_query(), get_bloginfo('name')); ?>
			<?php
				if (!$results) _e('There were no results, better luck next time.', 'hemingway');
				elseif (1 == $results) _e('There was one result found. It must be your lucky day.', 'hemingway');
				else printf(__('There were %d results found.', 'hemingway'), $results);
			?>
			</p>
			
		</div>
	</div>
	<div class="clear"></div>
	</div>
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
