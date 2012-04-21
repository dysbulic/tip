<?php
/**
 * @package WordPress
 * @subpackage Andrea
 */
?>
<?php get_header(); ?>

<div id="content" class="group">
<?php if ( have_posts() ) : ?>

	<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
	<?php /* If this is a category archive */ if ( is_category() ) { ?>
	<h2 class="archive-title"><?php printf( __( 'Archive for the &#8216;<strong>%s</strong>&#8217; Category', 'andrea' ), single_cat_title( '', false ) ); ?></h2>
	<?php /* If this is a tag archive */ } elseif ( is_tag() ) { ?>
	<h2 class="archive-title"><?php printf( __( 'Archive for the &#8216;<strong>%s</strong>&#8217; Tag', 'andrea' ), single_tag_title( '', false ) ); ?></h2>
	<?php /* If this is a daily archive */ } elseif ( is_day() ) { ?>
	<h2 class="archive-title"><?php printf( __( 'Archive for <strong>%s</strong>', 'andrea' ), get_the_time( get_option( 'date_format' ) ) ); ?></h2>
	<?php /* If this is a monthly archive */ } elseif ( is_month() ) { ?>
	<h2 class="archive-title"><?php printf( __( 'Archive for <strong>%s</strong>', 'andrea' ), get_the_time( 'F Y' ) ); ?></h2>
	<?php /* If this is a yearly archive */ } elseif ( is_year() ) { ?>
	<h2 class="archive-title"><?php printf( __( 'Archive for <strong>%s</strong>', 'andrea' ), get_the_time( 'Y' ) ); ?></h2>
	<?php /* If this is an author archive */ } elseif ( is_author() ) { ?>
	<h2 class="archive-title"><?php _e( 'Author Archive', 'andrea' ); ?></h2>
	<?php /* If this is a paged archive */ } elseif ( isset( $_GET['paged'] ) && !empty( $_GET['paged'] ) ) { ?>
	<h2 class="archive-title"><?php _e( 'Archives', 'andrea' ); ?></h2>
	<?php } ?>

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
