<?php
/**
 * @package WordPress
 * @subpackage Blogum
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'clear' ); ?>>
	<header class="post-meta">
		<h1><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'blogum' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

		<?php blogum_post_data(); ?>

		<?php blogum_comments_popup_link(); ?>

		<?php edit_post_link( __( 'Edit', 'blogum' ), '<div class="post-edit">', '</div>' ); ?>
	</header><!-- .post-meta -->

	<?php if ( is_search() ) : // Only display Excerpts for Search ?>
		<div class="post-content">
			<?php the_excerpt(); ?>
		</div><!-- .post-content -->
	<?php else : ?>
		<div class="post-content">
			<?php the_content( 'Read More', 'blogum' ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'blogum' ) . '</span>', 'after' => '</div>' ) ); ?>
		</div><!-- .post-content -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->