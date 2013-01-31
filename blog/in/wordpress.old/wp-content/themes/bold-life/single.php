<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Bold_Life
 */

get_header(); ?>

<div class="post-wrapper">

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class( 'wide-entry-wrapper' ); ?>>
			<div class="post-date-wrapper">
					<div class="post-date">
						<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'bold-life' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
							<span><?php the_time( 'd' ); ?></span>
							<?php the_time( 'M Y' ); ?>
						</a>
					</div><!-- .post-date -->
				<?php
					$comments_number = get_comments_number();
					if ( comments_open() || 0 < $comments_number ) :
					comments_popup_link(
						__( '<span class="leave-comment">Leave a Comment</span>', 'bold-life' ),
						_x( '<span>1</span> Comment', 'comments number', 'bold-life' ),
						_x( '<span>%</span> Comments', 'comments number', 'bold-life' ),
						'comment-left'
					);
					endif;
				?>
			</div><!-- .post-date-wrapper -->
			<div class="entry-wrapper">
				<div class="entry">
					<div class="entry-inner">
						<h2 class="post-title">
							<?php the_title(); ?>
						</h2>
						<?php
						the_content( __( 'Read more&hellip;', 'bold-life' ) );
						wp_link_pages( array( 'before' => '<div class="post-pages">' . __( 'Pages:', 'bold-life' ), 'after' => '</div>' ) );
						edit_post_link( __( 'Edit', 'bold-life' ), '<div class="edit-link">', '</div>' );
						?>
						<div class="post-meta">
							<?php
							$categories_list = get_the_category_list( __( ', ', 'bold-life' ) );
							$tag_list = get_the_tag_list( '', __( ', ', 'bold-life' ) );
							if ( is_multi_author() && $tag_list ) {
								printf( __( 'Posted by %1$s in %2$s and tagged %3$s', 'bold-life' ), esc_html( get_the_author() ), $categories_list, $tag_list );
							} elseif ( !is_multi_author() && $tag_list ) {
								printf( __( 'Posted in %1$s and tagged %2$s', 'bold-life' ), $categories_list, $tag_list );
							} elseif ( is_multi_author() ) {
								printf( __( 'Posted by %1$s in %2$s', 'bold-life' ), esc_html( get_the_author() ), $categories_list );
							} else {
								printf( __( 'Posted in %1$s', 'bold-life' ), $categories_list );
							}
							?>
						</div><!-- .post-meta -->
						<div id="post-navigation">
							<h3 class="screen-reader-text">
								<?php _e( 'Single Post Navigation', 'bold-life' ); ?>
							</h3>
							<?php previous_post_link( '<div class="nav-previous">%link</div>', __( '<span class="meta-nav">&larr;</span> Older Entry', 'bold-life' ) ); ?>
							<?php next_post_link( '<div class="nav-next">%link</div>', __( 'Newer Entry <span class="meta-nav">&rarr;</span>', 'bold-life' ) ); ?>
						</div><!-- #post-navigation -->
						<div class="clear"></div>
					</div><!-- .entry-inner -->
				</div><!-- .entry -->
				<?php comments_template(); ?>
			</div><!-- .entry-wrapper -->
		</div><!-- #post-<?php the_ID(); ?> -->

	<?php endwhile; else : ?>

		<div id="post-0" class="no-results-wrapper">
			<div class="entry">
				<div class="entry-inner">
					<h2>
						<?php _e( 'Not Found', 'bold-life' ); ?>
					</h2>
					<p>
						<?php _e( 'Sorry, but you are looking for something that isn&rsquo;t here.', 'bold-life' ); ?>
					</p>
				</div><!-- .entry-inner -->
			</div><!-- .entry -->
		</div><!-- #post-0 -->

	<?php endif; ?>

</div><!-- .post-wrapper-->

<?php get_sidebar(); ?>
<?php get_footer(); ?>