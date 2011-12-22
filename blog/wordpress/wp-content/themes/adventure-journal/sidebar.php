<?php
/**
 * @package Adventure_Journal
 */

//Get the selected layout option
$options = adventurejournal_get_theme_options();
$current_layout = $options['theme_layout'];

if ( 'col-1' != $current_layout ) :
?>
		<div id="secondary" class="widget-area" role="complementary">
			<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>

				<div id="archives" class="widget">
					<h3 class="widget-title"><?php _e( 'Archives', 'adventurejournal' ); ?></h3>
					<ul>
						<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
					</ul>
				</div>

				<div id="meta" class="widget">
					<h3 class="widget-title"><?php _e( 'Meta', 'adventurejournal' ); ?></h3>
					<ul>
						<?php wp_register(); ?>
						<li><?php wp_loginout(); ?></li>
						<?php wp_meta(); ?>
					</ul>
				</div>

			<?php endif; // end sidebar widget area ?>
		</div><!-- #secondary .widget-area -->
		
		<?php if ( is_active_sidebar( 'sidebar-2' ) ) : ?>
		<div id="tertiary" class="widget-area" role="complementary">
			<?php dynamic_sidebar( 'sidebar-2' ); ?>
		</div><!-- #tertiary .widget-area -->
		<?php endif; ?>
		
<?php endif; ?>