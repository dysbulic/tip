<?php
/**
 * @package Greyzed
 */
/*
Template Name: Archives
*/
get_header(); ?>
	<div id="container">
<?php get_sidebar(); ?>
	<div id="content" role="main">
	<div class="column">
			<div class="posttitle">
				<h2 class="pagetitle"><?php _e( 'Archives by Month:', 'greyzed' ); ?></h2>
			</div>
			<div class="entry">
				<ul>
				<?php wp_get_archives('type=monthly'); ?>
				</ul>
			</div>
				<div class="posttitle">
					<h2 class="pagetitle"><?php _e( 'Archives by Subject:', 'greyzed' ); ?></h2>
				</div>
			<div class="entry">
				<ul>
				<?php wp_list_categories('show_count=1&title_li='); ?>
				</ul>
			</div>
		</div>
	</div>
<?php get_footer(); ?>
<?php get_footer(); ?>
