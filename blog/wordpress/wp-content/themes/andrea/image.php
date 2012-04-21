<?php
/**
 * @package WordPress
 * @subpackage Andrea
 */
?>
<?php get_header(); ?>

<div id="content" class="group">
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

	<h2 class="entry-title"><a href="<?php echo get_permalink( $post->post_parent ); ?>" rev="attachment"><?php echo get_the_title( $post->post_parent ); ?></a> &raquo; <?php the_title(); ?><?php if ( comments_open() ) : ?> &nbsp; <a href="<?php comments_link(); ?>" class="comments-link"><?php comments_number( 'Leave a comment', '1 comment', '% comments' ); ?></a><?php endif; ?></h2>

	<div id="post-<?php the_ID(); ?>" <?php post_class( 'entry-content' ); ?>>
		<p class="attachment"><a href="<?php echo wp_get_attachment_url( $post->ID ); ?>"><?php echo wp_get_attachment_image( $post->ID, 'auto' ); ?></a></p>
		<?php the_content( __( 'Read the rest of this entry &raquo;', 'andrea' ) ); ?>
		<div class="navigation image group">
			<div class="alignleft"><?php previous_image_link(); ?></div>
			<div class="alignright"><?php next_image_link(); ?></div>
		</div>
		<?php wp_link_pages(); ?>
	</div>

	<div class="meta">
		<p><?php
			printf( __( 'Posted %1$s by %2$s', 'andrea' ),
				get_the_date( get_option( 'date_format' ) ),
				sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
					get_author_posts_url( get_the_author_meta( 'ID' ) ),
					sprintf( esc_attr__( 'View all posts by %s', 'andrea' ), get_the_author() ),
					get_the_author()
				)
			);
		?><?php edit_post_link( __( 'Edit', 'andrea' ), '<span class="edit"> &mdash; ', '</span>' ); ?></p>
	</div>

	<?php if ( comments_open() ) comments_template(); ?>

<?php endwhile; else: ?>
	<div class="warning">
		<p><?php _e( "Sorry, but you are looking for something that isn't here.", 'andrea' ); ?></p>
	</div>
<?php endif; ?>

</div><!-- /#content -->

<?php get_footer(); ?>