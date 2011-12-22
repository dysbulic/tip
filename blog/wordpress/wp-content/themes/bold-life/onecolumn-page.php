<?php
/**
 * Template Name: One column, no sidebar
 *
 * A custom page template without sidebar.
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package WordPress
 * @subpackage Bold_Life
 */

get_header(); ?>

<?php $content_width = 658; // override $content_width for wider content ?>

<div class="single-column">

	<?php while ( have_posts() ) : the_post(); ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
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
		</div><!-- #post-<?php the_ID(); ?> -->

	<?php endwhile; ?>

</div><!-- .post-wrapper-->

<?php get_footer(); ?>