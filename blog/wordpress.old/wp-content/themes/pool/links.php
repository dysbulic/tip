<?php
/*
Template Name: Links
*/
?>

<?php get_header(); ?>

<div id="bloque">
	<div id="noticias">
		<div class="entrada">
			<h2><?php _e( 'Links:', 'pool' ); ?></h2>
			<ul>
				<?php wp_list_bookmarks(); ?>
			</ul>
		</div><!-- #noticias -->
	</div><!-- #bloque -->

<?php get_footer(); ?>
