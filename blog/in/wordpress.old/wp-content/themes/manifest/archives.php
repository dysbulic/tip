<?php
/**
 * @package WordPress
 * @subpackage Manifest
 *
 * Template Name: Archives
 */

get_header(); ?>

<div id="core-content">
	<div id="archives" class="single hentry">
		<h2 class="entry-title"><?php the_title(); ?></h2>

		<h3><?php _e( 'Recent Articles', 'manifest' ); ?></h3>

		<?php query_posts( 'cat=&showposts=5' ); ?>
		<ul id="recent-posts">
			<?php while ( have_posts() ) : the_post(); ?>
 			<li>
 				<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
 				<div class="post-date"><abbr class="published" title="<?php the_time( 'Y-m-d\TH:i:sO' ); ?>"><?php the_date( 'F j, Y' ); ?></abbr></div>
 			</li>
			<?php endwhile; ?>
 		</ul>

		<div id="date">
			<h3><?php _e( 'Months', 'manifest' ); ?></h3>
			<ul>
				<?php wp_get_archives( 'type=monthly&show_post_count=1' ); ?>
			</ul>

			<h3><?php _e( 'Categories', 'manifest' ); ?></h3>
 			<ul class="categories">
 			<?php wp_list_categories( 'title_li=&show_count=1' ); ?>
 			</ul>
		</div>

		<div id="cattags">
			<h3><?php _e( 'Tags', 'manifest' ); ?></h3>
			<?php wp_tag_cloud( 'format=list' ); ?>
		</div>
	</div><!-- #archives -->
</div><!-- #core-content -->

<?php get_footer(); ?>