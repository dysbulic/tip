<?php get_header(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<div class="image">
		<div class="nav prev"><?php next_post_link( '%link','&lsaquo;' ); ?></div>
		<?php image_html(); ?>
		<div class="nav next"><?php previous_post_link( '%link','&rsaquo;' ); ?></div>
	</div>
	<?php partial( 'post' ); ?>
	<?php comments_template(); ?>
<?php endwhile; else : ?>
	<h2 class="center"><?php _e( 'Not Found', 'duotone' ); ?></h2>
	<p class="center"><?php _e( "Sorry, but you are looking for something that isn't here.", 'duotone' ); ?></p>
	<?php get_search_form(); ?>
<?php endif; ?>
<?php get_footer(); ?>