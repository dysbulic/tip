<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Mystique
 */

get_header(); ?>

 			<div id="content-container">
	 			<div id="content">
					<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

						<div class="post clear-block">
							<?php if ( is_front_page() ) : ?>
								<h2 class="page-title"><?php the_title(); ?></h2>
							<?php else : ?>
								<h1 class="page-title"><?php the_title(); ?></h1>
							<?php endif; ?>

							<div class="entry clear-block">
								<?php if ( has_post_thumbnail() ) : ?>
									<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s', 'mystique' ), the_title_attribute( 'echo=0' ) ) ); ?>">
										<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'post-thumbnail', 'alt' => get_the_title(), 'title' => get_the_title() ) ); ?>
									</a>
								<?php endif; ?>
								<?php the_content(); ?>
								<?php wp_link_pages( array( 'before' => '<div class="page-link"><p><strong>'.__( "Pages:", "mystique" ).' </strong> ', 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
								<?php edit_post_link( __( '(Edit)', 'mystique' ), '' ); ?>
							</div><!-- .entry -->
						</div><!-- .post -->

						<?php comments_template( '', true ); ?>

					<?php endwhile; // end of the loop. ?>
				</div><!-- #content -->
			</div><!-- #content-container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>