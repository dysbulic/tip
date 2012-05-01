<?php
/**
 * @package WordPress
 * @subpackage Motion
 */
get_header(); ?>

<div id="main">

	<div id="content">

		<h2 id="contentdesc"><?php _e( 'Latest Entries &raquo;' ); ?></h2>

		<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>

		<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">

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
				<?php the_content( __( 'View full article &raquo;' ) ); ?>
				<?php wp_link_pages(); ?>
			</div>

			<div class="postmetabottom">
				<div class="tags"><?php the_tags( __( 'Tags: ' ), ', ', '' ); ?></div>
				<div class="readmore">
					<span>
						<?php

						$moretag = strpos($post->post_content, '<!--more');
						$postpaged = strpos($post->post_content, '<!--nextpage');
						$next= '';

						if (!$moretag && !$postpaged)
							$full = true;
						else {
							$full = false;						
							if (!$moretag)
								$next = '2/';
							else
								$next = '#more-'.$id;
						}

						if( $full == true && $post->comment_status == 'open' ) { ?>
							<a href="<?php the_permalink() ?>#comments" title="<?php printf(__('Comment on %s'), the_title_attribute()); ?>"><?php _e('Comment'); ?> </a>
						<?php } elseif(!$full && $post->comment_status == 'open') { ?>
							<a href="<?php the_permalink(); echo $next; ?>" title="<?php printf(__('Continue reading %s and comment'), the_title_attribute()); ?>"><?php _e('Read&nbsp;More&nbsp;&amp;&nbsp;Comment'); ?></a>
						<?php } elseif(!$full && $post->comment_status == 'closed') { ?>
							<a href="<?php the_permalink(); echo $next; ?>" title="<?php _e('Continue reading'); the_title_attribute(); ?>"><?php _e('Read&nbsp;More'); ?></a>
						<?php } else { ?>
							<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s'), the_title_attribute()); ?>"><?php _e('Permalink'); ?> </a>
						<?php } ?>
					</span>
				</div>			
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
		</div>
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