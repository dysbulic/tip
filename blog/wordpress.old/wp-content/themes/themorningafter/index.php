<?php
/**
 * The main template file.
 *
 * @package WordPress
 * @subpackage The Morning After
 */
get_header(); ?>

<?php $content_width = 540; // to override $content_width for the narrower column ?>

<?php get_template_part( 'top-banner' ); ?>

<div id="arch_content" class="column full-width">
	
	<?php if ( have_posts() ) : ?>

		<div class="column archive-info first">
			<h2 class="archive_name"><?php bloginfo( 'name' ); ?></h2>
			
			<div class="archive_meta">
			
				<div class="archive_feed">
					<a href="<?php bloginfo( 'rss2_url' ); ?>"><?php _e( 'RSS feed for','woothemes' );?> <?php bloginfo( 'name' ); ?></a>
				</div>

			</div>
		</div><!-- end .archive-info -->
		
		<div class="column mid-column">
		
			<?php while ( have_posts() ) : the_post(); ?>

				<div class="archive_post_block clear-fix">
					<h3 class="archive_title" id="post-<?php the_ID(); ?>"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s', 'woothemes' ), the_title_attribute( 'echo=0' ) ); ?>"><?php the_title(); ?></a></h3>

					<div class="archive_post_meta"><?php _e( 'Posted by','woothemes' );?> <?php the_author_posts_link(); ?> <span class="dot">&sdot;</span> <?php the_time( get_option( 'date_format' ) ); ?> <span class="dot">&sdot;</span> <?php comments_popup_link( __( 'Leave a Comment', 'woothemes' ), __( '1 Comment', 'woothemes' ), __( '% Comments', 'woothemes' ) ); ?></div>

					<?php the_excerpt(); ?>
				</div>

			<?php endwhile; ?>

			<div id="nav-below" class="post-navigation clear-fix">
				<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&laquo;</span> Older posts', 'woothemes' ) ); ?></div>
				<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&raquo;</span>', 'woothemes' ) ); ?></div>
			</div>

		</div><!-- end .mid-column -->

		<?php get_sidebar(); ?>
		
	<?php else : ?>

		<?php
			printf( __( '<p>Lost? Go back to the <a href="%s">home page</a></p>', 'woothemes' ),
				get_home_url()
			);
		?>

	<?php endif; ?>

</div><!-- end #arch_content -->

<?php get_footer(); ?>