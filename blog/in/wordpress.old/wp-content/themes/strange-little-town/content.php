<?php
/**
 * @package WordPress
 * @subpackage StrangeLittleTown
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'contain' ); ?>>
	<header class="contain">
		<?php if ( is_singular() ) : ?>
			<?php the_title( '<h1>', '</h1>' ); ?>
		<?php else : ?>
			<?php the_title( '<h1><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' ); ?>
		<?php endif; ?>

		<?php edit_post_link( __( 'Edit', 'strange-little-town' ) ); ?>

		<span class="about-this-post"><?php
			/* translators: %1$s is the date. %2$s is the author's name. */
			printf( __( 'Published %1$s by %2$s', 'strange-little-town' ), '<a href="' . esc_url( get_permalink() ) . '"><span class="date-published">' . esc_html( get_the_time( get_option( 'date_format' ) ) ) . '</span></a>', '<span>' . esc_html( get_the_author() ) . '</span>' );
		?></span>
	</header>

	<div class="entry-content contain">
		<?php the_content( __( 'Read the rest of this entry &rarr;', 'strange-little-town' ) ); ?>
	</div>

	<footer class="entry-meta">

		<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
			<p class="comment-link"><?php
				comments_popup_link(
					__( 'Leave a Comment', 'strange-little-town' ),
					__( '1 comment',       'strange-little-town' ),
					__( '% comments',      'strange-little-town' )
				);
			?></p>
		<?php endif; ?>

		<?php if ( is_object_in_taxonomy( get_post_type(), 'category' ) ) : ?>
		<p><?php
			/* translators: %1$s is a comma-separated list of categories. */
			printf( __( 'Posted in: %1$s', 'strange-little-town' ), '<span>'. get_the_category_list( __( ', ', 'strange-little-town' ) ) . '</span>' );
		?></p>
		<?php endif; ?>

		<?php
			/* translators: Both strings end with a space. */
			the_tags( '<p>' . __( 'Tagged: ', 'strange-little-town' ) . '<span>', __( ', ', 'strange-little-town' ), '</span></p>' );
		?>
		<?php
			wp_link_pages( array(
				'after'       => '</span></p>',
				'before'      => '<p class="paginated-post">' . __( 'Pages: ', 'strange-little-town' ) . '<span>',
			) );
		?>
	</footer>

</article>