<?php
/**
 * @package WordPress
 * @subpackage Manifest
 *
 * Template Name: Links
 */

get_header(); ?>

<div id="core-content">
	<div id="links" class="single hentry">
		<div class="post-content">
			<h2 class="entry-title"><?php the_title(); ?></h2>
			<div class="entry-content">
				<ul id="link-list">
					<?php wp_list_bookmarks( 'show_images=0&title_before=<h3>&title_after=</h3>&show_description=1&link_after=<br />&exclude_category=25' ); ?>
				</ul>
			</div>
		</div>
	</div>
</div><!-- #core-content -->

<?php get_footer(); ?>