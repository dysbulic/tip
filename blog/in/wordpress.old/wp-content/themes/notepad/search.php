<?php
/**
 * @package WordPress
 * @subpackage Notepad
 */
?>
<?php get_header(); ?>

	<div id="content">

	<?php if ( have_posts() ) : ?>

		<h2>
			<?php printf( __( 'Search Results for: %s' ), '<em>' . esc_html( get_search_query() ) . '</em>' ); ?>
		</h2>

		<?php while ( have_posts() ) : the_post(); ?>

		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<h2 class="post-title">
				<a href="<?php the_permalink() ?>" title="<?php esc_attr( the_title_attribute() ); ?>"><?php the_title(); ?></a>
			</h2>
			<p class="post-date">
				<?php the_time( get_option( 'date_format' ) ); ?>
			</p>
			<p class="post-data">
				<span class="postcategory">
					<?php the_category( ', ' ) ?>
				</span>
				<?php the_tags( '<span class="posttag">', ', ', '</span>'); ?>
				<span class="postcomment">
					<?php comments_popup_link( 'Leave a comment', '1 Comment', '% Comments' ); ?>
				</span>
				<?php edit_post_link( '[Edit]' ); ?>
			</p>
			<?php the_excerpt(); ?>
		</div>
		<!--/post -->

		<?php endwhile; ?>

		<p class="post-nav">
			<span class="previous">
				<?php next_posts_link(__( 'Older Entries','notepad-theme' )) ?>
			</span>
			<span class="next">
				<?php previous_posts_link(__( 'Newer Entries','notepad-theme' )) ?>
			</span>
		</p>

	<?php else : ?>

		<h2>
			<?php _e( 'Sorry','notepad-theme' ); ?>
		</h2>
		<p>
			<?php printf( __( 'No posts found for &ldquo;%s.&rdquo; Please try a different keyword' ), '<em>' . esc_html( get_search_query() ) . '</em>' ); ?>
		</p>

	<?php endif; ?>

	</div>
	<!--/content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>