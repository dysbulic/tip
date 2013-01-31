<?php
/**
 * @package WordPress
 * @subpackage Koi
 */
?>	<div id="sidebar">

	<?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar() ) : ?>

		<div class="widget">
			<h4><?php _e( 'Pages', 'ndesignthemes' ); ?></h4>
			<ul>
			<?php wp_list_pages( 'title_li=' ); ?>
			</ul>
		</div>

		<div class="widget">
			<h4><?php _e( 'Categories', 'ndesignthemes' ); ?></h4>
			<ul>
			<?php wp_list_categories( 'show_count=1&title_li=' ); ?>
			</ul>
		</div>

		<div class="widget">
			<h4><?php _e( 'Archives', 'ndesignthemes' ); ?></h4>
			<ul>
			<?php wp_get_archives( 'type=monthly' ); ?>
			</ul>
		</div>

	<?php endif; ?>

	</div>
	<!--/sidebar -->