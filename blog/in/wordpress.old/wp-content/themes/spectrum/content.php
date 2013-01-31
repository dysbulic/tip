<?php
/**
 * @package Spectrum
 */
?>
<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<div class="main-title">
		<h3><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
		<div class="post-date">
			<?php spectrum_date(); ?>
		</div>
	</div>
	<?php if ( is_multi_author() ) { ?>
		<div class="post-meta post-author-and-comments">
			<p class="post-author"><?php printf( __( 'Written by <strong>%1$s</strong>', 'spectrum' ), get_the_author() ); ?></p>
			<p class="comment-number"><?php comments_popup_link( __( 'Leave a comment', 'spectrum' ), __( '<strong>1</strong> Comment', 'spectrum' ), __( '<strong>%</strong> Comments', 'spectrum' ) ); ?></p>
		</div>
	<?php } ?>
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