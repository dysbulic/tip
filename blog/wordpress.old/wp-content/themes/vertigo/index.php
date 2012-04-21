<?php
/**
 * @package Vertigo
 */

get_header(); ?>

<div id="content" role="main">

	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'content', get_post_format() ); ?>

	<?php endwhile; ?>

</div><!-- #content -->

<?php get_footer(); ?>