<?php
/**
 * @package WordPress
 * @subpackage Blogum
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'clear' ); ?>>
	<?php
		// Find the first image attachment in a post
		$first_image = '';
		$output = preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches );
		$first_image = $matches [1] [0];
	?>
	<header class="post-meta">
		<h1><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'blogum' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

		<?php blogum_post_data(); ?>

		<?php blogum_comments_popup_link(); ?>

		<?php edit_post_link( __( 'Edit', 'blogum' ), '<div class="post-edit">', '</div>' ); ?>
	</header><!-- .post-meta -->

	<div class="post-content">
		<div class="first-image">
			<a href="<?php echo $first_image; ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'blogum' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
				<img class="entry-image" src="<?php echo $first_image; ?>" alt="" />
			</a>
		</div><!-- .first-image -->
		<?php
			if ( ! empty( $first_image ) ) {
				// output the content stripped of the first image
				$image_content = get_the_content( __( 'Read More', 'blogum' ) );
				$image_content = preg_replace( '/\[caption.*\[\/caption\]|<img[^>]+./', '', $image_content, 1 );
				echo '<p>' .$image_content. '</p>';
			} else {
				// just output the regular old content
				the_content( __( 'Read More', 'blogum' ) );
			}
		?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'blogum' ) . '</span>', 'after' => '</div>' ) ); ?>
	</div><!-- .post-content -->
</article><!-- #post-<?php the_ID(); ?> -->