<?php
/**
 * The Supplementary widget areas.
 *
 * @package WordPress
 * @subpackage Matala
 */
?>

<?php
	/* The supplementary widget area is triggered if any of the areas
	 * have widgets. So let's check that first.
	 *
	 * If none of the sidebars have widgets, then let's bail early.
	 */
	if (   ! is_active_sidebar( 'first-supplementary-widget-area'  )
		&& ! is_active_sidebar( 'second-supplementary-widget-area' )
	)
		return;
	// If we get this far, we have widgets. Let do this.
?>
<div id="supplementary" <?php matala_supplementary_sidebar_class(); ?>>
	<?php if ( is_active_sidebar( 'first-supplementary-widget-area' ) ) : ?>
	<div id="first" class="widget-area" role="complementary">
		<?php dynamic_sidebar( 'first-supplementary-widget-area' ); ?>
	</div><!-- #first .widget-area -->
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'second-supplementary-widget-area' ) ) : ?>
	<div id="second" class="widget-area" role="complementary">
		<?php dynamic_sidebar( 'second-supplementary-widget-area' ); ?>
	</div><!-- #second .widget-area -->
	<?php endif; ?>
</div><!-- #supplementary -->