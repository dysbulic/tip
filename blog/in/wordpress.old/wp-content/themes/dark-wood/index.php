<?php
/**
 * @package WordPress
 * @subpackage Dark Wood
 */
?>
<?php get_header(); ?>

<div id="container">

	<div id="content">

		<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>

		<div class="post">

			<h2 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php esc_attr_e( 'Permanent Link to' ); ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>

			<div class="post-content">
				<?php the_content( __( 'Read more&hellip;' ) ); ?>
			</div>

			<div class="post-pages">
				<?php wp_link_pages( array( 'before' => 'Part: ', 'after' => '', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
			</div>

			<div class="postmeta">
				<span class="date"><img src="<?php bloginfo( 'template_url' ); ?>/images/calendaricon.png" alt="" />&nbsp;<?php the_time( get_option( 'date_format' ) ); ?></span>
				<span class="author"><img src="<?php bloginfo( 'template_url' ); ?>/images/authoricon.png" alt="" />&nbsp;<?php the_author(); ?></span>
				<span class="comment">				
					<img src="<?php bloginfo( 'template_url' ); ?>/images/commentsicon.png" alt="" />&nbsp;
					<?php comments_popup_link( __( 'Leave a comment' ), __( '1 Comment', 'theme' ), __( '% Comments' ) ); ?>					
				</span>
				<?php edit_post_link( __( 'Edit this' ), '<span class="edit">', '</span>' ); ?>
				<div class="taxonomy">
					<span class="categories"><?php _e( 'Categories:' ); ?>&nbsp;<?php the_category( ', ' ); ?></span>
					<?php the_tags( '<span class="tags">' . __( 'Tags:' ).'&nbsp;',', ','</span>' ); ?>
				</div>
			</div><!-- /postmeta -->

		</div><!-- /post -->

		<?php comments_template( '', true ); ?>

		<?php endwhile; ?>

		<?php else : ?>

		<h2><?php _e( '404 - Page not found' ); ?></h2>
		<p><?php _e( 'Oops! I cannot find what you are looking for. Please try again with a different keyword.', 'darkwood' ); ?></p>

		<?php endif; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link( __( '&laquo; Older Entries' ) ); ?></div>
			<div class="alignright"><?php previous_posts_link( __( 'Newer Entries &raquo;' ) ); ?></div>
		</div>

	</div><!-- /content -->

	<?php get_sidebar(); ?>

</div><!-- /container -->

<?php get_footer(); ?>