<?php
/**
 * The template for displaying single posts.
 *
 * @package WordPress
 * @subpackage Mystique
 */

get_header(); ?>

 			<div id="content-container">
	 			<div id="content">
					<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

						<div class="post-navigation nav-above">
							<div class="nav-previous">
								<?php previous_post_link( '%link', _x( '&larr;', 'Previous post link', 'mystique' ) . ' %title' ); ?>
							</div>
							<div class="nav-next">
								<?php next_post_link( '%link', '%title ' . _x( '&rarr;', 'Next post link', 'mystique' ) ); ?>
							</div>
						</div><!-- .post-navigation -->

						<?php get_template_part( 'content', get_post_format() ); ?>

						<div class="post-utility">
							<p class="details">
								<?php mystique_post_meta(); ?>
								<?php comments_popup_link( __( 'Leave a Comment', 'mystique' ), __( '1 Comment', 'mystique' ), __( '% Comments', 'mystique' ) ); ?><?php _e( '.', 'mystique'); ?>
								<?php edit_post_link( __( 'Edit', 'mystique' ), '<span class="edit-link">', '</span>' ); ?>
							</p>
						</div><!-- .post-utility -->

						<div class="post-navigation nav-below">
							<div class="nav-previous">
								<?php previous_post_link( '%link', _x( '&larr;', 'Previous post link', 'mystique' ) . ' %title' ); ?>
							</div>
							<div class="nav-next">
								<?php next_post_link( '%link', '%title ' . _x( '&rarr;', 'Next post link', 'mystique' ) ); ?>
							</div>
						</div><!-- .post-navigation -->

					<?php comments_template(); ?>

					<?php endwhile; else: ?>
						<p><?php _e( 'Sorry, but nothing matched your search criteria.', 'mystique' ); ?></p>

					<?php endif; ?>
				</div><!-- #content -->
			</div><!-- #content-container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>