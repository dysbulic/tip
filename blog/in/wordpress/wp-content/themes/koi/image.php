<?php
/**
 * @package WordPress
 * @subpackage Koi
 */
get_header();
$content_width = 873;
?>

	<div id="content" class="full">

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<div <?php post_class(); ?>>
			<h2 class="post-title"><a href="<?php echo get_permalink( $post->post_parent ); ?>" rev="attachment"><?php echo get_the_title( $post->post_parent ); ?></a> &raquo; <?php the_title(); ?></h2>
			<p class="post-date"><span class="day"><?php the_time( 'd' ); ?></span> <span class="month"><?php the_time( 'M' ); ?></span> <span class="year"><?php the_time( 'Y' ); ?></span> <span class="postcomment"><?php comments_popup_link( __( 'Leave a Comment', 'ndesignthemes' ), __( '1 Comment', 'ndesignthemes' ), __( '% Comments', 'ndesignthemes' ) ); ?></span></p>
			<p class="post-data">
				<span class="postauthor"><?php printf( __( 'by %1$s', 'ndesignthemes' ), sprintf( '<a class="url fn n" href="%1$s" title="%2$s">%3$s</a>', get_author_posts_url( get_the_author_meta( 'ID' ) ), esc_attr( sprintf( __( 'View all posts by %s', 'ndesignthemes' ), get_the_author() ) ), get_the_author() ) ); ?></span>
				<?php edit_post_link( __( '[Edit]', 'ndesignthemes' ) ); ?>
			</p>
			<p class="attachment"><a href="<?php echo wp_get_attachment_url( $post->ID ); ?>"><?php echo wp_get_attachment_image( $post->ID, 'auto' ); ?></a></p>
			<?php the_content( __( 'More', 'ndesignthemes' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<p><strong>' . __( 'Pages:' , 'ndesignthemes' ) . '</strong> ', 'after' => '</p>', 'next_or_number' => 'number' ) ); ?>

			<p class="image-nav">
				<span class="previous"><?php previous_image_link(); ?></span>
				<span class="next"><?php next_image_link(); ?></span>
			</p>

		</div>
		<!--/post -->

	<?php comments_template(); ?>

	<?php endwhile; endif; ?>

	</div>
	<!--/content -->

<?php get_footer(); ?>