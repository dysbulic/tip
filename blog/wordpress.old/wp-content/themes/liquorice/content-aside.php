<?php
/**
 * The portion of the loop that shows the "aside" post format.
 *
 * @package WordPress
 * @subpackage Liquorice
 */
?>
<div <?php post_class( 'post-wrapper' ); ?>>

	<div class="date">
		<small><?php liquorice_posted_on(); ?></small>
	</div><!-- .date -->

	<div class="aside-wrap">
		<div class="entry">
			 <div class="avatar"><?php echo get_avatar( $post->post_author, $size = '50' ); ?></div>
			<?php the_content(); ?>
		</div><!-- .entry -->

		<div class="post-meta">
			<?php liquorice_posted_in(); ?>
			<span class="comments-num"><?php comments_popup_link( __( 'Leave a comment', 'liquorice' ), __( '1 Comment', 'liquorice' ), __( '% Comments', 'liquorice' ) ); ?></span>
		</div><!-- .meta -->

		<?php edit_post_link( __( '(Edit)', 'liquorice' ), '<span class="edit-link">', '</span>' ); ?>
	</div><!-- .aside-wrap-->
</div><!-- .post-wrapper -->