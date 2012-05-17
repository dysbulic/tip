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

<?php if ( 'post' == $post->post_type ) : ?>
	<div class="post-meta col-left">
		<?php skeptical_post_meta(); ?>
	</div><!-- /.meta -->
<?php endif; ?>

	<div class="middle col-left clearfix">
		<h1 class="title">
		<?php if ( ! is_single() ) : ?>
			<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'woothemes' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
		<?php else : ?>
			<?php the_title(); ?>
		<?php endif; ?>
		</h1>

		<?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'skeptical-featured-image', array( 'class' => 'thumbnail main-image' ) ); endif; ?>

		<div class="entry clearfix">
			<?php the_content( __( 'Read More...', 'woothemes' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'woothemes' ) . '</span>', 'after' => '</div>' ) ); ?>
		</div><!-- .entry -->
		<?php if ( is_singular() ) the_tags( '<p class="tags">' . __( 'Tags: ', 'woothemes' ), ', ', '</p>' ); ?>
		<?php if ( get_the_author_meta( 'description' ) && is_singular() ) skeptical_author_info(); ?>
	</div><!-- /.middle -->
	<div class="fix"></div>
</div><!-- /.post -->