<?php
/**
 * @package Motion
 */
get_header(); ?>

<div id="main">

	<div id="content">

		<?php if ( have_posts() ) : ?>

		<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>

		<?php /* If this is a category archive */ if ( is_category() ) { ?>
		<h2 id="contentdesc"><?php printf( __( 'Category: <span>%1$s</span>' ), single_cat_title( '', false ) ); ?></h2>
		<?php /* If this is a tag archive */ } elseif (  is_tag() ) { ?>
		<h2 id="contentdesc"><?php printf( __( 'Tag Archive: <span>%1$s</span>' ), single_tag_title( '', false ) ); ?></h2>
		<?php /* If this is a daily archive */ } elseif ( is_day() ) { ?>
		<h2 id="contentdesc"><?php printf( __( 'Archive for <span>%1$s</span>' ), get_the_date() ); ?></h2>
		<?php /* If this is a monthly archive */ } elseif ( is_month() ) { ?>
		<h2 id="contentdesc"><?php printf( __( 'Archive for <span>%1$s</span>' ), get_the_date('F, Y') ); ?></h2>
		<?php /* If this is a yearly archive */ } elseif ( is_year() ) { ?>
		<h2 id="contentdesc"><?php printf( __( 'Archive for <span>%1$s</span>' ), get_the_date('Y') ); ?></h2>
		<?php /* If this is an author archive */ } elseif ( is_author() ) { ?>
		<h2 id="contentdesc"><?php _e( 'Author Archive' ); ?></h2>
		<?php /* If this is a paged archive */ } elseif ( isset( $_GET['paged'] ) && !empty( $_GET['paged'] ) ) { ?>
		<h2 id="contentdesc"><?php _e( 'Blog Archives' ); ?></h2>
		<?php } ?><br/>

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
				<?php the_content( __( 'View full article &raquo;' ) ); ?>
				<?php wp_link_pages(); ?>
			</div>

			<div class="postmetabottom">
				<div class="tags"><?php the_tags( 'Tags: ', ', ', '' ); ?></div>
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
							<a href="<?php the_permalink(); echo $next; ?>" title="<?php esc_attr_e( 'Continue reading' ); the_title_attribute(); ?>"><?php _e('Read&nbsp;More'); ?></a>
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
				<h2 class="posttitle"><a href="#">Oops!</a></h2>
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