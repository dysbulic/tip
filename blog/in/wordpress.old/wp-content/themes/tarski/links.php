<?php
/*
Template Name: Links
*/
?><?php get_header(); ?>
<div id="intro">
<?php while ( have_posts() ) : the_post(); ?>
<h1><?php the_title(); ?></h1>
<?php the_content(); ?>
<?php endwhile; // end of the loop. ?>
</div>

<div id="primary">
	<?php wp_list_bookmarks('title_before=<h3>&title_after=</h3>&show_images=false&orderby=name'); ?>
</div>
<?php if(!get_option('tarski_hide_sidebar')) { get_sidebar(); } ?>
<?php get_footer(); ?>
