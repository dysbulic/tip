<?php
/**
 * @package WordPress
 * @subpackage Paperpunch
 */
?><?php get_header(); ?>
	<?php if (have_posts()) : ?>
	<?php while (have_posts()) : the_post(); ?>
		<div class="post-box">
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="meta clear">
					<div class="author"><?php the_time( get_option( 'date_format' ) ); ?> <span>/ <?php printf(__( '%s', 'paperpunch' ), get_the_author()); ?></span></div>
				</div><!--end meta-->
				<div class="post-header">
				 <h1><?php the_title(); ?></h1>
				 <?php the_tags( '<div class="tags">', ', ', '</div>' ); ?>
				</div><!--end post header-->
				<div class="entry clear">
					<?php if ( function_exists( 'add_theme_support' ) ) the_post_thumbnail( array(250,9999), array( 'class' => ' alignleft' ) ); ?>
					<?php the_content(__( 'Read more...', 'paperpunch' )); ?>
					<?php edit_post_link( __( 'Edit this', 'paperpunch' ), '<p>', '</p>' ); ?>
					<?php wp_link_pages(); ?>
				</div><!--end entry-->
				<div class="post-footer clear">
					<?php if ( 'closed' == $post->comment_status) : ?>
						<p class="note"><?php _e( 'Comments are closed.', 'paperpunch' ); ?></p>
					<?php endif; ?>
					<div class="category"><?php _e( 'Filed under', 'paperpunch' ); ?> <?php the_category( ', ' ); ?></div>
				</div><!--end post footer-->
			</div><!--end post-->
		</div><!--end post-box-->
		<div class="pagination post single clear">
			<div class="alignleft"><?php previous_post_link( '%link', _x( '&larr;', 'Previous post link', 'paperpunch' ) . ' %title' ); ?></div>
			<div class="alignright" ><?php next_post_link( '%link', _x( '%title ', 'Next post link', 'paperpunch' ) . '&rarr;' ); ?></div>
		</div><!--end pagination-->
	<?php endwhile; /* rewind or continue if all posts have been fetched */ ?>
		<?php comments_template( '', true); ?>
	<?php else : ?>
	<?php endif; ?>
</div><!--end content-->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
