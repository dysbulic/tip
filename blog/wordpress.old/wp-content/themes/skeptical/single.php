<?php
/**
 * @package WordPress
 * @subpackage Skeptical
 */
?>
<?php get_header(); ?>

	<div id="content" class="col-full">
		<div id="main" class="col-left">
		<?php if ( have_posts() )  while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', get_post_format() ); ?>

			<div class="nav-entries">
				<div class="nav-prev fl"><?php previous_post_link( '%link', __( '&laquo; Previous post', 'woothemes' ) ); ?></div>
				<div class="nav-next fr"><?php next_post_link( '%link', __( 'Next post &raquo;', 'woothemes' ) ); ?></div>
				<div class="fix"></div>
			</div>

			<?php comments_template( '', true ); ?>

		<?php endwhile; ?>
		</div><!-- /#main -->

	<?php get_sidebar(); ?>

	</div><!-- /#content -->

<?php get_footer(); ?>