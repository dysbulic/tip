<?php
/**
 * The template for displaying the sidebars.
 *
 * @package WordPress
 * @subpackage Matala
 */
?>

	<div id="secondary" class="widget-area" role="complementary">
		<div id="secondary-content">
			<?php // The primary sidebar used in all layouts
			if ( ! dynamic_sidebar( 'primary-widget-area' ) ) : ?>

				<aside id="archives" class="widget">
					<h1 class="widget-title"><?php _e( 'Archives', 'matala' ); ?></h1>
					<ul>
						<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
					</ul>
				</aside>

				<aside id="meta" class="widget">
					<h1 class="widget-title"><?php _e( 'Meta', 'matala' ); ?></h1>
					<ul>
						<?php wp_register(); ?>
						<li><?php wp_loginout(); ?></li>
						<?php wp_meta(); ?>
					</ul>
				</aside>

			<?php endif; // end sidebar widget area ?>
			<div id="secondary-bottom"></div>
		</div><!-- #secondary-content -->
	</div><!-- #secondary .widget-area -->