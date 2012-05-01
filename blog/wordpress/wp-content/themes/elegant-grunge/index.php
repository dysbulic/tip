<?php
/**
 * @package WordPress
 * @subpackage Elegant Grunge
 */
get_header();
?>
<div id="content-container">
	<div id="content">
		<div id="content-body">
			<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ): the_post(); ?>
			<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
				<div class="post-date">
					<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'elegant-grunge' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
						<?php the_time( get_option( 'date_format' ) ); ?>
					</a>
				</div>
				<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'elegant-grunge' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
				<div class="post-author">
					<?php
					printf( __( '<span class="author vcard">By <a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>', 'elegant-grunge' ),
					get_author_posts_url( get_the_author_meta( 'ID' ) ), sprintf( esc_attr__( 'View all posts by %s', 'elegant-grunge' ), get_the_author() ), get_the_author() );
					?>
				</div>
				<div class="entry">
					<?php the_content( __( 'Continue reading', 'elegant-grunge' ) ); ?>
					<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'elegant-grunge' ), 'after' => '</div>' ) );

					if ( null == $post->post_title ) {
					?>
						<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'elegant-grunge' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php _e( 'Continue reading' ); ?></a>
					<?php
					}
					?>
				</div>
				<div class="clear"></div>
				<p class="metadata">
					<?php comments_popup_link( __( 'Leave a comment', 'elegant-grunge' ), __( '1 comment', 'elegant-grunge' ), __( '% comments', 'elegant-grunge' ) ); ?>
					<?php the_tags( '&nbsp;&nbsp;|&nbsp;&nbsp;'.__( 'tags:', 'elegant-grunge' ) . ' ', ', ', '' ); ?>
					<?php if ( count( get_the_category() ) ) : ?>
					| <?php _e( 'posted in ', 'elegant-grunge' )?><?php the_category( ', ' )?>
					<?php endif; ?>
					<?php edit_post_link( __( 'Edit', 'elegant-grunge' ), '&nbsp;&nbsp;|&nbsp;&nbsp;', '' ); ?>
				</p>
				<div class="hr"><hr /></div>
			</div>
			<?php endwhile; ?>
			<div class="navigation">
				<div class="next">
					<?php next_posts_link( __( '&laquo; Older Entries', 'elegant-grunge' ) )?>
				</div>
				<div class="previous">
					<?php previous_posts_link( __( 'Newer Entries &raquo;', 'elegant-grunge' ) )?>
				</div>
			</div>
			<?php else : ?>
			<h2 class="center"><?php _e( 'Not Found', 'elegant-grunge' )?></h2>
			<p class="center">
				<?php _e( 'Sorry, but you are looking for something that isn&rsquo;t here.', 'elegant-grunge' )?>
			</p>
			<?php get_search_form(); ?>
			<?php endif; ?>
		</div>
		<?php get_sidebar(); ?>
	</div>
	<div class="clear"></div>
</div>
<?php get_footer(); ?>