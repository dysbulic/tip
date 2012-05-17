<?php
/**
 * @package WordPress
 * @subpackage Nishita
 */
get_header(); ?>

<?php
/* Run a check to see if there are any active widgets
 * loaded into the Primary Sidebar. If widgets are present,
 * then we add a special class (page-no-sidebar) to a wrapper
 * div in order to reduce the width of page content and
 * allow for a widgetized sidebar to be present.
 */
if ( ! is_active_sidebar( 'primary-sidebar' ) )
	echo '<div class="main page-no-sidebar">';
else
	echo '<div class="main">';
?>

	<div class="main-inner">

		<?php while ( have_posts() ) : the_post(); ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<h2 class="page-title"><?php the_title(); ?></h2>
			<div class="page-body">
				<?php the_content(); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'nishita' ) . '</span>', 'after' => '</div>' ) ); ?>
				<?php edit_post_link( __( 'Edit', 'nishita' ), '<span class="edit-link">', '</span>' ); ?>
				<div class="clear"></div>
			</div><!-- .page-body -->
		</div><!-- #post-<?php the_ID(); ?> -->

		<?php if ( comments_open() ) comments_template( '', true ); ?>

		<?php endwhile; // end of the loop. ?>

	</div><!-- .main-inner -->

</div><!-- .main -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>