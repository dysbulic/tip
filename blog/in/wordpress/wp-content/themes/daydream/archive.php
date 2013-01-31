<?php get_header(); ?>

	<div id="content" class="sanda">

		<?php if (have_posts()) : ?>

			<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
			
			<?php /* If this is a category archive */ if (is_category()) { ?>				
				<h2 class="pagetitle"><?php printf(__('Archive for the &#8216;%s&#8217; Category', 'daydream'), single_cat_title('', false)); ?></h2>
			
			<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
				<h2 class="pagetitle"><?php printf(_c('Archive for %s|Daily archive page', 'daydream'), get_the_time(__('F jS, Y', 'daydream'))); ?></h2>
			
			<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
				<h2 class="pagetitle"><?php printf(_c('Archive for %s|Monthly archive page', 'daydream'), get_the_time(__('F, Y', 'daydream'))); ?></h2>
	
			<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
				<h2 class="pagetitle"><?php printf(_c('Archive for %s|Yearly archive page', 'daydream'), get_the_time(__('Y', 'daydream'))); ?></h2>
			
			<?php /* If this is a search */ } elseif (is_search()) { ?>
				<h2 class="pagetitle"><?php printf(__('Search Results for %s', 'daydream'), get_search_query()); ?></h2>
			
			<?php /* If this is an author archive */ } elseif (is_author()) { ?>
				<h2 class="pagetitle"><?php _e('Author Archive', 'daydream'); ?></h2>
	
			<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
				<h2 class="pagetitle"><?php _e('Blog Archives', 'daydream'); ?></h2>
	
			<?php } ?>
	
	
				<?php while (have_posts()) : the_post(); ?>
				
					<div <?php post_class(); ?>>
				
						<h3 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent link to %s' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a><br />
						<?php the_time(get_option('date_format')) ?></h3>
		
						<div class="entry">
							<?php the_excerpt() ?>
						</div>
				
						<p class="postmetadata"><?php _e('Posted in','daydream'); ?> <?php the_category(', ') ?> | <?php the_tags( __( 'Tagged' ) . ': ', ', ', ' | '); ?> <?php edit_post_link(__('Edit','daydream'), '', ' | '); ?>  <?php comments_popup_link(__('Leave a Comment &#187;','daydream'), __('1 Comment &#187;','daydream'), __('% Comments &#187;','daydream')); ?></p>
		
					</div>
			
				<?php endwhile; ?>
	
	
			<?php 
			
				// This young snippet fixes the problem of a grey navigation bar
				// when there is nothing to fill it, a bit pointless having it sitting
				// there all empty and unfufilled
				
				$numposts = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish'");
				$perpage = $wpdb->get_var("SELECT option_value FROM $wpdb->options WHERE option_name = 'posts_per_page'");
	
				if ($numposts > $perpage) {
				
			?>
					
					<div class="navigation">
						<div class="alignleft"><?php next_posts_link(__('&laquo; Previous Entries','daydream')) ?></div>
						<div class="alignright"><?php previous_posts_link(__('Next Entries &raquo;','daydream')) ?></div>
					</div>
					
			<?php }	?>
	
		<?php else : ?>

			<h2><?php _e('No Data Found', 'daydream'); ?></h2>
			<?php get_search_form(); ?>

		<?php endif; ?>
		
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
