<?php
/**
 * @package WordPress
 * @subpackage Skeptical
 */
?>
<?php
	// Access global variable directly to set content_width
	if ( isset( $GLOBALS['content_width'] ) )
		$GLOBALS['content_width'] = 479;
?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post-meta col-left">
		<?php skeptical_post_meta(); ?>
	</div><!-- /.meta -->

	<div class="middle col-left clearfix">
		<?php
			// Let's get all the post content
			$link_content = $post->post_content;

			// And let's find the first url in the post content
			$link_url = wpcom_themes_url_grabber();

			// Let's make the title a link if there's a link in this link post
			if ( ! empty( $link_url ) ) :
		?>
			<h1 class="title link"><a href="<?php echo $link_url; ?>" target="_blank"><?php the_title(); ?></a></h1>
		<?php else : ?>
			<h1 class="title"><?php the_title(); ?></h1>
		<?php endif; ?>

		<?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'skeptical-featured-image', array( 'class' => 'thumbnail main-image' ) ); endif; ?>

		<?php
			if ( strlen( $link_url ) != strlen( $link_content ) ) :
			add_filter( 'the_content', 'make_clickable' );
		?>
			<div class="entry clearfix">
				<?php the_content( __( 'Read More...', 'woothemes' ) ); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'woothemes' ) . '</span>', 'after' => '</div>' ) ); ?>
			</div><!-- .entry -->
			<?php if ( is_singular() ) the_tags( '<p class="tags">' . __( 'Tags: ', 'woothemes' ), ', ', '</p>' ); ?>
			<?php if ( get_the_author_meta( 'description' ) && is_singular() ) skeptical_author_info(); ?>
		<?php endif; ?>
	</div><!-- /.middle -->
	<div class="fix"></div>
</div><!-- /.post -->