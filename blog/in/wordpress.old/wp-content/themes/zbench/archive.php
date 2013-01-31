<?php
/**
 * @package WordPress
 * @subpackage zBench
 *
 * Archive
 */
?>
<?php get_header(); ?>

	<div id="maincontent">
		<div id="maincontent_inner">

		<div class="archives-title">
			<div>
			<?php if ( is_day() ) : ?>
				<h2><?php _e( 'Daily Archives:', 'zbench' ); ?> <?php the_time( get_option( 'date_format' ) ); ?></h2>
			<?php elseif ( is_month() ) : ?>
				<h2><?php _e( 'Monthly Archives:', 'zbench' ); ?> <?php the_time( 'F Y' ); ?></h2>
			<?php elseif ( is_year() ) : ?>
				<h2><?php _e( 'Yearly Archives:', 'zbench' ); ?> <?php the_time( 'Y' ); ?></h2>
			<?php elseif ( is_category() ) : ?>
				<h2><?php _e( 'Category Archives:', 'zbench' ); ?> <?php single_cat_title(); ?></h2>
			<?php elseif ( is_tag() ) : ?>
				<h2><?php echo _e( 'Tag Archives:', 'zbench' ); ?> <?php single_tag_title(); ?></h2>
			<?php elseif ( isset( $_GET['paged'] ) && !empty( $_GET['paged'] ) ) : ?>
				<h2><?php _e( 'Blog Archives', 'zbench' ); ?></h2>
			<?php endif; ?>
			</div>
		</div>

	<?php
	/* Main loop - displays posts */
	if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<h2 class="title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'zbench' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			<div class="post-info">
				<div>
					<span class="comments-meta"><?php comments_popup_link( __( 'Leave a Comment', 'zbench' ), __( '1 Comment', 'zbench' ), __( '% Comments', 'zbench' ) ); ?></span>
					<?php _e( 'Posted by', 'zbench' ); ?> <span class="author"><?php the_author_posts_link(); ?></span> <?php _e( 'on', 'zbench' ); ?> <span class="time"><?php the_time( get_option( 'date_format' ) ); ?></span>
					<?php edit_post_link( 'Edit', '', '' ); ?>
				</div>
			</div>
			<div class="content">
				<?php the_excerpt( __( 'Read more of this post', 'zbench' ) ); ?>
			</div>

			<div class="post-meta">
				<?php wp_link_pages( array( 'before' => '<strong>Pages:</strong> ', 'after' => '<br /><br />', 'next_or_number' => 'number' ) ); ?>
				<?php if ( get_the_category() ) : ?><span class="categories"><?php the_category( ', ' ); ?></span><?php endif; ?>
				<?php the_tags( '<span class="tags">', ', ', '</span>' ); ?>
			</div>
			<div class="sep"></div>
		</div>

	<?php endwhile;

	/* If no posts, then serve error message */
	else: ?>

		<div class="post">
			<h2><?php _e( 'Not Found', 'zbench' ); ?></h2>
			<p><?php _e( 'Sorry, but you are looking for something that isn&rsquo;t here.', 'zbench' ); ?></p>
		</div>
	<?php endif; ?>

		<div class="next-prev-links">
			<div class="nav-previous"><p><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'enterprise' ) ); ?></p></div>
			<div class="nav-next"><p><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'enterprise' ) ); ?></p></div>
		</div>

	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>