<?php
/**
 * The template for displaying single posts.
 *
 * @package WordPress
 * @subpackage Matala
 */

get_header(); ?>

	<div id="primary">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', get_post_format() ); ?>

				<nav id="nav-single">
					<h3 class="assistive-text"><?php _e( 'Post navigation', 'matala' ); ?></h3>
					<span class="nav-previous"><?php previous_post_link( '%link', __( 'Previous <span>Post</span>', 'matala' ) ); ?></span>
					<span class="nav-next"><?php next_post_link( '%link', __( 'Next <span>Post</span>', 'matala' ) ); ?></span>
				</nav><!-- #nav-single -->

				<?php comments_template(); ?>

			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->

		<?php
			/* Two columns of sidebars.
			 */
			get_sidebar( 'supplementary' );
		?>
		<div id="primary-bottom"></div>
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>