<?php
/**
 * @package Inuit Types
 */
?>
<?php
/*
Template Name: Sitemap Page
*/
?>

<?php get_header(); ?>

			<div id="header-about">

				<h2><?php the_title_attribute(); ?></h2>

			</div>

			<div class="arclist box">

				<h2><?php _e( 'Pages', 'it' ); ?>:</h2>

				<ul>
					<?php wp_list_pages('title_li='); ?>
				</ul>

			</div>

			<div class="fix"></div>

			<div class="arclist box">

				<h2><?php _e( 'Last 60 Blog Posts', 'it' ); ?>:</h2>

				<ul>
					<?php $archive_query = new WP_Query('showposts=60');
		            while ($archive_query->have_posts()) : $archive_query->the_post(); ?>
	                <li><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a> <strong><?php comments_number('0', '1', '%'); ?></strong></li>
	                <?php endwhile; ?>
				</ul>

			</div>

			<div class="fix"></div>

			<div class="arclist box">

				<h2><?php _e( 'Monthly Archives', 'it' ); ?>:</h2>

				<ul>
					<?php wp_get_archives('type=monthly'); ?>
				</ul>

			</div>

			<div class="arclist box">

				<h2><?php _e( 'Categories', 'it' ); ?>:</h2>

				<ul>
					<?php wp_list_categories('title_li=&hierarchical=0&show_count=1') ?>
				</ul>

			</div>

			<div class="fix"></div>

			<br/><br/><br/>

	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>