<?php
/**
 * @package WordPress
 * @subpackage Koi
 */
get_header(); ?>

	<div id="content">

	<?php if ( have_posts() ) : ?>

		<h2><?php _e( 'Search Results for:', 'ndesignthemes' ); ?> <em><?php the_search_query(); ?></em></h2>

		<?php while ( have_posts() ) : the_post(); ?>

		<div <?php post_class(); ?>>
			<h2 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
			<p class="post-date"><?php the_time( 'M d' ); ?></p>
			<p class="post-data"><span class="postcategory"><?php the_category( ', ' ); ?></span> <?php the_tags( '<span class="posttag">', ', ', '</span>' ); ?> <span class="postcomment"><?php comments_popup_link( 'Leave a Comment', '1 Comment', '% Comments' ); ?></span><?php edit_post_link( '[Edit]' ); ?></p>
			<?php the_excerpt(); ?>
		</div>
		<!--/post -->

		<?php endwhile; ?>

		<p class="post-nav"><span class="previous"><?php next_posts_link( __( '<em>Previous</em> Older Entries', 'ndesignthemes' ) ); ?></span> <span class="next"><?php previous_posts_link( __( '<em>Next</em> Newer Entries', 'ndesignthemes' ) ); ?></span></p>

	<?php else : ?>

		<h2><?php _e( 'Sorry', 'ndesignthemes' ); ?></h2>
		<p><?php _e( 'No posts found. Please try a different keyword', 'ndesignthemes' ); ?></p>

	<?php endif; ?>

	</div>
	<!--/content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>