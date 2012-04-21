<?php
/**
* @package WordPress
* @subpackage AutoFocus
*/
get_header(); ?>

<div id="content">

	<?php while ( have_posts() ) : the_post(); ?>

		<div id="nav-above">
			<h1 class="assistive-text">
				<?php _e( 'Post navigation', 'autofocus' ); ?>
			</h1><!-- .assistive-text-->
			<div class="nav-previous">
				<?php previous_post_link( '%link', '&laquo;' ); ?>
			</div><!-- .nav-previous -->
			<div class="nav-next">
				<?php next_post_link( '%link', '&raquo;' ); ?>
			</div><!-- .nav-next -->
		</div><!-- #nav-above -->
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php if ( '' != get_the_post_thumbnail() ) { ?>
				<div id="post-thumbnail">
					<?php the_post_thumbnail( 'autofocus-800x1200' ); ?>
				</div><!-- #post-thumbnail -->
			<?php } ?>
			<h2 class="entry-title">
				<?php the_title(); ?>
			</h2><!-- .entry-title -->
			<div id="entry-content">
				<?php the_content(); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'autofocus' ), 'after' => '</div>' ) ); ?>
				<?php edit_post_link( __( 'Edit', 'autofocus' ), '<span class="edit-link">', '</span>' ); ?>
			</div><!-- #entry-content -->
			<div id="entry-meta">
				<span class="entry-date">
					<?php the_time( 'd M' ); ?>
				</span><!-- .entry-date -->
				<?php
				$categories_list = get_the_category_list( __( ', ', 'autofocus' ) );
				$tag_list = get_the_tag_list( '', __( ', ', 'autofocus' ) );
				if ( is_multi_author() && $tag_list ) {
					printf(__( 'This entry was written by %1$s and published on %2$s at %3$s. It&rsquo;s filed under %4$s and tagged %5$s.', 'autofocus' ),
					esc_html( get_the_author() ),
					get_the_time( get_option( 'date_format' ) ),
					get_the_time( get_option( 'time_format' ) ),
					$categories_list,
					$tag_list );
				} elseif ( !is_multi_author() && $tag_list ) {
					printf( __( 'This entry was published on %1$s at %2$s. It&rsquo;s filed under %3$s and tagged %4$s.', 'autofocus' ),
					get_the_time( get_option( 'date_format' ) ),
					get_the_time( get_option( 'time_format' ) ),
					$categories_list,
					$tag_list );
				} elseif ( is_multi_author() ) {
					printf( __( 'This entry was written by %1$s and published on %2$s at %3$s. It&rsquo;s filed under %4$s.', 'autofocus' ),
					esc_html( get_the_author() ),
					get_the_time( get_option( 'date_format' ) ),
					get_the_time( get_option( 'time_format' ) ),
					$categories_list );
				} else {
					printf( __( 'This entry was published on %1$s at %2$s and is filed under %3$s.', 'autofocus' ),
					get_the_time( get_option( 'date_format' ) ),
					get_the_time( get_option( 'time_format' ) ),
					$categories_list );
				}
				?>
				<span class="bookmark-permalink">
					<?php printf( __( 'Bookmark the <a href="%1$s" title="Permalink to %2$s" rel="bookmark">permalink</a>.', 'autofocus' ), esc_url( get_permalink() ), esc_attr__( the_title_attribute( 'echo=0' ) ) ); ?>
				</span>
				<span class="comments-rss-link">
					<?php printf( __( 'Follow any comments here with the <a href="%1$s" title="RSS feed for %2$s">RSS feed for this post</a>.', 'autofocus' ), get_post_comments_feed_link(), esc_attr__( the_title_attribute( 'echo=0' ) ) ); ?>
				</span><!-- .comments-rss-link -->
			</div><!-- #entry-meta -->
		</div><!-- #post-<?php the_ID(); ?> -->
		<div id="nav-below">
			<h3>
				<?php _e( 'Browse ', 'autofocus' ); ?>
			</h3><!-- #nav-below -->
			<div class="nav-previous">
				<?php previous_post_link( __( 'Older: %link', 'autofocus' ) ); ?>
			</div><!-- .nav-previous -->
			<div class="nav-next">
				<?php next_post_link( __( 'Newer: %link', 'autofocus' ) ); ?>
			</div><!-- .nav-next -->
		</div><!-- #nav-below -->

		<?php
			// If comments are open or we have at least one comment, load up the comment template
			if ( comments_open() || '0' != get_comments_number() )
				comments_template( '', true );
		?>

	<?php endwhile; ?>

</div><!-- #content -->

<?php get_footer(); ?>