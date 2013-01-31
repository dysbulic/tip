<?php
/**
 * @package WordPress
 * @subpackage Brand New Day
 */

get_header();

?>

	<div id="content" class="content">
		<?php
			if ( is_search() ) {
				echo "<h2 class='page_title'>" . __( 'You searched for' , 'new-theme' ) . " <em>" . get_search_query() . "</em></h2>";
				get_search_form();
				echo "<hr />";
			}
		?>

	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>
			<div class="post-sidebar<?php if ( is_sticky() ) echo ' sticky'; ?>">
				<div class="post-date">
					<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e( 'Permanent Link to' , 'brand-new-day' ) ?> <?php the_title_attribute(); ?>">
						<span class="post-month"><?php the_time('M'); ?></span>
						<span class="post-day"><?php the_time('j'); ?></span>
					</a>
				</div>
				<div class="edit-link"><?php edit_post_link( __( 'Edit', 'brand-new-day' ), '', ''); ?></div>
			</div>
			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
				<h2 class="page_title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e( 'Permanent Link to' , 'brand-new-day' ) ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
				<small><span class="post-author"><?php _e( 'by' , 'brand-new-day' ); ?> <?php the_author() ?></span></small>

				<div class="entry">
					<?php the_content( __( 'Read the rest of this entry' , 'brand-new-day' ) . ' &raquo;'); ?>
				</div>

				<?php wp_link_pages(array('before' => '<p class="clear"><strong>' . __( 'Pages:' , 'brand-new-day' ) . '</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

				<p class="postmetadata clear post-categories"><?php the_category(' ') ?></p>

				<div class="post-comments"><?php comments_popup_link( __( 'No Comments' , 'brand-new-day' ) . ' &#187;', '1 ' . __( 'Comment' , 'brand-new-day' ) . ' &#187;', '% ' . __( 'Comments' , 'brand-new-day' ) . ' &#187;'); ?></div>
				<hr />
			</div>

		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo;' . __( 'Older Entries' , 'brand-new-day' ) ) ?></div>
			<div class="alignright"><?php previous_posts_link( __( 'Newer Entries' , 'brand-new-day' ) . ' &raquo;') ?></div>
		</div>

	<?php else : ?>

		<h2 class="page_title"><?php _e( 'Not Found' , 'brand-new-day' ) ?></h2>
		<p class="aligncenter"><?php _e( 'Sorry, no posts matched your criteria.', 'brand-new-day' ) ?></p>
		<?php get_search_form(); ?>

	<?php endif; ?>

	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
