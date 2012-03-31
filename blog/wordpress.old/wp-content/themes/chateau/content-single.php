<?php
/**
 * The template for displaying content in the single.php template
 *
 * @package WordPress
 * @subpackage Chateau
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="post-title">
		<h1><?php the_title(); ?></h1>
		<?php chateau_post_info(); ?>
	</header><!-- end .post-title -->

	<div class="post-content clear-fix">
		<?php chateau_post_extra(); ?>

		<div class="post-entry">
			<?php the_content( __( 'Continue reading &raquo;', 'chateau' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'chateau' ) . '</span>', 'after' => '</div>' ) ); ?>
		</div><!-- end .post-entry -->
	</div><!-- end .post-content -->

	<?php if ( get_the_author_meta( 'description' ) && is_multi_author() ) : // If a user has filled out their description and this is a multi-author blog, show a bio on their entries ?>
	<footer class="post-meta">
		<div id="author-info" class="clear-fix">
			<div id="author-avatar">
				<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'chateau_author_bio_avatar_size', 40 ) ); ?>
			</div><!-- #author-avatar -->
			<div id="author-description">
				<h2><?php esc_html( printf( __( 'About %s', 'chateau' ), get_the_author() ) ); ?></h2>
				<?php the_author_meta( 'description' ); ?>
				<div id="author-link">
					<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
						<?php printf( __( 'View all posts by %s &raquo;', 'chateau' ), get_the_author() ); ?>
					</a>
				</div><!-- #author-link -->
			</div><!-- #author-description -->
		</div><!-- #entry-author-info -->
	</footer><!-- end .post-meta -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->