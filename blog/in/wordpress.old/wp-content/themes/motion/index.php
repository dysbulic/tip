<?php
/**
 * @package Motion
 */
get_header(); ?>

<div id="main">

	<div id="content">

		<h2 id="contentdesc"><?php _e( 'Results &raquo;' ); ?></h2>

		<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>

		<?php if ( function_exists( 'wp_list_comments' ) ) : ?>
		<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
		<?php else : ?>
		<div class="post" id="post-<?php the_ID(); ?>">
		<?php endif; ?>

			<div class="posttop">
				<h2 class="posttitle"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s' ), the_title_attribute( 'echo=0' ) ); ?>"><?php the_title(); ?></a></h2>
				<div class="postmetatop">
					<div class="categs">
						<?php motion_post_meta(); ?>
						<?php comments_popup_link( __( 'Leave a comment' ), __( '1 Comment' ), __( '% Comments' ) ) ?>
					</div>
					<div class="date"><span><?php the_time( get_option( 'date_format' ) ); ?></span></div>
				</div>
			</div>

			<div class="postcontent">
				<?php the_excerpt(); ?>
				<?php wp_link_pages(); ?>
			</div>

			<div class="postmetabottom">
				<div class="tags"><?php the_tags( __( 'Tags: ' ), ', ', '' ); ?></div>
				<div class="readmore"><span><a href="<?php the_permalink(); ?>"><?php _e( 'Read more' ); ?></a></span></div>
			</div>

		</div><!-- /post -->

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
			<?php if ( function_exists( 'wp_pagenavi' ) ) : ?>
			<?php wp_pagenavi(); ?>
			<?php else : ?>
				<div class="alignleft"><?php next_posts_link( __( '&laquo; Older Entries' ) ); ?></div>
				<div class="alignright"><?php previous_posts_link( __( 'Newer Entries &raquo;' ) ); ?></div>
			<?php endif; ?>
		</div><!-- /navigation -->

	</div><!-- /content -->

	<?php get_sidebar(); ?>

</div><!-- /main -->

<?php get_footer(); ?>