<?php
/**
 * @package WordPress
 * @subpackage Paperpunch
 */
?><?php get_header(); ?>
	<?php if (have_posts()) : ?>
	<?php while (have_posts()) : the_post(); ?>
		<div class="post-box page-box">
			<div class="post-header">
				<h1 class="pagetitle"><?php the_title(); ?></h1>
			</div><!--end post-header-->
			<div class="entry page clear">
				<?php the_content(); ?>
				<?php edit_post_link( __( 'Edit this', 'paperpunch' ), '<p>', '</p>' ); ?>
				<?php wp_link_pages(); ?>
			</div><!--end entry-->
		</div><!--end post-box-->
	<?php endwhile; /* rewind or continue if all posts have been fetched */ ?>
		<?php comments_template( '', true ); ?>
	<?php else : ?>
	<?php endif; ?>
</div><!--end content-->
<?php get_sidebar(); ?>
<?php get_footer(); ?>