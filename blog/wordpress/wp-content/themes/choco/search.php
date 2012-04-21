<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage Choco
 */
get_header(); ?>
	<div class="list-page">
		<?php if ( have_posts() ) : ?>
			<h1 class="pagetitle"><?php _e( 'Search results for', 'choco' ); ?> &#8216;<?php the_search_query(); ?>&#8217;</h1>
			<?php while ( have_posts() ) : the_post(); ?>
				<div <?php post_class( 'post' ); ?>>
					<h2 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
					<?php if ( $post->post_type=='post' ): ?>
						<div class="date">
							<div class="bg">
								<a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
									<span class="day"><?php the_time( 'd' ); ?></span>
									<span><?php the_time( 'M' ); ?></span>
								</a>
							</div>
						</div>	
					<?php endif ?>
					<div class="entry">
					<?php if( has_post_thumbnail() ){ ?>
						<a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
					<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'post-thumbnail', 'alt' => get_the_title(), 'title' => get_the_title() ) ); ?>
						</a>
					<?php } ?>
						<?php the_content( 'Read the rest of this entry &raquo;' ); ?>
						<div class="cl">&nbsp;</div>
					</div>
					<?php if ( $post->post_type=='post' ): ?>
						<div class="meta">
							<div class="bg">
								<span class="comments-num"><?php comments_popup_link( 'Leave a comment', '1 Comment', '% Comments' ); ?></span>
								<p><?php _e( 'Posted by', 'choco' ); ?> <?php the_author_posts_link(); ?> <?php _e( 'on', 'choco' ); ?> <?php the_date( get_option( 'date_format' ) ); ?> <?php _e( 'in', 'choco' ); ?> <?php the_category( ', ' ); ?></p>
							</div>
							<div class="bot">&nbsp;</div>
						</div>
						
						<?php the_tags( '<p class="tags">' . __( 'Tags:', 'choco' ) . ' ', ', ', '</p>' ); ?>
						
					<?php endif; ?>
				</div>
			<?php endwhile; ?>
			<?php if (  $wp_query->max_num_pages > 1 ) : ?>
				<div class="post-navigation clear-fix">
					<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'choco' ) ); ?></div>
					<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'choco' ) ); ?></div>
				</div>
			<?php endif; ?>
		<?php else : ?>
			<h1 class="pagetitle"><?php _e( 'No posts found. Try a different search?', 'choco' ); ?></h1>
			<?php get_search_form(); ?>
		<?php endif; ?>
	</div>
	<?php get_sidebar(); ?>
<?php get_footer(); ?>