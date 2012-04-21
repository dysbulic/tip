<?php
/**
 * The Sidebar containing the sidebar widget area.
 *
 * @package WordPress
 * @subpackage Chateau
 */

$options = chateau_get_theme_options();
$current_layout = $options['theme_layout'];

if ( 'content' != $current_layout ) :
?>

	<div id="secondary" class="widget-area" role="complementary">

		<?php if ( ! dynamic_sidebar( 'sidebar' ) ) : ?>

			<aside id="subscribe-item" class="sidebar-widget">
				<h1><?php _e( '&clubs; Subscribe', 'chateau' ); ?></h1>
				<ul>
					<li><a href="<?php bloginfo( 'rss2_url' ); ?>" title="Subscribe to Entries (RSS)"><?php _e( 'Entries (RSS)', 'chateau' ); ?></a></li>
					<li><a href="<?php bloginfo( 'comments_rss2_url' ); ?>" title="Subscribe to Comments (RSS)"><?php _e( 'Comments (RSS)', 'chateau' ); ?></a></li>
				</ul>
			</aside>

			<aside class="sidebar-widget">
				<h1><?php _e( '&clubs; Archives', 'chateau' ); ?></h1>
				<ul>
					<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
				</ul>
			</aside>

			<aside class="sidebar-widget">
				<h1><?php _e( '&clubs; Categories', 'chateau' ); ?></h1>
				<ul>
					<?php wp_list_categories( 'title_li=' ); ?>
				</ul>
			</aside>
			
			<aside class="sidebar-widget">
				<h1><?php _e( '&clubs; Meta', 'chateau' ); ?></h1>
				<ul>
					<?php wp_register(); ?>
					<li><?php wp_loginout(); ?></li>
					<?php wp_meta(); ?>
				</ul>
			</aside>			

		<?php endif; ?>

	</div><!-- #secondary .widget-area -->
<?php endif; ?>