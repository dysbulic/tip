<?php
/**
 * The portion of the loop that shows the "image" post format.
 *
 * @package WordPress
 * @subpackage Mystique
 */
?>
<div <?php post_class( 'post-wrapper clear-block' ); ?>>

	<?php if ( ! is_single() && get_the_title() != '' ) : ?>
		<h2 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s', 'mystique' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h2>
	<?php else: ?>
		<h1 class="single-title"><?php the_title(); ?></h1>
	<?php endif; ?>

	<div class="entry">
		<?php the_content(); ?>
	</div><!-- .entry -->

	<?php if ( ! is_single() ) : ?>
	<div class="post-meta">
		<p>
			<?php edit_post_link( __( 'Edit', 'mystique' ), '<span class="edit-link">', '</span>' ); ?>
		</p>
	</div><!-- .meta -->
	<?php endif; ?>

</div><!-- .post-wrapper -->