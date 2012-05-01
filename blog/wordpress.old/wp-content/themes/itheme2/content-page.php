<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage iTheme2
 * @since iTheme2 1.1-wpcom
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_post_thumbnail( 'large-feature' ); ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link"><strong>' . __( 'Pages:', 'itheme2' ) . '</strong>', 'after' => '</div>' ) ); ?>
		<?php edit_post_link( __( 'Edit', 'itheme2' ), '<span class="edit-link">', '</span>' ); ?>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
