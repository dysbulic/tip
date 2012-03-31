<?php
/**
 * The portion of the loop that shows the "status" post format.
 *
 * @package WordPress
 * @subpackage Matala
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-wrapper' ); ?>>

	<header class="entry-header">
		<?php if ( ! is_single() && get_the_title() != '' ) : ?>
			<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'matala' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
		<?php else: ?>
			<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php endif; ?>

		<div class="entry-info">
			<?php matala_posted_on(); ?>
		</div><!-- .entry-info -->

	</header><!-- .entry-header -->
	<div class="post-format-icon"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'matala' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></div>

	<div class="status-avatar"><?php echo get_avatar( $post->post_author, $size = '65' ); ?></div>

	<div class="entry-content">
		<?php the_content( __( 'Read the rest of this entry <span class="meta-nav">&rarr;</span>', 'matala' ) ); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'matala' ) . '</span>', 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">

		<div class="entry-meta">
			<?php matala_posted_in(); ?>
			<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'matala' ), __( '1 Comment', 'matala' ), __( '% Comments', 'matala' ) ); ?></span>
			<?php edit_post_link( __( 'Edit', 'matala' ), '<span class="sep">|</span> <span class="edit-link">', '</span>' ); ?>
		</div><!-- .entry-meta -->

	</footer><!-- .entry-footer -->

</article><!-- #post-<?php the_ID(); ?> .post-wrapper -->