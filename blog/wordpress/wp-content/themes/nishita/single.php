<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Nishita
 */

get_header(); ?>

<?php
/* Run a check to see if there are any active widgets
 * loaded into the Primary Sidebar. If widgets are present,
 * then we add a special class (single-no-sidebar) to a wrapper
 * div in order to reduce the width of page content and 
 * allow for a widgetized sidebar to be present.
 */
if ( ! is_active_sidebar( 'primary-sidebar' ) )
	echo '<div class="main single-no-sidebar">';
else
	echo '<div class="main">';
?>

		<?php while ( have_posts() ) : the_post(); ?>

			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php if ( get_the_title() ) { ?>
					<h2 class="photo-title">
						<span class="content-title"><?php the_title(); ?></span>
					</h2>
				<?php } ?>
				<div class="photo">
					<div class="photo-inner">
					<?php the_content(); ?>
					<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'nishita' ) . '</span>', 'after' => '</div>' ) ); ?>
					<?php the_tags( '<div class="entry-tags"><span class="the-tags">' . __( 'Tags:', 'nishita' ) . '</span> ', ', ', '</div>' ); ?>
					<?php if ( is_multi_author() ) { printf( '<span class="the-author">' . __( 'This entry was posted by %s.', 'nishita' ) . '</span>', get_the_author() ); } ?>
					<?php edit_post_link( __( 'Edit', 'nishita' ), '<span class="edit-link">', '</span>' ); ?>
					<?php if ( comments_open() ) comments_template( '', true ); ?>
					<div class="clear"></div>
					</div><!-- .photo-inner -->
				</div><!-- .photo -->
				<div class="photo-meta">
					<div class="photo-meta-inner">
						<ul>
							<li class="first"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'nishita' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_time( get_option( 'date_format' ) ); ?></a></li>
							<?php if( get_the_category() ) { ?><li><?php the_category( ', ' ); ?></li><?php } ?>
							<li class="last"><?php comments_popup_link( __( 'No comments', 'nishita' ), _x( '1 comment', 'comments number', 'nishita' ), _x( '% comments', 'comments number', 'nishita' ), 'comments-link', __( 'Comments closed', 'nishita' ) ); ?></li>
						</ul>
					</div><!-- .photo-meta-inner -->
				</div><!-- .photo-meta -->
				
			</div><!-- #post-<?php the_ID(); ?> -->
			
			<div class="navigate-single">
				<?php previous_post_link( '<div class="older-wrapper">%link</div>', __( '&larr; Older Post', 'nishita' ) ); ?>
				<?php next_post_link( '<div class="newer-wrapper">%link</div>', __( 'Newer Post &rarr;', 'nishita' ) ); ?>
			</div><!-- .navigate -->

		<?php endwhile; // end of the loop. ?>

</div><!-- .main -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>