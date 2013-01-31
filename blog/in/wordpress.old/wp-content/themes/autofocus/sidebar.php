<?php
/**
 * @package WordPress
 * @subpackage AutoFocus
 */

if ( ! is_active_sidebar( 'primary-sidebar' ) )
	return;

// If we get this far, we have widgets. ?>
<div id="sidebar" class="widget-area">
	<ul>
		<?php if ( ! dynamic_sidebar( 'primary-sidebar' ) ) : ?>

				<?php
				/* We don't need fallback HTML if no widgets are active because
				 * if no widgets are loaded into the Primary Sidebar widget
				 * area then the file will not even get this far (see the first
				 * is_active_sidebar() conditional).
				 */
				?>

		<?php endif;  ?>
	</ul>
</div><!-- #sidebar -->