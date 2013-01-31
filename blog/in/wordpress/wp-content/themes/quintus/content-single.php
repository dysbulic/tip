<?php
/**
 * @package WordPress
 * @subpackage Quintus
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>

		<div class="entry-meta">
			<a class="entry-date" title="<?php echo esc_attr( get_the_time( 'F j Y' ) ); ?>" href="<?php echo esc_url( get_permalink() ); ?>">
				<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" pubdate><?php the_time( 'M' . '<b>' . 'j' . '</b>' ); ?></time>
			</a>
			<span class="entry-byline">
			<?php
				printf( __( 'by <span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>', 'quintus' ),
					esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
					esc_attr( sprintf( __( 'View all posts by %s', 'quintus' ), get_the_author() ) ),
					get_the_author()
				);
			?>
			</span>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'quintus' ), 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
		<?php
			$tag_list = get_the_tag_list( '', ', ' );
			if ( '' != $tag_list ) {
				$utility_text = __( 'This entry was posted in %1$s and tagged %2$s.', 'quintus' );
			} else {
				$utility_text = __( 'This entry was posted in %1$s.', 'quintus' );
			}
			printf(
				$utility_text,
				get_the_category_list( ', ' ),
				$tag_list
			);
		?>

		<?php edit_post_link( __( 'Edit', 'quintus' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->
