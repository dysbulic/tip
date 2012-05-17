<?php
/**
 * @package Adventure_Journal
 */
?>

<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if ( $wp_query->max_num_pages > 1 ) : ?>
	<div id="nav-above" class="navigation">
		<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'adventurejournal' ) ); ?></div>
		<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'adventurejournal' ) ); ?></div>
                <div class="clear"></div>
	</div><!-- #nav-above -->
<?php endif; ?>

<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if ( ! have_posts() ) : ?>
	<div id="post-0" class="post error404 not-found">
		<h1 class="entry-title"><?php _e( 'Not Found', 'adventurejournal' ); ?></h1>
		<div class="entry-content">
			<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'adventurejournal' ); ?></p>
			<?php get_search_form(); ?>
		</div><!-- .entry-content -->
	</div><!-- #post-0 -->
<?php endif; ?>

<?php while ( have_posts() ) : the_post(); // Start the loop. ?>

	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php if ( is_sticky() && !is_search() ) { ?>
			<div class="tape tr">&nbsp;</div><div class="tape bl">&nbsp;</div>
		<?php } ?>
		<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'adventurejournal' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			<div class="entry-meta">
			<?php if ( !is_sticky() && !is_search() ) {
				adventurejournal_posted_on();
			} ?>
			</div><!-- .entry-meta -->

	<?php if ( is_archive() || is_search() ) : // Only display excerpts for archives and search. ?>
			<div class="entry-summary">
				<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->
	<?php else : ?>
			<div class="entry-content">
				<?php the_content( __( 'Read more <span class="meta-nav">&raquo;</span>', 'adventurejournal' ) ); ?>
			</div><!-- .entry-content -->
	<?php endif; ?>

			<?php if (  get_the_category() || get_the_tag_list() || have_comments() || wp_link_pages() ) : ?>
			<div class="entry-utility">
			<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Post pages:', 'adventurejournal' ), 'after' => '</div>' ) ); ?>
				<?php if ( count( get_the_category() ) ) : ?>
					<span class="cat-links">
						<?php printf( __( '<span class="%1$s">Categories: </span> %2$s', 'adventurejournal' ), 'entry-utility-prep entry-utility-prep-cat-links', get_the_category_list( ', ' ) ); ?>
					</span>
				<?php endif; ?>
				<?php $tags_list = get_the_tag_list( '', ', ' );
				if ( $tags_list ): ?>
					<span class="meta-sep">|</span>
					<span class="tag-links">
						<?php printf( __( '<span class="%1$s">Tags:</span> %2$s', 'adventurejournal' ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list ); ?>
					</span>
				<?php endif; ?>
				<?php if ( comments_open() || have_comments() ) : ?>
				<span class="meta-sep">|</span>
				<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'adventurejournal' ), __( '1 Comment', 'adventurejournal' ), __( '% Comments', 'adventurejournal' ) ); ?></span>
				<?php endif; ?>
			</div><!-- .entry-utility -->
			<?php endif; ?>
			
		</div><!-- #post-## -->

		<?php comments_template( '', true ); ?>

<?php endwhile; // End the loop. Whew. ?>

<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if (  $wp_query->max_num_pages > 1 ) : ?>
	<div id="nav-below" class="navigation clearfix">
		<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'adventurejournal' ) ); ?></div>
		<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'adventurejournal' ) ); ?></div>
	</div><!-- #nav-below -->
<?php endif; ?>