<?php
/**
 * @package WordPress
 * @subpackage Paperpunch
 */
?><?php get_header(); ?>
	<?php if (have_posts()) : ?>
		<div class="post-box page-box">
			<div class="post-header">
				<h1 class="pagetitle"><?php printf(__("Search results for &lsquo;%s&rsquo;", "paperpunch"), get_search_query()); ?></h1>
			</div><!--end post-header-->
			<div class="entries">
				<img class="archive-comment" src="<?php bloginfo( 'template_url' ); ?>/images/comments-bubble.png" width="17" height="14" alt="<?php _e( 'comment', 'paperpunch' ); ?>"/>
				<ul>
					<?php while (have_posts()) : the_post(); ?>
					<li><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><span class="comments_number"><?php comments_number( '0', '1', '%', '' ); ?></span><span class="archdate"><?php the_time(__( 'n.j.y', 'paperpunch' )); ?></span><?php $title = get_the_title(); echo ( empty( $title ) ) ? '&hellip;' : $title; ?></a></li>
					<?php endwhile; /* rewind or continue if all posts have been fetched */ ?>
				</ul>
			</div><!--end entries-->
		</div><!--end post-box-->
		<div class="pagination">
			<div class="alignleft"><?php next_posts_link(__( '&larr; Older', 'paperpunch' )); ?></div>
			<div class="alignright"><?php previous_posts_link(__( 'Newer &rarr;', 'paperpunch' )); ?></div>
		</div><!--end pagination-->
	<?php else : ?>
		<div class="post-box page-box">
			<div class="post-header">
				<h1 class="pagetitle"><?php printf(__("No results found for '%s'", "paperpunch"), get_search_query()); ?></h1>
			</div><!--end post-header-->
			<div class="entries">
				<?php get_search_form(); ?>
			</div><!--end entries-->
		</div><!--end post-box-->
	<?php endif; ?>
</div><!--end content-->
<?php get_sidebar(); ?>
<?php get_footer(); ?>