<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Chateau
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="post-title">
		<h1><?php the_title(); ?></h1>
	</header><!-- end .post-title -->
	<div class="post-content clear-fix">
		<div class="post-entry">
			<?php the_content( __( 'Continue reading &raquo;', 'chateau' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'chateau' ) . '</span>', 'after' => '</div>' ) ); ?>
			<?php edit_post_link( __( 'Edit', 'chateau' ), '<p>[', ']</p>'); ?>
		</div>
	</div><!-- end .post-content -->
</article><!-- #post-<?php the_ID(); ?> -->