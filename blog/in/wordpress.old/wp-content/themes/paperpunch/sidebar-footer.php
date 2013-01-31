<?php
/**
 * @package WordPress
 * @subpackage Paperpunch
 */
?>
<?php
	// The footer widget area is triggered if any of these areas have widgets. If none of the sidebars have widgets, the footer will be empty.
	if ( !is_active_sidebar( 'footer-1' ) && !is_active_sidebar( 'footer-2' ) && !is_active_sidebar( 'footer-3' ) && !is_active_sidebar( 'footer-4' ) )
		return;
?>
<div id="footer" class="clear">
	<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
	<ul class="widget-container first">
		<?php dynamic_sidebar( 'footer-1' ); ?>
	</ul>
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
	<ul class="widget-container">
		<?php dynamic_sidebar( 'footer-2' ); ?>
	</ul>
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
	<ul class="widget-container">
		<?php dynamic_sidebar( 'footer-3' ); ?>
	</ul>
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
	<ul class="widget-container">
		<?php dynamic_sidebar( 'footer-4' ); ?>
	</ul>
	<?php endif; ?>
</div><!--end footer-->