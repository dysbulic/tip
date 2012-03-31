<?php
/**
 * @package Shaan
 */

get_header(); ?>

<div id="container">

	<div id="content" class="narrow">

	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<div id="post-<?php the_ID(); ?>" <?php post_class( 'post' ); ?>>

				<?php if ( has_post_thumbnail( get_the_ID() ) ) : ?>
					<div class="post-thumb">
						<?php echo get_the_post_thumbnail( get_the_ID(), 'shaan_featured_image' ); ?>
					</div><!--  #post-thumb -->
				<?php endif; ?>

				<?php the_title( '<h1 class="post-title">', '</h1>' ); ?>

				<div id="page-content"><?php the_content(); ?></div>

				<?php wp_link_pages( array(
					'before' => '<div class="page-link">' . __( 'Pages: ', 'shaan' ),
					'after'  => '</div>',
				) ) ; ?>

			</div>

		<?php endwhile; ?>

	<?php endif; ?>

	<?php comments_template(); ?>

	</div><!-- #content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>