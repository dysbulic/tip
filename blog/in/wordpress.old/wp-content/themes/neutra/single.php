<?php
/**
 * @package WordPress
 * @subpackage Neutra
 */
get_header(); ?>

<div id="page">

	<div id="left">

		<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<div <?php post_class('post'); ?> id="post-<?php the_ID(); ?>">
				<h2 class="title"><?php the_title(); ?></h2>
				<div class="postcontent">
					<?php the_content( __( 'Read more&#8230;', 'neutra' ) ); ?>
					<?php wp_link_pages( 'before=<p class="link-pages">' . __( 'Pages:', 'neutra' ) . ' &after=</p>' ); ?>
					<p class="edit-post"><?php edit_post_link( __( '(Edit this entry)', 'neutra' ) ); ?></p>

					<div class="postmetadata">
						<div class="floatleft">
							<p><span class="category"><?php the_category( ', ' ); ?></span></p>
							<p><?php the_tags( '<span class="tags">', ', ', '</span>' ); ?></p>
						</div><!-- /floatleft -->
						<div class="floatright">
							<p><span class="date"><?php the_time( get_option( 'date_format' ) ); ?></span></p>
							<p><span class="comments"><?php comments_popup_link( __( 'Leave a comment', 'neutra' ), __( '1 Comment', 'neutra' ), __( '% Comments', 'neutra' ) ) ?></span></p>
						</div><!-- /floatright -->
					</div><!-- /postmetadata -->

				</div><!-- /postcontent -->
			</div><!-- /post -->

			<div class="navigation">
				<div class="alignleft"><?php previous_post_link( '%link', '&laquo; %title' ); ?></div>
				<div class="alignright"><?php next_post_link( '%link', '%title &raquo;' ); ?></div>
			</div><!-- /navigation -->

			<?php comments_template( '', true ); ?>

			<?php endwhile; ?>

			<?php else : ?>

			<div class="post">
				<h2 class="title"><?php _e( 'I&rsquo;m sorry, I couldn&rsquo;t find the article!', 'neutra' ); ?></h2>
				<div class="postcontent">
					<p><?php _e( 'Don&rsquo;t worry, you can always search the <strong>archives</strong> or browse the <strong>categories</strong>.', 'neutra' ); ?></p>
				</div>
			</div><!-- /post -->

	<?php endif; ?>

	</div><!-- /left -->

	<div id="right">
		<?php get_sidebar(); ?>
	</div><!-- /right -->

</div><!-- /page -->

<?php get_footer(); ?>
