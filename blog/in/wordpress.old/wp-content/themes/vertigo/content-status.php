<?php
/**
 * @package Vertigo
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="container">

		<?php if ( is_search() ) : // Only display excerpts for search pages ?>
			<div class="entry-content clear-fix">
				<?php the_excerpt(); ?>
			</div><!-- .entry-content -->
		<?php else : ?>
			<div class="entry-content clear-fix">
				<div class="avatar"><?php echo get_avatar( $post->post_author, '50' ); ?></div>
				<?php the_content( __( 'Read more', 'vertigo' ) ); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages:', 'vertigo' ), 'after' => '</p></div>' ) ); ?>
			</div><!-- .entry-content -->
		<?php endif; ?>

		<?php vertigo_entry_meta(); ?>

		<?php vertigo_entry_info(); ?>

	</div><!-- .container -->
</article><!-- #post-## -->