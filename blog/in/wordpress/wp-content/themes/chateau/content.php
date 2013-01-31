<?php
/**
 * The default template for displaying content
 *
 * @package WordPress
 * @subpackage Chateau
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="post-title">
		<?php if ( is_sticky() ) : ?>
			<hgroup>
				<h1><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'chateau' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
				<h2 class="entry-format featured"><?php _e( 'Featured', 'chateau' ); ?></h2>
			</hgroup>
			<div class="post-info clear-fix">
				<p>
					<?php
						printf( __ ( 'Posted <span class="by-author"> by <span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span></span> in %4$s'),
							esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
							esc_attr( sprintf( __( 'View all posts by %s', 'chateau' ), get_the_author() ) ),
							esc_html( get_the_author() ),
							get_the_category_list( ', ' )
						);
					?>
				</p>
				<p class="post-com-count">
					<strong>&asymp; <?php comments_popup_link( __( 'Leave a Comment', 'chateau' ), __( '1 Comment', 'chateau' ), __( '% Comments', 'chateau' ) ); ?></strong>
				</p>
			</div><!-- end .post-info -->
		<?php else : ?>
			<h1><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'chateau' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
			<?php if ( 'post' == get_post_type() ) : ?>
				<?php chateau_post_info(); ?>
			<?php endif; ?>
		<?php endif; ?>
	</header><!-- end .post-title -->
	<div class="post-content clear-fix">

		<?php chateau_post_extra(); ?>

		<div class="post-entry">
			<?php the_content( __( 'Continue reading &raquo;', 'chateau' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'chateau' ) . '</span>', 'after' => '</div>' ) ); ?>
		</div><!-- end .post-entry -->

	</div><!-- end .post-content -->
</article><!-- #post-<?php the_ID(); ?> -->