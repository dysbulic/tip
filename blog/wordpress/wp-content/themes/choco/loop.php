<?php
/**
 * The loop that displays posts.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop.php or
 * loop-template.php, where 'template' is the loop context
 * requested by a template. For example, loop-index.php would
 * be used if it exists and we ask for the loop with:
 * <code>get_template_part( 'loop', 'index' );</code>
 *
 * @package WordPress
 * @subpackage Choco
 */
?>

<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if ( ! have_posts() ) : ?>
	<h2 class="center"><?php _e( 'Not Found', 'choco' ); ?><</h2>
	<p><?php _e( 'Sorry, but nothing matched your search criteria.', 'choco' ); ?></p>
	<?php get_search_form(); ?>
<?php endif; ?>

<?php while ( have_posts() ) : the_post(); ?>
	<div <?php post_class(); ?>>
		<h2 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
		<div class="date">
			<div class="bg">
				<a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
					<span class="day"><?php the_time( 'd' ); ?></span>
					<span><?php the_time( 'M' ); ?></span>
				</a>
			</div>
		</div><!-- .date -->
	
		<div class="entry">
			<?php if( has_post_thumbnail() ){ ?>
				<a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
			<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'post-thumbnail', 'alt' => get_the_title(), 'title' => get_the_title() ) ); ?>
				</a>
			<?php } ?>
			<?php the_content( 'Read the rest of this entry &raquo;' ); ?>
			<div class="cl">&nbsp;</div>
			<?php wp_link_pages( array( 'before' => '<div class="page-navigation"><p><strong>'. __( 'Pages:', 'choco' ) .' </strong> ', 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
			
		</div><!-- .entry -->
	
		<div class="meta">
			<div class="bg">
				<span class="comments-num"><?php comments_popup_link( 'Leave a comment', '1 Comment', '% Comments' ); ?></span>
				<p><?php _e( 'Posted by', 'choco' ); ?> <?php the_author_posts_link(); ?> <?php _e( 'on', 'choco' ); ?> <?php the_date( get_option( 'date_format' ) ); ?> <?php _e( 'in', 'choco' ); ?> <?php the_category( ', ' ); ?></p>
			</div>
			<div class="bot">&nbsp;</div>
		</div><!-- .meta -->
		
		<?php the_tags( '<p class="tags">' . __( 'Tags:', 'choco' ) . ' ', ', ', '</p>' ); ?>
		
	</div>
<?php endwhile; // End the loop.?>

<?php if (  $wp_query->max_num_pages > 1 ) : ?>
	<div class="post-navigation clear-fix">
		<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'choco' ) ); ?></div>
		<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'choco' ) ); ?></div>
	</div><!-- .post-navigation -->
<?php endif; ?>