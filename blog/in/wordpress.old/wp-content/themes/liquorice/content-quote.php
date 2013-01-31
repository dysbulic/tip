<?php
/**
 * The portion of the loop that shows the "quote" post format.
 *
 * @package WordPress
 * @subpackage Liquorice
 */
?>
<div <?php post_class( 'post-wrapper' ); ?>>

	<?php if ( ! is_single() && get_the_title() != '' ) : ?>
		<h2 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s', 'liquorice' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h2>
	<?php else: ?>
		<h1 class="single-title"><?php the_title(); ?></h1>
	<?php endif; ?>

	<div class="date">
		<small><?php liquorice_posted_on(); ?></small>
	</div><!-- .date -->

	<div class="quote-wrap">
		<div class="entry">
			<?php the_content(); ?>
		</div><!-- .entry -->

		<div class="post-meta">
			<?php liquorice_posted_in(); ?>
			<span class="comments-num"><?php comments_popup_link( __( 'Leave a comment', 'liquorice' ), __( '1 Comment', 'liquorice' ), __( '% Comments', 'liquorice' ) ); ?></span>
		</div><!-- .meta -->

		<?php edit_post_link( __( '(Edit)', 'liquorice' ), '<span class="edit-link">', '</span>' ); ?>
	</div><!-- .quote-wrap-->

</div><!-- .post-wrapper -->