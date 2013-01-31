<?php
/**
 * @package WordPress
 * @subpackage Skeptical
 */
?>
<?php get_header(); ?>

<?php $content_width = 659; ?>

<div id="content" class="page col-full">
	<div id="main" class="col-left">
		<?php while ( have_posts() ) : the_post(); ?>

			<div class="post page clearfix">
				<h1 class="title"><?php the_title(); ?></h1>
				<div class="entry clearfix">
					<?php the_content(); ?>
					<?php edit_post_link( __( '{ Edit }', 'woothemes' ), '<p>', '</p>' ); ?>
				</div>
			</div><!-- /.post -->

			<?php comments_template( '', true ); ?>

		<?php endwhile; // end of the loop. ?>

	</div><!-- /#main -->
	<?php get_sidebar(); ?>
</div><!-- /#content -->

<?php get_footer(); ?>