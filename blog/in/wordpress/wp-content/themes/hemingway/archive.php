<?php get_header(); ?>
<?php is_tag(); ?>

	<div id="primary" class="single-post">
	<div class="inside">
		<div class="primary">

			<?php if (have_posts()) : ?>
	
			<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
			<?php /* If this is a category archive */ if (is_category()) { ?>
			<h1><?php printf(__('Archive for the &#8216;%s&#8217; Category', 'hemingway'), single_cat_title('', false)); ?></h1>

			<?php /* If this is a tag archive */ } elseif (is_tag()) { ?>
			<h1><?php printf(__('Archive for the &#8216;%s&#8217; Tag', 'hemingway'), single_tag_title('', false) ); ?></h1>
			
			<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
			<h1><?php printf(_c('Archive for %s|Daily archive page', 'hemingway'), get_the_time(__('F jS, Y', 'hemingway'))); ?></h1>
			
		 <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
			<h1><?php printf(_c('Archive for %s|Monthly archive page', 'hemingway'), get_the_time(__('F, Y', 'hemingway'))); ?></h1>
	
			<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
			<h1><?php printf(_c('Archive for %s|Yearly archive page', 'hemingway'), get_the_time(__('Y', 'hemingway'))); ?></h1>
			
			<?php /* If this is a search */ } elseif (is_search()) { ?>
			<h1><?php _e('Search Results', 'hemingway'); ?></h1>
			
			<?php /* If this is an author archive */ } elseif (is_author()) { ?>
			<h1><?php _e('Author Archive', 'hemingway'); ?></h1>
	
			<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
			<h1><?php _e('Blog Archives', 'hemingway'); ?></h1>
	
			<?php } ?>

		 <ul class="dates">
		 	<?php while (have_posts()) : the_post(); ?>
			<li>
				<span class="date"><?php the_time(get_option('date_format')) ?></span>
				<a href="<?php the_permalink() ?>"><?php the_title(); ?></a> 
				<?php printf(__('posted in %s', 'hemingway'), get_the_category_list(__(', ', 'hemingway'))); ?>
				<?php if (is_callable('the_tags')) the_tags(__('tagged', 'hemingway') . ' ', ', '); ?>
			</li>
		
			<?php endwhile; ?>
		</ul>
		
	<div class="navigation">
		<div class="left"><?php next_posts_link(__('&laquo; Previous Entries','hemingway')) ?></div>
		<div class="right"><?php previous_posts_link(__('Next Entries &raquo;','hemingway')) ?></div>
	</div>

	
	<?php else : ?>

		<h1><?php _e('Not Found', 'hemingway'); ?></h1>

	<?php endif; ?>
		
	</div>
	
	<div class="secondary">
		<h2><?php _e('About the archives', 'hemingway'); ?></h2>
		<div class="featured">
			<p><?php printf(__('Welcome to the archives here at %s. Have a look around.', 'hemingway'), get_bloginfo('name')); ?></p>
			
		</div>
	</div>
	<div class="clear"></div>
	</div>
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
