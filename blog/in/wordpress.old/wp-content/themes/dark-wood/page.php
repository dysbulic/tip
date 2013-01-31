<?php
/**
 * @package WordPress
 * @subpackage Dark Wood
 */
?>
<?php get_header(); ?>

<div id="container">

	<div id="content">

		<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>

		<div class="post">

			<h2 class="post-title"><?php the_title(); ?></h2>

			<div class="post-content">
				<?php the_content( __('Read more&hellip;') ); ?>
			</div>

			<div class="post-pages">
				<?php wp_link_pages( array( 'before' => 'Part: ', 'after' => '', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
			</div>

			<?php if ('open' == $post->comment_status) : ?>
			<div class="postmeta">
				<span class="comment">
					<img src="<?php bloginfo( 'template_url' ); ?>/images/commentsicon.png" alt="" />&nbsp;
					<a href="<?php comments_link(); ?>"><?php comments_number( __( 'No comments' ), __( '1 comment' ), __( '% comments' ) ); ?></a>
				</span>
			</div><!-- /postmeta -->
			<?php endif; ?>
			

		</div><!-- /post -->

		<?php comments_template( '', true ); ?>

		<?php endwhile; ?>

		<?php else : ?>

		<h2><?php _e('404 - Page not found'); ?></h2>
		<p><?php _e( 'Oops! I cannot find what you are looking for. Please try again with a different keyword.', 'darkwood' ); ?></p>

		<?php endif; ?>

	</div><!-- /content -->

	<?php get_sidebar(); ?>

</div><!-- /container -->

<?php get_footer(); ?>