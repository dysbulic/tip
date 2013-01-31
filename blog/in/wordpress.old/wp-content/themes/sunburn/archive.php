<?php get_header(); ?>

	<div id="content" class="sanda">

<?php is_tag(); ?>
		<?php if (have_posts()) : ?>

		<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
		<?php /* If this is a category archive */ if (is_category()) { ?>				
		<h2 class="pagetitle"><?php printf( __( 'Archive for the &lsquo;%s&rsquo; Category', 'sunburn' ), '<span>' . single_cat_title( '', false ) . '</span>' ); ?></h2>

		<?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
		<h2 class="pagetitle"><?php printf( __( 'Posts Tagged &lsquo;%s&rsquo;', 'sunburn' ), '<span>' . single_tag_title( '', false ) . '</span>' ); ?></h2>
		
 	  	<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h2 class="pagetitle"><?php printf( __( 'Archive for %s', 'sunburn' ), '<span>' . get_the_date() . '</span>' ); ?></h2>
		
	 	<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h2 class="pagetitle"><?php printf( __( 'Archive for %s', 'sunburn' ), '<span>' . get_the_date( 'F Y' ) . '</span>' ); ?></h2>

		<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
			<h2 class="pagetitle"><?php printf( __( 'Archive for %s', 'sunburn' ), '<span>' . get_the_date( 'Y' ) . '</span>' ); ?></h2>
		
	  	<?php /* If this is a search */ } elseif (is_search()) { ?>
		<h2 class="pagetitle"><?php _e( 'Search Results', 'sunburn' ); ?></h2>
		
	  	<?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h2 class="pagetitle"><?php _e( 'Author Archive', 'sunburn' ); ?></h2>

		<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h2 class="pagetitle"><?php _e( 'Blog Archives', 'sunburn' ); ?></h2>

		<?php } ?>


		<div class="navigation">
			<div class="alignleft"><?php next_posts_link( __( '&laquo; Previous Entries', 'sunburn' ) ); ?></div>
			<div class="alignright"><?php previous_posts_link( __( 'Next Entries &raquo;', 'sunburn' ) ); ?></div>
		</div>

		<?php while ( have_posts() ) : the_post(); ?>
		<div <?php post_class(); ?>>
				<h3 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s', 'sunburn' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h3>
				<small><?php the_time( get_option( 'date_format' ) ); ?></small>
				
				<div class="entry">
					<?php the_excerpt() ?>
				</div>
		
				<p class="postmetadata">
					<?php _e( 'Posted in', 'sunburn' ); ?> <?php the_category(', ') ?>&nbsp;|&nbsp;
					<?php edit_post_link( __( 'Edit', 'sunburn' ), '', ' | '); ?>&nbsp;
					<br />
					<?php the_tags( __( 'Tags: ', 'sunburn' ), ', ', '<br />'); ?>&nbsp;
					<?php comments_popup_link( __( 'Leave a Comment &#187;', 'sunburn' ), __( '1 Comment &#187;', 'sunburn' ), __( '% Comments &#187;', 'sunburn' ) ); ?>
				</p> 
			</div>
	
		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link( __( '&laquo; Previous Entries', 'sunburn' ) ); ?></div>
			<div class="alignright"><?php previous_posts_link( __( 'Next Entries &raquo;', 'sunburn' ) ); ?></div>
		</div>
	
	<?php else : ?>

		<h2 class="center"><?php _e( 'Error 404 - Not Found', 'sunburn' ); ?></h2>

		<p><?php _e( 'Apologies, but the content you requested could not be found. Perhaps searching will help.', 'sunburn' ); ?></p>

		<?php get_search_form(); ?>

	<?php endif; ?>
		
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
