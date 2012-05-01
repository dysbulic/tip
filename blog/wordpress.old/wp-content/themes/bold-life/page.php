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
 * @subpackage Bold_Life
 */

get_header(); ?>

<div class="post-wrapper">

	<?php while ( have_posts() ) : the_post(); ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class( 'wide-entry-wrapper' ); ?>>
			<div class="page-entry-wrapper">
				<div class="entry">
					<div class="entry-inner">
						<h2 class="post-title">
							<?php the_title(); ?>
						</h2>
						<?php
						the_content( __( 'Read more&hellip;', 'bold-life' ) );
						wp_link_pages( array( 'before' => '<div class="post-pages">' . __( 'Pages:', 'bold-life' ), 'after' => '</div>' ) );
						edit_post_link( __( 'Edit', 'bold-life' ), '<div class="edit-link">', '</div>' );
						?>
					</div><!-- .entry-inner -->
				</div><!-- .entry -->
				<?php if ( comments_open() ) comments_template(); ?>
			</div><!-- .page-entry-wrapper -->
		</div><!-- #post-<?php the_ID(); ?> -->

	<?php endwhile; ?>

</div><!-- .post-wrapper-->

<?php get_sidebar(); ?>
<?php get_footer(); ?>