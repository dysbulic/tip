<?php
/**
 * @package Shaan
 */

get_header();

$content_width = 900;
$metadata = wp_get_attachment_metadata();
$caption = get_post_field( 'post_excerpt', get_the_id() );
$parent_ID = absint( get_post_field( 'post_parent', get_the_ID() ) );
?>

<div id="container">

	<div id="content" class="media-image">

	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class( 'media-image' ); ?>>

		<?php the_title( '<h1 class="post-title">', '</h1>' ); ?>

		<p class="post-meta">
			<?php the_author_posts_link(); ?>

			&diams; <a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php echo esc_html( get_the_date() ); ?></a>

			<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
				&diams; <?php comments_popup_link(
					__( 'Leave a Comment', 'shaan' ),
					__( '1 Comment',       'shaan' ),
					__( '% Comments',      'shaan' )
				); ?>
			<?php endif; ?>

			<?php if ( $metadata ) : ?>
				<?php
					/* translators: We're going for something like: 300 x 400 pixels. %3$d = width. %4$d = height. */
					printf( ' &diams; <a href="%1$s" title="%2$s">' . __( '%3$d &times; %4$d pixels', 'shaan' ) . '</a>',
						esc_url( wp_get_attachment_url() ),
						esc_attr__( 'Link to full-size image', 'shaan' ),
						absint( $metadata['width'] ),
						absint( $metadata['height'] )
				); ?>
			<?php endif; ?>

			<?php if ( ! empty( $parent_ID ) ) : ?>
				&diams; <a href="<?php echo esc_url( get_permalink( $parent_ID ) ); ?>" title="<?php echo esc_attr( sprintf( __( 'Return to %s', 'shaan' ), strip_tags( get_the_title( $parent_ID ) ) ) ); ?>"><?php _e( 'Back to gallery', 'shaan' ); ?></a>
			<?php endif; ?>

			<?php edit_post_link( __( 'Edit', 'shaan' ), ' &diams; ', '' ); ?>
		</p><!-- post-meta -->

		<div class="entry-image">

			<?php echo wp_get_attachment_image( get_the_ID(), array( $content_width, $content_width ), false, array( 'id' => 'image' ) ); ?>

			<?php if ( ! empty( $caption ) ) : ?>
				<div id="image-caption" class="wp-caption-text"><?php the_excerpt(); ?></div>
			<?php endif; ?>

			<div id="image-description"><?php the_content(); ?></div>

			<nav id="image-navigation" class="paged-navigation">
				<h1 class="assistive-text"><?php _e( 'Image navigation', 'shaan' ); ?></h1>
				<div class="nav-older"><?php previous_image_link( false, __( '&larr; Previous Image', 'shaan' ) ); ?></div>
				<div class="nav-newer"><?php next_image_link( false, __( 'Next Image &rarr;', 'shaan' ) ); ?></div>
				<?php if ( ! empty( $parent_ID ) ) : ?>
					<?php $link_text = ( 'gallery' == get_post_format( $parent_ID ) ) ? get_post_format_string( 'gallery' ) : get_the_title( $parent_ID ); ?>

					<a class="nav-return" href="<?php echo esc_attr( get_permalink( $parent_ID ) ); ?>" title="<?php echo esc_attr( sprintf( __( 'Return to %s', 'shaan' ), strip_tags( $link_text ) ) ); ?>"><?php printf( __( 'Return to: %s', 'shaan' ), '<em>' . $link_text . '</em>' ); ?></a>
				<?php endif; ?>
			</nav>

		</div><!-- .entry-attachment -->

		<?php edit_post_link( __( 'Edit this post', 'shaan' ), '<div id="post-info"><ul><li>', '</li></ul></div>' ); ?>
	</div>

	<?php comments_template(); ?>

		<?php endwhile; ?>

	<?php else : ?>

		<h2 class="page-title"><?php _e( 'Not Found', 'shaan' ); ?></h2>
		<p><?php _e( 'Sorry, but you are looking for something that is not here.', 'shaan' ); ?></p>
		<?php get_search_form(); ?>

	<?php endif; ?>

	</div><!-- #content -->

	<?php get_footer(); ?>