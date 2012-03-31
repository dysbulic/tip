<?php
/**
 * @package WordPress
 * @subpackage AutoFocus
 */
get_header(); ?>

<div id="content">

	<?php while ( have_posts() ) : the_post(); ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<h2 class="entry-title">
				<?php the_title(); ?>
			</h2><!-- .entry-title -->
			<div id="entry-content">
				<?php the_content(); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'autofocus' ), 'after' => '</div>' ) ); ?>
				<?php edit_post_link( __( 'Edit', 'autofocus' ), '<span class="edit-link">', '</span>' ); ?>
			</div><!-- #entry-content -->
		</div><!-- #post-<?php the_ID(); ?> -->

		<?php if ( comments_open() ) comments_template( '', true ); ?>

	<?php endwhile; ?>

</div><!-- #content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>