<?php
/**
 * @package WordPress
 * @subpackage Retro MacOS
 */
get_header(); ?>

	<div id="content" class="narrowcolumn">

	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content' ); ?>

		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link( __( '&laquo; Previous Entries', 'retro' ) ); ?></div>
			<div class="alignright"><?php previous_posts_link( __( 'Next Entries &raquo;', 'retro' ) ); ?></div>
		</div>

	<?php else : ?>

		<h2 class="center"><?php _e( 'Not Found', 'retro' ); ?></h2>
		<p class="center"><?php _e( "Sorry, but you are looking for something that isn't here.", 'retro' ); ?></p>
		<?php get_search_form(); ?>

	<?php endif; ?>

	</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>