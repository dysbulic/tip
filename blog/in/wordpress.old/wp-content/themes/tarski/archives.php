<?php
/*
Template Name: Archives
*/
?><?php get_header(); ?>
	<div id="intro">
		<h1><?php _e( 'Archives', 'tarski' ); ?></h1>
	</div>
	<div id="primary">
		<h3><?php _e( 'Monthly archives', 'tarski' ); ?></h3>

		<ul class="archivelist">
		<?php wp_get_archives( array( 'type' => 'monthly', 'show_post_count' => true ) ); ?>  
		</ul>
	</div>
	
	<div id="secondary">
		<h3><?php _e( 'Category archives', 'tarski' ); ?></h3>
		<ul class="archivelist">
		<?php wp_list_categories( array( 'title_li' => '', 'order' => 'desc' ) ); ?>
		</ul>
	</div>
<?php get_footer(); ?>