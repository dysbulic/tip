<?php
/**
 * Template Name: Full-width, no sidebar
 *
 * @package WordPress
 * @subpackage zBench
 *
 */
?>
<?php get_header(); ?>

	<div id="maincontent">
		<div id="maincontent_inner">

	<?php
	/* Main loop - displays posts */
	if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<h2 class="title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'zbench' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

			<div class="post-info">
				<div>
					<?php if ( comments_open() ) : ?>
					<span class="comments-meta"><a href="<?php the_permalink(); ?>#respond"><?php comments_number(__( 'Leave a Comment', 'zbench' ), __( '1 Comment', 'zbench' ), __( '% Comments', 'zbench' )); ?></a></span>
					<?php endif; ?>
					<?php edit_post_link( 'Edit', '', '' ); ?>
				</div>
			</div>

			<div class="content">
				<?php the_content( __( 'Read more of this post', 'zbench' ) ); ?>
			</div>

			<div class="sep"></div>
		</div>

	<?php endwhile; ?>
	<?php comments_template(); // Load comments


	/* If no posts, then serve error message */
	else: ?>

		<div class="post">
			<h2><?php _e( 'Not Found', 'zbench' ); ?></h2>
			<p><?php _e( 'Sorry, but you are looking for something that isn\'t here.', 'zbench' ); ?></p>
		</div>
	<?php endif; ?>

		</div>
	</div>

<?php get_footer(); ?>