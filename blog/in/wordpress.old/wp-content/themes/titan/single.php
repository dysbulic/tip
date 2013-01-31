<?php
/**
 * @package Titan
 */
?>
<?php get_header(); ?>
	<?php if ( have_posts() ) : ?>
	<?php while ( have_posts() ) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="post-header">
				<div class="tags"><?php the_tags( '<span>Tags</span> <p>', ', ', '</p>' ); ?></div>
				<h1><?php the_title(); ?></h1>
				<div class="author">
					<?php
						titan_posted_by();
						if ( is_multi_author() )
							_e( 'on', 'titan' );
					?>
					<?php the_time( get_option( 'date_format' ) ); ?>
				</div>
			</div><!--end post header-->
			<div class="entry clear">
				<?php if ( function_exists( 'add_theme_support' ) ) the_post_thumbnail( array( 250, 9999 ), array( 'class' => ' alignleft border' ) ); ?>
				<?php the_content( __( 'Read more...', 'titan' ) ); ?>
				<?php edit_post_link( __( 'Edit', 'titan' ), '<p>', '</p>' ); ?>
				<?php wp_link_pages(); ?>
			</div><!--end entry-->
			<div class="meta clear">
				<p><?php _e( 'From &rarr;', 'titan' ); ?> <?php the_category( ', ' ); ?></p>
			</div><!--end meta-->
		</div><!--end post-->
	<?php endwhile; /* rewind or continue if all posts have been fetched */ ?>
		<?php comments_template( '', true ); ?>
		
	<div class="navigation index">
		<div class="alignleft"><?php next_post_link( '&laquo; %link' ); ?></div>
		<div class="alignright"><?php previous_post_link( '%link &raquo;' ); ?></div>
	</div><!--end navigation-->
	<?php else : ?>
	<?php endif; ?>
</div><!--end content-->
<?php get_sidebar(); ?>
<?php get_footer(); ?>