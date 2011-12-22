<?php
/**
 * @package WordPress
 * @subpackage Paperpunch
 */
?><?php get_header(); ?>
	<?php if (have_posts()) : ?>
	<?php while (have_posts()) : the_post(); ?>
		<div class="post-box clear">
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="meta">
					<div class="author"><?php the_time( get_option( 'date_format' ) ); ?> <span>/ <?php printf(__( '%s', 'paperpunch' ), get_the_author()); ?></span></div>
				</div><!--end meta-->
				<div class="post-header">
				 <h2><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
				</div><!--end post header-->
				<div class="entry clear">
					<?php if ( function_exists( 'add_theme_support' ) ) the_post_thumbnail( array(250,9999), array( 'class' => ' alignleft border' ) ); ?>
					<?php the_content(__( 'Read more...', 'paperpunch' )); ?>
					<?php edit_post_link( __( 'Edit this', 'paperpunch' ), '<p>', '</p>' ); ?>
					<?php wp_link_pages(); ?>
				</div><!--end entry-->
				<div class="post-footer clear">
					<div class="category"><?php _e( 'Filed under', 'paperpunch' ); ?> <?php the_category( ', ' ); ?></div>
					<?php if (( 'open' == $post->comment_status) && (empty($post->post_password))) : ?>
					 <div class="comments"><?php comments_popup_link(__( '<strong>0</strong>', 'paperpunch' ), __( '<strong>1</strong>', 'paperpunch' ), __( '<strong>%</strong>', 'paperpunch' ), '', '' );?></div>
					<?php endif; ?>
				</div><!--end post footer-->
			</div><!--end post-->
		</div><!--end post-box-->
	<?php endwhile; /* rewind or continue if all posts have been fetched */ ?>
		<div class="pagination clear">
			<div class="alignleft"><?php next_posts_link(__( '&larr; Older', 'paperpunch' )); ?></div>
			<div class="alignright" ><?php previous_posts_link(__( 'Newer &rarr;', 'paperpunch' )); ?></div>
		</div><!--end pagination-->
	<?php else : ?>
	<?php endif; ?>
</div><!--end content-->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
