<?php
/**
 * @package WordPress
 * @subpackage Comet
 */
?>

<?php get_header(); ?>

<?php if ( have_posts() ) : ?>

	<?php while ( have_posts() ) : the_post(); ?>

	<!-- post -->
	<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
		<?php the_title( '<h1 class="post-title">', '</h1>' ); ?>
		<div class="post-text">

		<?php the_content(); ?>

		<a href="<?php echo wp_get_attachment_url(); ?>" rel="attachment"><?php printf( __( 'Download %1$s', 'comet' ), '<em>' . get_the_title() . '</em>' ); ?></a>

		</div>
		<div class="post-meta">
			<div class="row">
				<?php if ( comments_open() ) { ?>
					<div class="alignright"><?php
						comments_popup_link(
							__( 'Leave a Comment', 'comet' ),
							__( '1 Comment', 'comet' ),
							__( '% Comments', 'comet' )
						);
					?></div>
				<?php } ?>

				<?php
					/*
					 * Print a sentence like: "by Admin on March 3, 2008".
					 */
					printf( __( 'by %1$s on %2$s', 'comet' ),
						sprintf( '<a href="%1$s" title="%2$s">%3$s</a>',
							esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
							esc_attr( sprintf( __( 'View all posts by %1$s', 'comet' ), get_the_author() ) ),
							esc_html( get_the_author() )
						),
						'<em>' . esc_html( get_the_date() ) . '</em>'
					);
				?>

				&nbsp;&bull;&nbsp; <a href="<?php the_permalink(); ?>" rel="bookmark"><?php _e( 'Permalink', 'comet' ); ?></a>

				<?php edit_post_link( __( 'Edit Post', 'comet' ), ' &nbsp;&bull;&nbsp; ', '' ); ?>

				<div class="row"><?php
					$parent_id = get_post_field( 'post_parent', get_the_ID() );
					$parent_id = absint( $parent_id );
					printf( __( 'This file is attached to %1$s', 'comet' ), '<a href="' . esc_url( get_permalink( $parent_id ) ) . '">' . get_the_title( $parent_id ) . '</a>' );
				?></div>
			</div>
		</div>
	</div>
	<!--/post -->

	<div class="sep"></div>

	<?php comments_template(); ?>

	<?php endwhile; ?>

	<?php else : ?>

	<div class="post">
		<h1 class="post-title"><?php _e( 'Post not found', 'comet' ); ?></h1>
		<div class="post-text">
			<p><?php _e( 'The post you were looking for could not be found.', 'comet' ); ?></p>
		</div>
	</div>

<?php endif; ?>

<?php get_footer(); ?>
