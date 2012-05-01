<?php
/**
 * @package WordPress
 * @subpackage Fruit Shake
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'fruit-shake' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

		<?php if ( 'post' == $post->post_type ) : ?>
		<div class="entry-meta">
			<?php
				printf( __( 'Posted by <span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span> in', 'fruit-shake' ),
					get_author_posts_url( get_the_author_meta( 'ID' ) ),
					sprintf( esc_attr__( 'View all posts by %s', 'fruit-shake' ), get_the_author() ),
					get_the_author()
				 );
			?>
			
			<span class="cat-links"><?php the_category( ', ' ); ?></span>
			
			<?php the_tags( '<span class="tag-links">' . __( 'and tagged with', 'fruit-shake' ) . ' </span>', ', ', '' ); ?>
			
			<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'fruit-shake' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><time pubdate="" datetime="<?php the_date( 'c' ); ?>" class="entry-date"><?php the_time( get_option( 'date_format' ) ); ?></time></a>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php if ( is_search() ) : // Only display Excerpts for search pages ?>
	<div class="entry-summary">
		<?php the_excerpt( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'fruit-shake' ) ); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">
		<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'fruit-shake' ) ); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'fruit-shake' ), 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->
	<?php endif; ?>

	<footer class="entry-meta">
		<span class="comments-link fruity"><?php comments_popup_link( __( 'Leave a comment', 'fruit-shake' ), __( '1 Comment', 'fruit-shake' ), __( '% Comments', 'fruit-shake' ) ); ?></span>
		<?php edit_post_link( __( '[Edit]', 'fruit-shake' ), '<span class="edit-link fruity">', '</span>' ); ?>
	</footer><!-- #entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->
