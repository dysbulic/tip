<?php
/**
 * The template for displaying all pages.
 *
 * @package WordPress
 * @subpackage Matala
 */

get_header(); ?>

	<div id="primary">
		<div id="content" role="main">

			<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="page-header">
						<h1 class="page-title"><?php the_title(); ?></h1>
					</header><!-- .page-header -->

					<div class="entry-content">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'matala' ) . '</span>', 'after' => '</div>' ) ); ?>
					</div><!-- .entry-content -->
					<div class="entry-meta">
						<?php edit_post_link( __( 'Edit', 'matala' ), '<span class="edit-link">', '</span>' ); ?>
					</div><!-- .entry-meta -->
				</article><!-- #post-<?php the_ID(); ?> -->

				<?php comments_template( '', true ); ?>

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