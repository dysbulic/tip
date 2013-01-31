<?php
/**
 * The portion of the loop that shows the "aside" post format.
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

	<div class="aside-wrap">
		<div class="entry">
			<?php the_content(); ?>
		</div><!-- .entry -->

		<?php if ( ! is_single() ) : ?>
		<div class="post-meta">
			<p class="alignright">
				<?php
				printf( __( '<span class="sep">Posted on </span><a href="%1$s" rel="bookmark"><time class="entry-date" datetime="%2$s" pubdate>%3$s</time></a>', 'mystique' ),
					get_permalink(),
					get_the_date( 'c' ),
					get_the_date()
				);
			?>
				<?php edit_post_link( __( 'Edit', 'mystique' ), '<span class="sep">|</span> <span class="edit-link">', '</span>' ); ?>
			</p>
		</div><!-- .meta -->
		<?php endif; ?>
	</div><!-- .aside-wrap-->

</div><!-- .post-wrapper -->