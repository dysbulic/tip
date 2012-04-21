<?php
/**
 * The template for displaying posts in the Aside Post Format
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
				<h2 class="entry-format"><?php _e( 'Featured', 'chateau' ); ?></h2>
			</hgroup>
		<?php else : ?>
			<h1><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'chateau' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
		<p class="post-date">
			<strong><?php the_time( 'd' ); ?></strong>
			<em><?php the_time( 'l' ); ?></em>
			<span><?php the_time( 'M Y' ); ?></span>
		</p>
		<?php endif; ?>
	</header><!-- end .post-title -->
	<div class="post-content clear-fix">

		<?php chateau_post_extra(); ?>

		<div class="post-entry">
			<?php the_content( __( 'Continue reading &raquo;', 'chateau' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'chateau' ) . '</span>', 'after' => '</div>' ) ); ?>
		</div><!-- end .post-entry -->
		
		<div class="post-info clear-fix">
			<p><?php _e( 'Posted by','chateau' );?> <?php the_author_posts_link(); ?> | <?php printf( __( 'Filed under %s' ), get_the_category_list( ', ' ) ); ?></p>
			<p class="post-com-count">
				<strong>&asymp; <?php comments_popup_link( __( 'Leave a Comment', 'chateau' ), __( '1 Comment', 'chateau' ), __( '% Comments', 'chateau' ) ); ?></strong>
			</p>
		</div><!-- end .post-info -->

	</div><!-- end .post-content -->
	
</article><!-- #post-<?php the_ID(); ?> -->