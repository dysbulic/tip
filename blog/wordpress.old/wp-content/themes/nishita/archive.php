<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Nishita
 */

get_header(); ?>

<?php
/* Run a check to see if there are any active widgets
 * loaded into the Primary Sidebar. If widgets are present,
 * then we add a special class (archive-no-sidebar) to a wrapper
 * div in order to reduce the width of page content and 
 * allow for a widgetized sidebar to be present.
 */
if ( ! is_active_sidebar( 'primary-sidebar' ) )
	echo '<div class="archive-wrapper archive-no-sidebar">';
else
	echo '<div class="archive-wrapper">';
?>

		<div class="main">
			<div class="main-inner">
				<?php
					$postsppage = get_option('posts_per_page');
					if ( $postsppage < 10 ) {
						global $query_string;
						$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
						query_posts( $query_string . '&paged=' . $paged . '&posts_per_page=10' );
					}
				?>
				<?php if ( have_posts() ) : ?>
				<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>

					<?php /* If this is a category archive */ if ( is_category() ) { ?>
					<h2 class="page-title">
						<?php printf( __( 'Archive for the category "%s"' , 'nishita' ), single_cat_title( '', false) ); ?>
					</h2>
					<?php /* If this is a category archive */ } elseif ( is_tag() ) { ?>
					<h2 class="page-title">
						<?php printf( __( 'Archive for the tag "%s"' , 'nishita' ), single_tag_title( '', false) ); ?>
					</h2>
					<?php /* If this is a monthly archive */ } elseif ( is_year() ) { ?>
					<h2 class="page-title">
						<?php printf( __( 'Archive for the year "%s"', 'nishita' ), get_the_time( 'Y' ) ); ?>
					</h2>
					<?php /* If this is a monthly archive */ } elseif ( is_month() ) { ?>
					<h2 class="page-title">
						<?php printf( __( 'Archive for the month "%s"', 'nishita' ), get_the_time( 'F, Y' ) ); ?>
					</h2>
					<?php /* If this is a daily archive*/ } elseif ( is_day() ) { ?>
					<h2 class="page-title">
						<?php printf( __( 'Archive for the day "%s"', 'nishita' ),get_the_time( 'F j, Y' ) ); ?>
					</h2>
					<?php /* If this is an author archive */ } elseif ( is_author() ) { ?>
					<h2 class="page-title">
						<?php _e( 'Author Archive', 'nishita' ); ?>
					</h2>
					<?php /* If this is a paged archive */ } elseif ( isset( $_GET[ 'paged' ] ) && ! empty( $_GET[ 'paged' ] ) ) { ?>
					<h2 class="page-title">
						<?php _e( 'Blog Archives', 'nishita' ); ?>
					</h2>
					<?php } ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<div id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix' ); ?>>
						<div class="post-body">
							<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'nishita' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
								<?php
									if ( has_post_thumbnail() ) { 
										the_post_thumbnail();
									} else {
										$images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
										if ( $images ) :
											$image = array_shift( $images );
											$image_img_tag = wp_get_attachment_image( $image->ID, array(128,128) );
											echo $image_img_tag;
										endif;
									}
								?>
							</a>
						</div>
						<h3 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'nishita' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
						<h4 class="post-meta"><?php the_time( get_option( 'date_format' ) ); ?></h4>
					</div><!-- #post-<?php the_ID(); ?> -->

				<?php endwhile; ?>

			</div><!-- .main-inner -->
		</div><!-- .main -->

		<div class="navigate">
			<div class="older-wrapper"><?php next_posts_link( '<span class="older">' . __( '&larr; Older Posts', 'nishita' ) . '</span>' ); ?></div>
			<div class="newer-wrapper"><?php previous_posts_link( '<span class="newer">'. __( 'Newer Posts &rarr;', 'nishita' ) . '</span>' ); ?></div>
		</div><!-- .navigate -->

	</div><!-- .archive-wrapper -->

	<?php else : ?>

		<h3><?php _e( 'Not Found', 'nishita' ); ?></h3>

	<?php endif; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>