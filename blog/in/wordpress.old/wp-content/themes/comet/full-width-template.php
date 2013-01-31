<?php
/**
 * Template Name: Full Width
 *
 * @package WordPress
 * @subpackage Comet
 */
$content_width = 900;
?>

<?php get_header(); ?>

<?php if ( have_posts() ) : ?>

	<?php while ( have_posts() ) : the_post(); ?>

	<!-- post -->
	<div <?php post_class( 'post' ); ?> id="post-<?php the_ID(); ?>">
		<?php the_title( '<h1 class="post-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' ); ?>
		<div class="post-text">
		<?php
		the_content();
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
				<?php edit_post_link( __( 'Edit Post', 'comet' ), ' &nbsp;&bull;&nbsp; ', '' ); ?>
			</div>
		</div>
	</div>
	<!--/post -->

	<div class="sep"></div>

	<?php comments_template(); ?>

	<?php endwhile; ?>

	<?php else : ?>

	<div class="post">
		<h1 class="post-title"><?php _e( 'Page not found', 'comet' ); ?></h1>
		<div class="post-text">
			<p><?php _e( 'The page you were looking for could not be found.', 'comet' ); ?></p>
		</div>
	</div>

<?php endif; ?>

<?php get_footer(); ?>
