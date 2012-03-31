<?php
/**
 * @package Shaan
 */

get_header();
?>

<div id="container">

	<div id="content" class="narrow">

	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<?php if ( is_sticky() && has_post_thumbnail( get_the_ID() ) ) : ?>
				<div class="post-thumb">
					<?php echo get_the_post_thumbnail( get_the_ID(), 'shaan_featured_image' ); ?>
				</div><!--  #post-thumb -->
			<?php endif; ?>

			<?php the_title( '<h2 class="post-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" title="' .  the_title_attribute( array( 'echo' => false ) ) . '">', '</a></h2>' ); ?>

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
			</p>

			<?php if ( ! empty( $post->post_excerpt ) ) : ?>
				<div class="entry-excerpt">
					<?php if ( ! is_sticky() && has_post_thumbnail( get_the_ID() ) ) : ?>
						<a href="<?php the_permalink(); ?>" class="featured-image-link"><?php echo get_the_post_thumbnail( get_the_ID(), 'thumbnail' ); ?></a>
					<?php endif; ?>
					<?php the_excerpt(); ?>
					<p><a class="more-link" href="<?php echo esc_attr( get_permalink() ); ?>"><?php _e( 'Read more &raquo;', 'shaan' ); ?></a></p>
				</div>
			<?php else : ?>
				<?php the_content( '<span>' . __( 'Read more &raquo;', 'shaan' ) . '</span>' ); ?>
				<?php wp_link_pages( array(
					'before' => '<div class="page-link">' . __( 'Pages: ', 'shaan' ),
					'after'  => '</div>',
				) ) ; ?>
			<?php endif; ?>

			<?php edit_post_link( __( 'Edit this post', 'shaan' ), '<div id="post-info"><ul><li>', '</li></ul></div>' ); ?>

		</div><!--#posts-->

		<?php endwhile; ?>

		<?php get_template_part( 'nav-posts' ); ?>

	<?php else : ?>

		<h2 class="page-title"><?php _e( 'Not Found', 'shaan' ); ?></h2>
		<p><?php _e( 'Sorry, but you are looking for something that is not here.', 'shaan' ); ?></p>
		<?php get_search_form(); ?>

	<?php endif; ?>

	</div><!-- #content -->


	<?php get_sidebar(); ?>
	<?php get_footer(); ?>