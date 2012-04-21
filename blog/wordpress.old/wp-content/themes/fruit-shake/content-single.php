<?php
/**
 * @package WordPress
 * @subpackage Fruit Shake
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>

		<div class="entry-meta">
			<?php
				printf( __( 'Posted by <span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span> in', 'fruit-shake' ),
					get_author_posts_url( get_the_author_meta( 'ID' ) ),
					esc_attr( sprintf( __( 'View all posts by %s', 'fruit-shake' ), get_the_author() ) ),
					get_the_author()
				 );
			?>
			<span class="cat-links"><?php the_category( ', ' ); ?></span>
			<?php the_tags( '<span class="tag-links">' . __( 'and tagged with', 'fruit-shake' ) . ' </span>', ', ', '' ); ?>
			<time pubdate="" datetime="<?php the_date( 'c' ); ?>" class="entry-date"><?php the_time( get_option( 'date_format' ) ); ?></time>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php if ( 'audio' == get_post_format() ) get_template_part( 'audio-player' ); ?>
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'fruit-shake' ), 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
		<?php edit_post_link( __( '[Edit]', 'fruit-shake' ), '<span class="edit-link fruity">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->
