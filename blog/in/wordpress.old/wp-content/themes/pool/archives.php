<?php
/*
Template Name: Archives
*/
?>

<?php get_header(); ?>

<div id="bloque">

	<div id="noticias">
		<div class="entrada">
			<h2><?php _e( 'Archives', 'pool' ); ?></h2>
			<h3><?php _e( 'By month:', 'pool'); ?></h3>
			<ul>
				<?php wp_get_archives( 'type=monthly&show_post_count=1' ); ?>
			</ul>

			<h3><?php _e( 'By category:', 'pool' ); ?></h3>
			<ul>
				<?php wp_list_categories( 'orderby=name&show_count=1&feed=RSS' ); ?>
			</ul>
		</div><!-- #entrada -->
	</div><!-- #noticias -->

<?php get_footer(); ?>
