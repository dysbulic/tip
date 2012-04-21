<?php
/**
 * @package WordPress
 * @subpackage Andrea
 */
?>
<?php get_header(); ?>

<div id="content" class="group">
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

	<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a><?php if ( comments_open() ) : ?> &nbsp; <a href="<?php comments_link(); ?>" class="comments-link"><?php comments_number( 'Leave a comment', '1 comment', '% comments' ); ?></a><?php endif; ?></h2>
	
	<div id="post-<?php the_ID(); ?>" <?php post_class( 'entry-content' ); ?>>
		<?php the_content( __( 'Read the rest of this entry &raquo;', 'andrea' ) ); ?>
		<?php wp_link_pages(); ?>
	</div>

	<div class="meta">
		<p><?php
			printf( __( 'Posted %1$s by <strong>%2$s</strong> in %3$s', 'andrea' ),
				get_the_date( get_option( 'date_format' ) ),
				sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
					get_author_posts_url( get_the_author_meta( 'ID' ) ),
					sprintf( esc_attr__( 'View all posts by %s', 'andrea' ), get_the_author() ),
					get_the_author()
				),
				get_the_category_list( ', ' )
			);
		?><?php edit_post_link( __( 'Edit', 'andrea' ), '<span class="edit"> &mdash; ', '</span>' ); ?></p>
		<?php if ( the_tags( '<p>' . __( 'Tagged with', 'andrea' ) . ' ', ', ', '</p>' ) ); ?>
	</div>

	<?php if ( comments_open() ) comments_template(); ?>

<?php endwhile; else: ?>
	<div class="warning">
		<p><?php _e( "Sorry, but you are looking for something that isn't here.", 'andrea' ); ?></p>
	</div>
<?php endif; ?>

	<div class="navigation index group">
		<div class="alignleft"><?php next_posts_link( __( '&laquo; Older Entries', 'andrea' ) ); ?></div>
		<div class="alignright"><?php previous_posts_link( __( 'Newer Entries &raquo;', 'andrea' ) ); ?></div>
	</div>

</div><!-- /#content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>