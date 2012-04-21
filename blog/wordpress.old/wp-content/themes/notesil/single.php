<?php get_header(); ?>
	<div id="container">
		<div id="content">

			<?php while ( have_posts() ) : the_post(); ?>

			<div id="post-<?php the_ID(); ?>" class="<?php notesil_post_class(); ?>">
				<h2 class="entry-title"><?php the_title(); ?></h2>
				<div class="entry-content">
					<?php the_content(); ?>
					<?php wp_link_pages( 'before=<div class="page-link">' . __( 'Pages:', 'notesil' ) . '&after=</div>' ); ?>
				</div>
				<div class="entry-meta">
					<?php printf( __( 'By %1$s, on <abbr class="published" title="%2$sT%3$s">%4$s at %5$s</abbr>, under %6$s. %7$s', 'notesil' ),
						'<span class="author vcard"><a class="url fn n" href="' . get_author_posts_url( $authordata->ID, $authordata->user_nicename ) . '" title="' . sprintf( __( 'View all posts by %s', 'notesil' ), $authordata->display_name ) . '">' . get_the_author() . '</a></span>',
						get_the_time( 'Y-m-d' ),
						get_the_time( 'H:i:sO' ),
						the_date( '', '', '', false ),
						get_the_time(),
						get_the_category_list( ', ' ),
						get_the_tag_list( __( 'Tags: ', 'notesil' ), ', ', '.' ),
						the_title_attribute( 'echo=0' ) ); ?>
						 <?php comments_number(); ?>
					</div>
					<div class="entry-actions">
<?php if ( ( 'open' == $post->comment_status) && ( 'open' == $post->ping_status) ) : // Comments and trackbacks open ?>
					<?php printf( __( '<a class="comment-link" href="#respond" title="Post a comment">Post a comment</a> or leave a trackback: <a class="trackback-link" href="%s" title="Trackback URL for your post" rel="trackback">Trackback URL</a>.', 'notesil' ), get_trackback_url() ); ?>
<?php elseif ( !( 'open' == $post->comment_status) && ( 'open' == $post->ping_status) ) : // Only trackbacks open ?>
					<?php printf( __( 'Comments are closed, but you can leave a trackback: <a class="trackback-link" href="%s" title="Trackback URL for your post" rel="trackback">Trackback URL</a>.', 'notesil' ), get_trackback_url() ); ?>
<?php elseif ( ( 'open' == $post->comment_status) && !( 'open' == $post->ping_status) ) : // Only comments open ?>
					<?php _e( 'Trackbacks are closed, but you can <a class="comment-link" href="#respond" title="Post a comment">post a comment</a>.', 'notesil' ); ?>
<?php elseif ( !( 'open' == $post->comment_status) && !( 'open' == $post->ping_status) ) : // Comments and trackbacks closed ?>
					<?php _e( 'Both comments and trackbacks are currently closed.', 'notesil' ); ?>
<?php endif; ?>
<?php edit_post_link( __( 'Edit', 'notesil' ), "\n\t\t\t\t\t<span class=\"edit-link\">", "</span>" ); ?>

				</div>
			</div><!-- .post -->

			<div id="nav-below" class="navigation">
				<div class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">&laquo;</span> %title' ); ?></div>
				<div class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">&raquo;</span>' ); ?></div>
			</div>

			<?php comments_template(); ?>

			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
		<?php get_sidebar(); ?>
	</div><!-- #container -->


<?php get_footer(); ?>
