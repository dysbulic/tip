<?php
/**
 * @package WordPress
 * @subpackage Blogum
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'clear' ); ?>>
	<header class="post-meta">
		<h1><?php the_title(); ?></h1>

		<?php blogum_comments_popup_link(); ?>

		<?php edit_post_link( __( 'Edit', 'blogum' ), '<div class="post-edit">', '</div>' ); ?>
	</header><!-- .post-meta -->

	<div class="post-content">
		<?php the_content( 'Read More', 'blogum' ); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'blogum' ) . '</span>', 'after' => '</div>' ) ); ?>
	</div><!-- .post-content -->
</article><!-- #post-<?php the_ID(); ?> -->