<?php
/**
 * @package WordPress
 * @subpackage Blogum
 */
?>
<div class="sidebar" role="complementary">
	<?php if ( !dynamic_sidebar( 'sidebar-1' ) ) : ?>
		<aside class="widget">
			<div class="widget-body">
				<h1><?php _e( 'Archives', 'blogum' ); ?></h1>
				<ul>
					<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
				</ul>
			</div>
		</aside><!-- .widget -->

		<aside class="widget">
			<div class="widget-body">
				<h1><?php _e( 'Categories', 'blogum' ); ?></h1>
				<ul>
					<?php wp_list_categories( 'title_li=' ); ?>
				</ul>
			</div>
		</aside><!-- .widget -->

		<aside class="widget">
			<div class="widget-body">
				<h1><?php _e( 'Meta', 'blogum' ); ?></h1>
				<ul>
					<?php wp_register(); ?>
					<li><?php wp_loginout(); ?></li>
					<?php wp_meta(); ?>
				</ul>
			</div>
		</aside><!-- .widget -->
	<?php endif; ?>
</div><!-- .sidebar -->