<?php
/**
 * @package Spectrum
 */
?>
<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<div class="main-title">
		<?php
			// Let's get all the post content
			$link_content = $post->post_content;

			// And let's find the first url in the post content
			$link_url = wpcom_themes_url_grabber();

			// Let's make the title a link if there's a link in this link post
			if ( ! empty( $link_url ) ) :
		?>
		<h3><a href="<?php echo $link_url; ?>" target="_blank"><?php the_title(); ?></a></h3>
		<?php else : ?>
		<h3><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
		<?php endif; ?>
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
		<?php
		// Sometimes links need descriptions and sometimes they don't ...

		// Let's compare the length of the first url with the length of the post content.
		// If they're one and the same we don't really need to show the post content BECAUSE ...
		// that's just a url and we're already using that url as a href for the title link above BUT ...
		// if they're NOT the same I think we should show that content.
		if ( strlen( $link_url ) != strlen( $link_content ) ) :

		// Let's make any bare URL a clickable link, too.
		add_filter( 'the_content', 'make_clickable' );
		?>
		<?php the_content( 'Read the rest of this entry &raquo;' ); ?>
		<?php wp_link_pages( array( 'before' => '<p>Page: ', 'after' => '</p>', 'next_or_number' => 'number' ) ); ?>
		<?php endif; ?>
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