<?php
/**
 * Template Name: Full-width, no sidebar
 * @package Spectrum
 */

get_header(); ?>

		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
			<div class="main-title">
				<h3><?php the_title(); ?></h3>
			</div>
			<div class="entry">
				<?php the_content( 'Read the rest of this entry &raquo;' ); ?>
			</div>
		</div>

		<?php comments_template(); ?>

		<?php endwhile; endif; ?>

</div>

<?php get_footer(); ?>