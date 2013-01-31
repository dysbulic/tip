<?php
/**
 * @package WordPress
 * @subpackage zBench
 *
 * Single posts
 */
?>
<?php get_header(); ?>

	<div id="maincontent">
		<div id="maincontent_inner">

	<?php
	/* Main loop - displays posts */
	if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<h2 class="title"><?php the_title(); ?></h2>

			<div class="post-info">
				<div>
					<span class="comments-meta"><a href="<?php the_permalink(); ?>#respond"><?php comments_number(__( 'Leave a Comment', 'zbench' ), __( '1 Comment', 'zbench' ), __( '% Comments', 'zbench' )); ?></a></span>
					<?php _e( 'Posted by', 'zbench' ); ?> <span class="author"><?php the_author_posts_link(); ?></span> <?php _e( 'on', 'zbench' ); ?> <span class="time"><?php the_time( get_option( 'date_format' ) ); ?></span>
					<?php edit_post_link( 'Edit', '', '' ); ?>
				</div>
			</div>

			<div class="content">
				<?php the_content( __( 'Read more of this post', 'zbench' ) ); ?>
			</div>

			<div class="post-meta">
				<?php wp_link_pages( array( 'before' => '<strong>Pages:</strong> ', 'after' => '<br /><br />', 'next_or_number' => 'number' ) ); ?>
				<span class="categories"><?php the_category( ', ' ); ?></span>
				<?php the_tags( '<span class="tags">', ', ', '</span>' ); ?>
			</div>
			<div class="sep"></div>

			<div class="next-prev-links">
				<?php previous_post_link( '%link', '<span class="nav-previous">&larr; %title</span>' ); ?>
				<?php next_post_link( '%link', '<span class="nav-next">%title &rarr;</span>' ); ?>
			</div>
			<div class="sep"></div>

		</div>

	<?php endwhile; ?>
	<?php comments_template(); // Load comments


	/* If no posts, then serve error message */
	else: ?>
		<div class="post">
			<h2><?php _e( 'Not Found', 'zbench' ); ?></h2>
			<p><?php _e( 'Sorry, but you are looking for something that isn&rsquo;t here.', 'zbench' ); ?></p>
		</div>
	<?php endif; ?>

	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>