<?php
/**
 * @package Imbalance 2
 */
?>
<div class="post_title">
	<?php if ( is_front_page() ) : ?>
		<h2 class="entry-title"><?php the_title(); ?></h2>
	<?php else : ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>
	<?php endif; ?>

	<div class="entry-meta">
		<?php if ( comments_open() || ( '0' != get_comments_number() && ! comments_open() ) ) : ?>
			<?php echo comments_popup_link( __( 'Leave a comment', 'imbalance2' ), __( 'One Comment', 'imbalance2' ), __( '% Comments', 'imbalance2' ) ); ?>
		<?php endif; ?>

		<?php edit_post_link( __( 'Edit', 'imbalance2' ), '<span class="main-separator"> / </span><span class="edit-link">', '</span>' ); ?>
	</div><!-- .entry-meta -->
</div>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
		<?php the_content(); ?>
		<div class="entry-utility">
			<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( '<span>Pages:</span>', 'imbalance2' ), 'after' => '</div>' ) ); ?>
		</div><!-- .entry-utility -->
	</div><!-- .entry-content -->
</div><!-- #post-<?php the_ID(); ?> -->