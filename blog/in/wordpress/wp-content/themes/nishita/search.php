<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage Nishita
 */
get_header(); ?>

<?php
/* Run a check to see if there are any active widgets
 * loaded into the Primary Sidebar. If widgets are present,
 * then we add a special class (search-no-sidebar) to a wrapper
 * div in order to reduce the width of page content and 
 * allow for a widgetized sidebar to be present.
 */
if ( ! is_active_sidebar( 'primary-sidebar' ) )
	echo '<div class="search-no-sidebar">';
else
	echo '<div class="search-with-sidebar">';
?>

	<?php if ( have_posts() ) : ?>

		<div class="search-header">
			<h1 class="page-title">
				<?php printf( __( 'Search Results for: %s', 'nishita' ), '<span>' . get_search_query() . '</span>' ); ?>
			</h1>
		</div><!-- .search-header -->

	<?php while ( have_posts() ) : the_post(); ?>
	
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<?php if ( get_the_title() ) { ?>
				<h2 class="photo-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'nishita' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			<?php } ?>
			<?php if ( get_the_content() ) { ?>
				<div class="photo">
					<div class="photo-inner">
					<?php the_content(); ?>
					<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'nishita' ) . '</span>', 'after' => '</div>' ) ); ?>
					<?php the_tags( '<div class="entry-tags"><span class="the-tags">' . __( 'Tags:', 'nishita' ) . '</span> ', ', ', '</div>' ); ?>
					<div class="clear"></div>
					</div><!-- .photo-inner -->
				</div><!-- .photo -->	
			<?php } ?>
			<?php if ( 'post' == get_post_type() ) { ?>
			<div class="photo-meta">
				<div class="photo-meta-inner">
					<ul>
						<li class="first"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'nishita' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_time( get_option( 'date_format' ) ); ?></a></li>
						<?php if( get_the_category() ) { ?><li><?php the_category( ', ' ); ?></li><?php } ?>
						<li class="last"><?php comments_popup_link( __( 'No comments', 'nishita' ), _x( '1 comment', 'comments number', 'nishita' ), _x( '% comments', 'comments number', 'nishita' ), 'comments-link', __( 'Comments closed', 'nishita' ) ); ?></li>
					</ul>
				</div><!-- .photo-meta-inner -->
			</div><!-- .photo-meta -->
			<?php } ?>
			
		</div><!-- #post-<?php the_ID(); ?> -->

	<?php endwhile; ?>

		<div class="navigate">
			<div class="older-wrapper"><?php next_posts_link( '<span class="older">' . __( '&larr; Older Posts', 'nishita' ) . '</span>' ); ?></div>
			<div class="newer-wrapper"><?php previous_posts_link( '<span class="newer">'. __( 'Newer Posts &rarr;', 'nishita' ) . '</span>' ); ?></div>
		</div><!-- .navigate -->

	<?php else : ?>

		<div class="search-header">
			<h1 class="page-title">
				<?php _e( 'Nothing Found', 'nishita' ); ?>
			</h1>
		</div><!-- .search-header -->
		<div class="no-results-wrapper">
			<div class="no-results">
				<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'nishita' ); ?></p>
				<?php get_search_form(); ?>
			</div><!-- .no-results -->
		</div><!-- .no-results-wrapper -->

	<?php endif; ?>

</div><!-- .search-no-sidebar and .search-with-sidebar -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>