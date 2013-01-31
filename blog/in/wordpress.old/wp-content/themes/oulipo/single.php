<?php
/**
 * @package WordPress
 * @subpackage Oulipo
 */
?>
<?php get_header(); ?>

<div id="content">

	<div id="entry-content">

	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h2><?php the_title(); ?></h2>
				<p class="date"><?php the_time( 'F jS, Y' ); ?> <?php comments_popup_link( '&sect; <span class="commentcount">' . __( 'Leave a Comment', 'oulipo' ) . '</span>', '&sect; <span class="commentcount">' . __( '1 Comment', 'oulipo' ) . '</span>', '&sect; <span class="commentcount">' . __( '% Comments', 'oulipo' ) . '</span>' ); ?></p>

				<div class="entry">
					<?php the_content( __( '&laquo; Read the rest of this entry &raquo;', 'oulipo' ) ); ?>
					<?php wp_link_pages( array( 'before' => '<p>' . __( 'Page: ', 'oulipo' ), 'after' => '</p>', 'next_or_number' => 'number' ) ); ?>

					<p class="tags"><?php the_tags( __( '<strong>Tagged:</strong> ', 'oulipo' ), ', ', '' ); ?></p>
					<?php comments_template(); ?>

				</div>
			</div>

		<?php endwhile; ?>

		<div class="navigation">
			<p class="alignleft"><?php next_post_link( '&laquo; %link' ); ?></p>
			<p class="alignright"><?php previous_post_link( '%link &raquo;' ); ?></p>
		</div>

	<?php else : ?>

		<div class="entry">
			<span class="error"><img src="<?php bloginfo( 'template_directory' ); ?>/images/mal.png" alt="error duck" /></span>
			<p><?php _e( 'Hmmm, seems like what you were looking for isn&rsquo;t here. You might want to give it another try.', 'oulipo' ); ?></p>
		</div>

	<?php endif; ?>

</div> <!-- close entry-content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>