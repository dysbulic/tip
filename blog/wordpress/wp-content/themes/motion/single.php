<?php
/**
 * @package WordPress
 * @subpackage Motion
 */
get_header(); ?>

<div id="main">

	<div id="content">

		<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>

		<?php if ( function_exists( 'wp_list_comments' ) ) : ?>
		<div <?php post_class('post'); ?> id="post-<?php the_ID(); ?>">
		<?php else : ?>
		<div class="post" id="post-<?php the_ID(); ?>">
		<?php endif; ?>

			<div class="posttop">
				<h2 class="posttitle"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s' ), the_title_attribute( 'echo=0' ) ); ?>"><?php the_title(); ?></a></h2>
				<div class="postmetatop">
					<div class="categs">
						<?php printf( __( 'Filed under: %1$s by %2$s &mdash; ' ),
						get_the_category_list(', '),
						get_the_author_meta( 'display_name' )
						); ?>
						<?php comments_popup_link( __( 'Leave a comment' ), __( '1 Comment' ), __( '% Comments' ) ) ?>
					</div>
					<div class="date"><span><?php the_time( get_option( 'date_format' ) ); ?></span></div>
				</div>
			</div>

			<div class="postcontent">
				<?php the_content( __( 'Read more &raquo;' ) ); ?>
				<div class="linkpages"><?php wp_link_pages( 'before=<p><span>' . __( 'Pages:' ) . '</span>&link_before=<span>&link_after=</span>' ); ?></div>
			</div>
			<small><?php edit_post_link( __( 'Admin: Edit this entry' ) , '' , '' ); ?></small>

			<div class="postmetabottom">
				<div class="tags"><?php the_tags( __( 'Tags: ' ), ', ', '' ); ?></div>
				<div class="readmore"><?php post_comments_feed_link(__( 'Comments <abbr title="Really Simple Syndication">RSS</abbr> feed' )); ?></div>
			</div>

		</div><!-- /post -->

		<div id="comments">
		<?php comments_template( '', true ); ?>
		</div><!-- /comments -->

		<?php endwhile; ?>

		<?php else : ?>
		<div class="post">
			<div class="posttop">
				<h2 class="posttitle"><a href="#"><?php _e( 'Oops!' ); ?></a></h2>
			</div>
			<div class="postcontent">
				<p><?php _e( 'What you are looking for doesn&rsquo;t seem to be on this page...' ); ?></p>
			</div>
		</div><!-- /post -->
		<?php endif; ?>

		<div id="navigation">
			<div class="alignleft"><?php previous_post_link('&laquo; %link') ?></div>
			<div class="alignright"><?php next_post_link('%link &raquo;') ?></div>
		</div><!-- /navigation -->

	</div><!-- /content -->

	<?php get_sidebar(); ?>

</div><!-- /main -->

<?php get_footer(); ?>