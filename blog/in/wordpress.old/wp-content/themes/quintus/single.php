<?php
/**
 * @package WordPress
 * @subpackage Quintus
 */

get_header(); ?>

		<div id="primary">
			<div class="content" id="content" role="main">

			<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

				<nav id="nav-above">
					<span class="permalink">
					<?php printf( __( 'Bookmark the <a href="%1$s" title="Permalink to %2$s" rel="bookmark">permalink</a>.', 'quintus' ),
						get_permalink(),
						the_title_attribute( 'echo=0' )
						);
					?>
					</span>
				</nav><!-- #nav-above -->

				<?php get_template_part( 'content', 'single' ); ?>

				<nav id="nav-below">
					<h1 class="section-heading"><?php _e( 'Post navigation', 'quintus' ); ?></h1>
					<div class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'quintus' ) . '</span> %title' ); ?></div>
					<div class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'quintus' ) . '</span>' ); ?></div>
				</nav><!-- #nav-below -->

				<?php comments_template( '', true ); ?>

			<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>