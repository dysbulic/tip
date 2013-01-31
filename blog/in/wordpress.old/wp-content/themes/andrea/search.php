<?php
/**
 * @package WordPress
 * @subpackage Andrea
 */
?>
<?php get_header(); ?>

<div id="content" class="group">
<?php if ( have_posts() ) : ?>

	<h2 class="page-title"><?php _e( 'Search Results', 'andrea' ); ?></h2>

<?php while ( have_posts() ) : the_post(); ?>

	<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a><?php if ( comments_open() ) : ?> &nbsp; <a href="<?php comments_link(); ?>" class="comments-link"><?php comments_number( 'Leave a comment', '1 comment', '% comments' ); ?></a><?php endif; ?></h2>

	<div id="post-<?php the_ID(); ?>" <?php post_class( 'entry-content' ); ?>>
		<?php the_excerpt( __( 'Read the rest of this entry &raquo;', 'andrea' ) ); ?>
	</div>

	<div class="meta">
		<p><?php
			printf( __( 'Posted %1$s by %2$s in %3$s', 'andrea' ),
				get_the_date( get_option( 'date_format' ) ),
				sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
					get_author_posts_url( get_the_author_meta( 'ID' ) ),
					esc_attr( sprintf( __( 'View all posts by %s', 'andrea' ), get_the_author() ) ),
					get_the_author()
				),
				get_the_category_list( ', ' )
			);
		?><?php edit_post_link( __( 'Edit', 'andrea' ), '<span class="edit"> &mdash; ', '</span>' ); ?></p>
		<?php if ( the_tags( '<p>' . __( 'Tagged with', 'andrea' ) . ' ', ', ', '</p>' ) ); ?>
	</div>

	<?php if ( comments_open() ) comments_template(); ?>

<?php endwhile; ?>
	<div class="navigation index group">
		<div class="alignleft"><?php next_posts_link( __( '&laquo; Older Entries', 'andrea' ) ); ?></div>
		<div class="alignright"><?php previous_posts_link( __( 'Newer Entries &raquo;', 'andrea' ) ); ?></div>
	</div>
<?php else : ?>
	<h2><?php _e( 'No posts found.', 'andrea' ); ?></h2>
	<div class="warning">
		<p><?php _e( 'Apologies, but we were unable to find what you were looking for. Try a different search?', 'andrea' ); ?></p>
		<?php get_search_form(); ?>
	</div>
<?php endif; ?>

</div><!-- /#content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>