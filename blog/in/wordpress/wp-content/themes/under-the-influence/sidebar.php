<?php
/**
 * @package WordPress
 * @subpackage Under_the_Influence
 */
?>
<div id="sidebar">
	<ul>
		<?php
			/* Widgetized sidebar, if you have the plugin installed. */
			if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( 'sidebar' ) ) :
		?>
		<li></li>
	</ul>
	<ul>
		<?php wp_list_categories( array( 'show_count' => 1, 'title_li' => '<h2>' . __( 'Categories', 'uti_theme' ) . '</h2>' ) ); ?>
	</ul>
	<ul>
		<?php
			/* If this is the frontpage */
			if ( is_home() || is_page() ) {
		?>
		<li>
			<h2><?php _e( 'Meta', 'uti_theme' ); ?></h2>
			<ul>
				<?php wp_register(); ?>
				<li><?php wp_loginout(); ?></li>
				<?php wp_meta(); ?>
			</ul>
		</li>
		<?php
			}
		?>
		<li>
			<?php get_search_form(); ?>
		</li>
		<?php endif; ?>
	</ul>
</div>