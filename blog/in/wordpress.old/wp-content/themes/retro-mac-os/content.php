<?php
/**
 * @package WordPress
 * @subpackage Retro MacOS
 */
?>

			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h2 class="entry-title"><span><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'retro' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></span></h2>
				<small>
				<?php the_time( 'l, F jS, Y' ); ?>
				<?php if ( is_multi_author() ) : ?>
					<?php
						printf( __( 'by <span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>', 'retro' ),
							get_author_posts_url( get_the_author_meta( 'ID' ) ),
							sprintf( esc_attr__( 'View all posts by %s', 'retro' ), get_the_author() ),
							get_the_author()
						);
					?>
				<?php endif; ?>
				</small>

				<div class="entry">
					<?php the_content( __( 'Read the rest of this entry &raquo;', 'retro' ) ); ?>
					<?php wp_link_pages( array( 'before' => '<p><strong>' . __( 'Pages:', 'retro' ) . '</strong> ', 'after' => '</p>', 'next_or_number' => 'number' ) ); ?>
					<p class="postmetadata">
						<span class="cat-links"><?php printf( __( 'Posted in %1$s', 'retro' ), get_the_category_list( ', ' ) ); ?></span>
						<span class="sep"> | </span>
					<?php if ( comments_open() || ( '0' != get_comments_number() && ! comments_open() ) ) : ?>
						<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'retro' ), __( '1 Comment', 'retro' ), __( '% Comments', 'retro' ) ); ?></span>
					<?php endif; ?>
						<?php edit_post_link( __( 'Edit', 'retro' ), ' | ', '' ); ?>
					</p>
				</div>
			</div>