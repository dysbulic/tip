<?php
/**
 * The template for displaying Archive pages.
 *
 * @package WordPress
 * @subpackage The Morning After
 */
get_header(); ?>

<?php get_template_part( 'top-banner' ); ?>

<?php $content_width = 540; // to override $content_width for the narrower column ?>

<?php $morningafter_options = morningafter_get_theme_options(); ?>

<div id="arch_content" class="column span-14">

	<?php if ( have_posts() ) : ?>

		<div class="column span-3 first">

			<?php if ( is_category() ) { ?>

				<h1 class="archive_name"><?php echo single_cat_title(); ?></h1>

				<div class="archive_meta">

					<div class="archive_feed">
						<?php 
							$cat_obj = $wp_query->get_queried_object(); 
							$cat_id = $cat_obj->term_id;
							echo '<a href="'; get_category_feed_link( $cat, '' ); echo '">RSS feed for this section</a>'; 
						?>
					</div>
						
					<?php $cat_count = $cat_obj->count; ?>
					
					<div class="archive_number">
						<?php _e( 'This category contains','woothemes' );?> <?php echo $cat_count . " " . ($cat_count==1? __( 'post','woothemes' ): __( 'posts','woothemes' )); ?>
					</div>

				</div><!-- end .archive_meta -->

			<?php } elseif ( is_tag() ) { ?>

				<h1 class="archive_name"><?php single_tag_title(); ?></h1>

				<div class="archive_meta">

					<div class="archive_number">
						<?php _e( 'This tag is associated with','woothemes' );?> <?php $tag = $wp_query->get_queried_object(); echo $tag->count; ?> <?php _e( 'posts','woothemes' );?>
					</div>

				</div>

			<?php } elseif ( is_day() ) { ?>
				<h1 class="archive_name"><?php _e( 'Archive for','woothemes' ); ?> <?php the_time( get_option( 'date_format' ) ); ?></h1>

			<?php } elseif ( is_month() ) { ?>
				<h1 class="archive_name"><?php _e( 'Archive for','woothemes' ); ?> <?php the_time( get_option( 'date_format' ) ); ?></h1>

			<?php } elseif ( is_year() ) { ?>
				<h1 class="archive_name"><?php _e( 'Archive for','woothemes' ); ?> <?php the_time( get_option( 'date_format' ) ); ?></h1>

			<?php } ?>

		</div><!-- end .span-3 -->

		<div class="column span-8">

			<?php while ( have_posts() ) : the_post(); ?>

				<div class="archive_post_block clear-fix">
				
					<h3 class="archive_title" id="post-<?php the_ID(); ?>"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s', 'woothemes' ), the_title_attribute( 'echo=0' ) ); ?>"><?php the_title(); ?></a></h3>

					<div class="archive_post_meta"><?php _e( 'By','woothemes' );?> <?php the_author_posts_link(); ?> <span class="dot">&sdot;</span> <?php the_time( get_option( 'date_format' ) ); ?> <span class="dot">&sdot;</span> <?php comments_popup_link( __( 'Leave a Comment', 'woothemes' ), __( '1 Comment', 'woothemes' ), __( '% Comments', 'woothemes' ) ); ?></div>
					
					<?php if ( $morningafter_options['show_full_archive'] == "1" ) the_content( __( 'Continue reading <span class="meta-nav">&raquo;</span>', 'woothemes' ) ); else the_excerpt(); ?>
					
					<?php wp_link_pages(array( 'before' => '<div class="page-navigation"><p><strong>'.__( 'Pages','woothemes' ).':</strong> ', 'after' => '</p></div>', 'next_or_number' => 'number' )); ?>
					
				</div><!-- end .archive_post_block -->

			<?php endwhile; ?>

			<?php if (  $wp_query->max_num_pages > 1 ) : ?>
				<div id="nav-below" class="post-navigation clear-fix">
					<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&laquo;</span> Older posts', 'woothemes' ) ); ?></div>
					<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&raquo;</span>', 'woothemes' ) ); ?></div>
				</div>
			<?php endif; ?>

		</div><!-- end .span-8 -->

		<?php get_sidebar(); ?>

	<?php else : ?>

		<p><?php _e( 'Lost? Go back to the','woothemes' ); ?> <a href="<?php echo home_url(); ?>/"><?php _e( 'home page','woothemes' );?></a>.</p>

	<?php endif; ?>		

</div><!-- end #arch_content -->

<?php get_footer(); ?>