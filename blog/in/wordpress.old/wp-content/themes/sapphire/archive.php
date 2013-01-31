<?php get_header(); ?>

	<div id="content" class="narrowcolumn">

<?php is_tag(); ?>
		<?php if (have_posts()) : ?>

		 <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
<?php /* If this is a category archive */ if (is_category()) { ?>				
		<h2 class="pagetitle"><?php printf(__('Archive for the &#8216;%s&#8217; category', 'sapphire'), single_cat_title('', false)); ?></h2>


<?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
        <h2 class="pagetitle"><?php printf(__('Posted tagged &#8216;%s&#8217;', 'sapphire'), single_tag_title('', false)); ?></h2>
		
 	  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h2 class="pagetitle"><?php printf(__('Archive for %s', 'sapphire'), get_the_time(get_option('date_format'))); ?></h2>
		
	 <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h2 class="pagetitle"><?php printf(__('Archive for %s', 'sapphire'), get_the_time('F Y')); ?></h2>

		<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h2 class="pagetitle"><?php printf(__('Archive for %s', 'sapphire'), get_the_time('Y')); ?></h2>
		
	  <?php /* If this is a search */ } elseif (is_search()) { ?>
		<h2 class="pagetitle"><?php _e('Search Results', 'sapphire'); ?></h2>
		
	  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h2 class="pagetitle"><?php _e('Author Archive', 'sapphire'); ?></h2>

		<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h2 class="pagetitle"><?php _e('Blog Archives', 'sapphire'); ?></h2>

		<?php } ?>


		<div class="navigation">
			<div class="alignleft">&laquo; <?php bloginfo('name'); ?> <a href="<?php bloginfo('url'); ?>"><?php _e('home page', 'sapphire'); ?></a></div>
			<div class="alignleft"><?php next_posts_link( __( '&laquo; Older Entries', 'sapphire' ) ); ?></div>
			<div class="alignright"><?php previous_posts_link( __( 'Newer Entries &raquo;', 'sapphire' ) ); ?></div>			
		</div>

		<?php while (have_posts()) : the_post(); ?>
		<div <?php post_class(); ?>>
				<h3 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent link to %s', 'sapphire'), the_title_attribute('echo=0')); ?>"><?php the_title(); ?></a></h3>
				<small><?php the_time(get_option('date_format')) ?></small>
				
				<div class="entry">
					<?php the_excerpt() ?>
				</div>
		
				<p class="postmetadata"><strong><?php _e('Categories:', 'sapphire'); ?></strong> <?php the_category(', ') ?> <br /><?php the_tags(__('<strong>Tags:</strong> ', 'sapphire'), ', ', '<br />'); ?><?php edit_post_link(__('Edit', 'sapphire'),''); ?> <br /><strong><?php _e('Comments:', 'sapphire'); ?></strong> <?php comments_popup_link(__('Be the first to comment', 'sapphire'), __('1 Comment', 'sapphire'), __('% Comments', 'sapphire')); ?></p>

			</div>
	
		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link( __( '&laquo; Older Entries', 'sapphire' ) ); ?></div>
			<div class="alignright"><?php previous_posts_link( __( 'Newer Entries &raquo;', 'sapphire' ) ); ?></div>			
		</div>
	
	<?php else : ?>

		<h2 class="center"><?php _e('Not Found', 'sapphire'); ?></h2>
		<?php get_search_form(); ?>

	<?php endif; ?>
		
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
