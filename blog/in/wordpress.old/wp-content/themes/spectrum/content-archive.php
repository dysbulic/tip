<?php
/**
 * @package Spectrum
 */
?>
<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<div class="entry">
		<h3 class="result"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s', 'spectrum' ), the_title_attribute( 'echo=0' ) ); ?>"><?php the_title(); ?></a></h3>
		<?php if ( ! is_search() ) : ?>
			<?php the_excerpt(); ?>
		<?php endif; ?>
	</div>
	<div class="post-meta post-category">
		<p class="post-category-title"><strong><?php _e( 'Category:', 'spectrum' ); ?></strong></p>
		<p class="post-category-elements"><?php the_category( ', ' ); ?></p>
	</div>
	<?php the_tags( '<div class="post-meta post-tags"><p><strong>' . __( 'Tagged with:', 'spectrum' ) . '</strong></p><ul><li>','</li><li>','</li></ul></div>'); ?>
</div>