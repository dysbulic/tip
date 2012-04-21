<?php
/**
 * @package WordPress
 * @subpackage Comet
 */
?>

<?php get_header(); ?>

	<div id="page-head">
	<?php
		$description = '';
		if ( is_tag() ) {
			$title = sprintf( __( 'All posts tagged %1$s', 'comet' ), '<b>' . single_tag_title( '', false ) . '</b>' );
			$description = tag_description();
		} elseif ( is_category() ) {
			$title = sprintf( __( 'All posts in category %1$s', 'comet' ), '<b>' . esc_html( single_cat_title( '', false ) ) . '</b>' );
			$description = category_description();
		} elseif ( is_day() ) {
			$title = sprintf( __( 'All posts for the day %1$s', 'comet' ), '<b>' . esc_html( get_the_date( __( 'F jS, Y', 'comet' ) ) ) . '</b>' );
		} elseif ( is_month() ) {
			$title = sprintf( __( 'All posts for the month %1$s', 'comet' ), '<b>' . esc_html( get_the_date( __( 'F, Y', 'comet' ) ) ) . '</b>' );
		} elseif ( is_year() ) {
			$title = sprintf( __( 'All posts for the year %1$s', 'comet' ), '<b>' . esc_html( get_the_date( __( 'Y', 'comet' ) ) ) . '</b>' );
		} elseif ( is_author() ) {
			the_post();
			$title = sprintf( __( 'All posts by %1$s', 'comet' ), '<b>' . esc_html( get_the_author() ) . '</b>' );
			rewind_posts();
		}

		if ( ! empty( $title ) ) {
			echo '<h2 id="page-intro">' . $title . '</h2>';
		}

		if ( ! empty( $description ) ) {
			echo '<div id="page-description">' . $description . '</div>';
		}
	?>
	</div>

<?php if ( have_posts() ) : ?>

	<?php while ( have_posts() ) : the_post(); ?>

	<!-- post -->
	<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
		<?php the_title( '<h1 class="post-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' ); ?>
		<div class="post-text">
		<?php
			the_excerpt();

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

	<div class="sep"></div>

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
