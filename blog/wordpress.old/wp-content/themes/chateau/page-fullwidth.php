<?php
/**
 * Template Name: Full width Template
 *
 * @package WordPress
 * @subpackage Chateau
 */
get_header(); ?>

<?php $content_width = 960; ?>

<div id="primary">
	<div id="content" class="full-width clear-fix" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'page' ); ?>

			<?php comments_template( '', true ); ?>

		<?php endwhile; // end of the loop. ?>

	</div><!-- end #content -->
</div><!-- end #primary -->
<?php get_footer(); ?>