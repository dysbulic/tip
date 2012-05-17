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
	<?php if ( is_singular() ) { ?>
		<h1 class="post-title"><?php the_title(); ?></h1>
	<?php } else { ?>
		<h1 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
	<?php } ?>
		<div class="post-text">
		<?php the_content( __( 'Read the full post &raquo;', 'comet' ) ); ?>

		<?php
		wp_link_pages( array(
			'before'         => '<div class="post-pages">' . __( 'Pages', 'comet' ) . ':',
			'after'          => '</div>',
			'next_or_number' => 'number',
			'pagelink'       => '<span>%</span>',
		) );
		?>
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

				<span class="byline-post-date"><?php
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
				?> &nbsp;&bull;&nbsp; </span>

				<a href="<?php the_permalink(); ?>" rel="bookmark"><?php _e( 'Permalink', 'comet' ); ?></a>

				<?php edit_post_link( __( 'Edit Post', 'comet' ), ' &nbsp;&bull;&nbsp; ', '' ); ?>
			</div>

			<?php
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( __( ', ', 'comet' ) );
				if ( $categories_list ) :
			?>
			<div class="row"><?php printf( __( 'Posted in %1$s', 'comet' ), $categories_list ); ?></div>
			<?php endif; // End if $categories_list ?>

			<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', __( ', ', 'comet' ) );
				if ( $tags_list ) :
			?>
			<div class="row"><?php printf( __( 'Tagged %1$s', 'comet' ), $tags_list ); ?></div>
			<?php endif; // End if $tags_list ?>

		</div>
		<div class="print-view">
			<p><em><?php printf( __( 'Posted by %1$s on %2$s', 'comet' ), esc_html( get_the_author() ), esc_html( get_the_date() ) ); ?></em></p>
			<p><?php the_permalink(); ?></p>
		</div>
	</div>
	<!--/post -->

	<?php if ( is_singular() ) { ?>
	<div class="post post-nav">
		<?php previous_post_link( '<div class="alignleft"><i>' . __( 'Previous Post', 'comet' ) . '</i><br />%link</div>' ); ?>
		<?php next_post_link( '<div class="alignright"><i>' . __( 'Next Post', 'comet' ) . '</i><br />%link</div>' ); ?>
	</div>
	<?php } ?>

	<div class="sep"></div>

	<?php comments_template(); ?>

	<?php endwhile; ?>

	<div class="navigation">
		<div class="alignleft"><?php next_posts_link( '&laquo; ' . __( 'Older Posts', 'comet' ) ); ?></div>
		<div class="alignright"><?php previous_posts_link( __( 'Newer Posts', 'comet' ) . ' &raquo;' ); ?></div>
	</div>

	<?php else : ?>

	<div class="post">
		<h1 class="post-title"><?php _e( 'Post not found', 'comet' ); ?></h1>
		<div class="post-text">
			<p><?php _e( 'The post you were looking for could not be found.', 'comet' ); ?></p>
		</div>
	</div>

<?php endif; ?>

<?php get_footer(); ?>
