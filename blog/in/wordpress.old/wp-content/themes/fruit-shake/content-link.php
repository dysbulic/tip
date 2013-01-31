<?php
/**
 * @package WordPress
 * @subpackage Fruit Shake
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
		$link_url = get_permalink();
	
		if ( false != fruit_shake_url_grabber() )
			$link_url = fruit_shake_url_grabber();
	?>		
	<a href="<?php esc_attr_e( $link_url ); ?>" title="<?php printf( esc_attr__( 'Link to %s', 'fruit-shake' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
		<header class="entry-header">
			<h1 class="entry-title">
				<?php the_title(); ?>&nbsp;<span>&rarr;</span>
			</h1>
		</header><!-- .entry-header -->
	</a>

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
		<?php
			printf( __( 'Posted by <span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>', 'fruit-shake' ),
				get_author_posts_url( get_the_author_meta( 'ID' ) ),
				esc_attr( sprintf( __( 'View all posts by %s', 'fruit-shake' ), get_the_author() ) ),
				get_the_author()
			 );
		?>

		<a class="entry-date-link" href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'fruit-shake' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><time pubdate="" datetime="<?php the_date( 'c' ); ?>" class="entry-date"><?php the_time( get_option( 'date_format' ) ); ?></time></a>
		
		<?php if ( comments_open() ) : ?>
		<span class="sep"> | </span>
		<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'fruit-shake' ), __( '1 Comment', 'fruit-shake' ), __( '% Comments', 'fruit-shake' ) ); ?></span>
		<?php endif; ?>
		<?php edit_post_link( __( '[Edit]', 'fruit-shake' ), '<span class="sep"> | </span> <span class="edit-link">', '</span>' ); ?>
	</footer><!-- #entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->
