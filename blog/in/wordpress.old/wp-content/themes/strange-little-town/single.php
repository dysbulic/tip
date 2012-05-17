<?php
/**
 * @package WordPress
 * @subpackage StrangeLittleTown
 */
?>

<?php get_header(); ?>

<div id="content">

	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', get_post_format() ); ?>

			<nav id="post-nav" class="paged-navigation contain">
				<h1 class="assistive-text"><?php _e( 'Post navigation', 'strange-little-town' ); ?></h1>
				<?php previous_post_link( '<div class="nav-older">&larr; %link</div>' ); ?>
				<?php next_post_link( '<div class="nav-newer">%link &rarr;</div>' ); ?>
			</nav>

			<?php comments_template(); ?>

		<?php endwhile; ?>

	<?php else: ?>

		<?php get_template_part( 'content', '404' ); ?>

	<?php endif; ?>

</div><!-- content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>