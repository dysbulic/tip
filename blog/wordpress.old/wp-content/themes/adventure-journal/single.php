<?php
/**
 * @package Adventure_Journal
 */

get_header(); ?>

	<div id="content" class="clearfix">
		<div id="main-content">
			<?php
			//Start the Loop
			if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<h1 class="entry-title"><?php the_title(); ?></h1>
					<div class="entry-meta">
						<?php adventurejournal_posted_on(); ?>
					</div><!-- .entry-meta -->

					<div class="entry-content" class="clearfix">
						<?php the_content(); ?>
					</div>

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

					<div id="nav-below-post" class="navigation clearfix">
						<h3 class="assistive-text"><?php _e( 'Post navigation', 'adventurejournal' ); ?></h3>
						<div class="nav-previous"><?php previous_post_link( '%link', __( '<span class="meta-nav">&larr;</span> Older post', 'adventurejournal' ) ); ?></div>
						<div class="nav-next"><?php next_post_link( '%link', __( 'Newer post <span class="meta-nav">&rarr;</span>', 'adventurejournal' ) ); ?></div>
					</div><!-- #nav-below -->

				</div>

				<?php if ( comments_open() || have_comments() ) {
					comments_template( '', true );
				} ?>
	        <?php endwhile; else: ?>
	        	<p><?php _e( 'Sorry, no posts matched your criteria.','adventurejournal' ); ?></p>
	        <?php endif; ?>

		</div><!-- #main-content -->

		<?php get_sidebar(); ?>
	</div><!-- #content -->

<?php get_footer(); ?>