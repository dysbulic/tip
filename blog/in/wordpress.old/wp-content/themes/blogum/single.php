<?php
/**
 * @package WordPress
 * @subpackage Blogum
 */
?>

<?php get_header(); ?>

<div id="content" role="main">

	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'content', 'single' ); ?>

		<nav id="nav-below" class="clear">
			<span class="nav-previous"><?php previous_post_link( '%link', __( '<span class="meta-nav">&larr;</span> Previous post', 'blogum' ) ); ?></span>
			<span class="nav-next"><?php next_post_link( '%link', __( 'Next post <span class="meta-nav">&rarr;</span>', 'blogum' ) ); ?></span>
		</nav><!-- #nav-below -->

	<?php endwhile; ?>

</div><!-- #content -->

<?php get_sidebar(); ?>

<?php comments_template( '', true ); ?>

<?php get_footer(); ?>