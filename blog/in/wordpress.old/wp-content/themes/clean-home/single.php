<?php
/**
 * @package WordPress
 * @subpackage Clean Home
 */
?>
<?php get_header(); ?>

	<div class="content">
	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>

		<div class="navigation nav-above">
			<div class="alignleft"><?php next_post_link( '&larr; %link' ); ?></div>
			<div class="alignright"><?php previous_post_link( '%link &rarr;' ); ?></div>
		</div>

		<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
			<h1><?php the_title(); ?></h1>
			<small class="post-meta"><span class="post-date"><b><?php _e( 'Posted:', 'cleanhome' ); ?></b> <?php the_time( get_option( 'date_format' ) ); ?></span> <span class="author-link">| <b><?php _e( 'Author:', 'cleanhome' ); ?></b> <?php the_author_posts_link(); ?></span> <span class="meta-sep"><?php _e( '|', 'cleanhome'); ?></span> <span class="cat-links"><b><?php _e( 'Filed under:', 'cleanhome' ); ?></b> <?php the_category( ', ' ); ?></span> <span class="tag-links"><?php the_tags( ' | <b>Tags:</b> ', ', ', '' ); ?></span> <span class="edit-link"><?php edit_post_link( __( 'Edit this', 'cleanhome' ), ' |  <b>Modify:</b> ' ); ?></span> <span class="meta-sep"><?php _e( '|', 'cleanhome'); ?></span><span class="comments-link"><?php comments_popup_link( __( 'Leave a comment &#187;', 'cleanhome' ), __( '<strong>1</strong> Comment &#187;', 'cleanhome' ), __( '<strong>%</strong> Comments &#187;', 'cleanhome' ) ); ?></span></small>
			<?php the_content( 'Read the rest of this entry &raquo;' ); ?>
			<?php wp_link_pages( array( 'before' => '<p>Page: ', 'after' => '</p>', 'next_or_number' => 'number' ) ); ?>
			<hr/>
		</div>

		<?php comments_template(); ?>

		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_post_link( __( '&larr; %link' ) ); ?></div>
			<div class="alignright"><?php previous_post_link( __( '%link &rarr;') ); ?></div>
		</div>

	<?php else : ?>

		<h2 class="center"><?php _e( 'Not found', 'cleanhome' ); ?></h2>
		<p class="center"><?php _e( "Sorry, but you are looking for something that isn't here.", 'cleanhome' ); ?></p>

	<?php endif; ?>

	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
