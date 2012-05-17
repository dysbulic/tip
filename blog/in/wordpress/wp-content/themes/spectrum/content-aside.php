<?php
/**
 * @package Spectrum
 */
?>
<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<div class="post-meta post-author-and-comments">
		<?php if ( is_multi_author() ) {
			printf( __( '<p class="post-author">Written by <strong>%1$s</strong></p><p class="date">on <a href="%2$s" rel="bookmark">%3$s</a></p>', 'spectrum' ),
				get_the_author(),
				get_permalink(),
				get_the_date()
			);
		} else {
			printf( __( '<p class="post-author">Written <p class="date">on <a href="%1$s" rel="bookmark">%2$s</a></p>', 'spectrum' ),
				get_permalink(),
				get_the_date()
			);
		} ?>
		<?php if ( is_multi_author() ) { ?>
			<p class="comment-number"><?php comments_popup_link( __( 'Leave a comment', 'spectrum' ), __( '<strong>1</strong> Comment', 'spectrum' ), __( '<strong>%</strong> Comments', 'spectrum' ) ); ?></p>
		<?php } ?>
	</div>
	<div class="entry">
		<?php the_content( 'Read the rest of this entry &raquo;' ); ?>
		<?php wp_link_pages( array( 'before' => '<p>Page: ', 'after' => '</p>', 'next_or_number' => 'number' ) ); ?>
	</div>
	<div class="post-meta post-category">
		<p class="post-category-title"><strong><?php _e( 'Category:', 'spectrum' ); ?></strong></p>
		<p class="post-category-elements"><?php the_category( ', ' ); ?></p>
		<?php if ( ! is_multi_author() ) { ?>
			<p class="comment-number"><?php comments_popup_link( __( 'Leave a comment', 'spectrum' ), __( '<strong>1</strong> Comment', 'spectrum' ), __( '<strong>%</strong> Comments', 'spectrum' ) ); ?></p>
		<?php } ?>
	</div>
	<?php the_tags( '<div class="post-meta post-tags"><p><strong>' . __('Tagged with:', 'spectrum') . '</strong></p><ul><li>','</li><li>','</li></ul></div>' ); ?>
</div>