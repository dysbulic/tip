<?php
/**
 * @package WordPress
 * @subpackage Titan
 */
?>
<?php get_header(); ?>
	<?php if ( have_posts() ) : ?>
	<?php while ( have_posts() ) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="post-header">
				<h1><a href="<?php echo get_permalink( $post->post_parent ); ?>" rev="attachment"><?php echo get_the_title( $post->post_parent ); ?></a> &raquo; <?php the_title(); ?></h1>
				<div class="author"><?php printf( __( 'by %s on', 'titan' ), get_the_author() ); ?> <?php the_time( get_option( 'date_format' ) ); ?></div>
			</div><!--end post header-->
			<div class="entry clear">
				<p class="attachment"><a href="<?php echo wp_get_attachment_url( $post->ID ); ?>"><?php echo wp_get_attachment_image( $post->ID, 'auto' ); ?></a></p>
				<?php the_content( __( 'Read more...', 'titan' ) ); ?>
				<div class="navigation image clear">
					<div class="alignleft"><?php previous_image_link(); ?></div>
					<div class="alignright"><?php next_image_link(); ?></div>
				</div>
				<?php edit_post_link( __( 'Edit', 'titan' ), '<p>', '</p>' ); ?>
				<?php wp_link_pages(); ?>
			</div><!--end entry-->
		</div><!--end post-->
	<?php endwhile; /* rewind or continue if all posts have been fetched */ ?>
		<?php comments_template( '', true ); ?>
	<?php else : ?>
	<?php endif; ?>
</div><!--end content-->
<?php get_sidebar(); ?>
<?php get_footer(); ?>