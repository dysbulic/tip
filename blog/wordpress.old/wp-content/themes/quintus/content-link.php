<?php
/**
 * @package WordPress
 * @subpackage Quintus
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title">
			<?php
				$link_url = get_permalink();

				if ( false != quintus_url_grabber() )
					$link_url = quintus_url_grabber();
			?>
			<a href="<?php esc_attr_e( $link_url ); ?>" title="<?php printf( esc_attr__( 'Link to %s', 'quintus' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
		</h1>

		<?php if ( 'post' == $post->post_type ) : ?>
		<div class="entry-meta">
			<a class="entry-date" title="<?php the_time( 'F j Y' ); ?>" href="<?php the_permalink(); ?>">
				<time datetime="<?php the_time( 'c' ); ?>" pubdate><?php the_time( 'M' . '<b>' . 'j' . '</b>' ); ?></time>
			</a>
			<?php if ( is_multi_author() ) : ?>
			<span class="entry-byline">
			<?php
				printf( __( 'by <span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>', 'quintus' ),
					get_author_posts_url( get_the_author_meta( 'ID' ) ),
					esc_attr( sprintf( __( 'View all posts by %s', 'quintus' ), get_the_author() ) ),
					get_the_author()
				);
			?>
			</span>
			<?php endif; ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php if ( is_search() ) : // Only display Excerpts for search pages ?>
	<div class="entry-summary">
		<?php the_excerpt( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'quintus' ) ); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">
		<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'quintus' ) ); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'quintus' ), 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->
	<?php endif; ?>

	<footer class="entry-meta">
	<?php if ( comments_open() || ( '0' != get_comments_number() && ! comments_open() ) ) : ?>
		<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'quintus' ), __( '1 Comment', 'quintus' ), __( '% Comments', 'quintus' ) ); ?></span>
	<?php endif; ?>
		<span class="cat-links"><span class="entry-utility-prep entry-utility-prep-cat-links"><?php _e( 'Posted in ', 'quintus' ); ?></span><?php the_category( ', ' ); ?></span>
		<?php the_tags( '<span class="tag-links">' . __( 'Tagged ', 'quintus' ) . '', ', ', '</span>' ); ?>
		<?php edit_post_link( __( 'Edit', 'quintus' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- #entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->
