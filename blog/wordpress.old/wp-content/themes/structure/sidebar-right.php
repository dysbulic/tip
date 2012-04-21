<?php
/**
 * @package WordPress
 * @subpackage Structure
 */
?>
<!-- begin r_sidebar -->

<div id="sidebar_right">
 
	<ul>
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Right Sidebar') ) : ?>
			<li id="archives" class="widget">
				<h4><?php _e( 'Archives', 'structuretheme' ); ?></h4>
				<ul>
					<?php wp_get_archives( 'type=monthly' ); ?>
				</ul>
			</li>

			<li id="meta" class="widget">
				<h4><?php _e( 'Meta', 'structuretheme' ); ?></h4>
				<ul>
					<?php wp_register(); ?>
					<li><?php wp_loginout(); ?></li>
					<?php wp_meta(); ?>
				</ul>
			</li>
        <?php endif; ?>
    </ul>
    
</div>