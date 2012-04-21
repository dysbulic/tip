<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Nishita
 */

get_header(); ?>

<?php
/* Run a check to see if there are any active widgets
 * loaded into the Primary Sidebar. If widgets are present,
 * then we add a special class (index-no-sidebar) to a wrapper
 * div in order to reduce the width of page content and 
 * allow for a widgetized sidebar to be present.
 */
if ( ! is_active_sidebar( 'primary-sidebar' ) )
	echo '<div class="index-wrapper index-no-sidebar">';
else
	echo '<div class="index-wrapper index-with-sidebar">';
?>

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

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
			<?php if ( !is_sticky() ) { ?>
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
	
		<?php
		/* If a user has chosen to show only 1 post per 
		 * page in his Readings Settings panel then we
		 * will output single post navigation links 
		 * underneath the single post on the home page instead
		 * of multiple posts navigation links.
		 */
		$postsppage = get_option('posts_per_page');
		if ( 1 == $postsppage ) {
		?>
		
			<div class="navigate-single">
				<?php previous_post_link( '<div class="older-wrapper">%link</div>', __( '&larr; Older Post', 'nishita' ) ); ?>
				<?php next_post_link( '<div class="newer-wrapper">%link</div>', __( 'Newer Post &rarr;', 'nishita' ) ); ?>
			</div><!-- .navigate -->
		
		<?php } else { ?>
		
			<div class="navigate">
				<div class="older-wrapper">
					<?php next_posts_link( '<span class="older">' . __( '&larr; Older Posts', 'nishita' ) . '</span>' ); ?>
				</div><!-- .older-wrapper -->
				<div class="newer-wrapper">
					<?php previous_posts_link( '<span class="newer">'. __( 'Newer Posts &rarr;', 'nishita' ) . '</span>' ); ?>
				</div><!-- .newer-wrapper -->
			</div><!-- .navigate -->
		
		<?php } ?>

	<?php endif; ?>

</div><!-- .index-wrapper -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>