<?php
/**
 * @package WordPress
 * @subpackage Comet
 */
?>

<ul class="widgets">

<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) { ?>

	<li class="widget widget_search">
		<?php get_search_form(); ?>
	</li>

	<li class="widget widget_categories">
		<h2><?php _e( 'Categories', 'comet' ); ?></h2>
		<ul>
			<?php wp_list_categories( array(
				'sort_column' => 'name',
				'title_li'    => '',
			) ); ?>
		</ul>
	</li>

	<li class="widget widget_archive">
		<h2><?php _e( 'Archives', 'comet' ); ?></h2>
		<ul>
			<?php wp_get_archives( array(
				'type' => 'monthly'
			) ); ?>
		</ul>
	</li>

	<li class="widget widget_meta">
		<h2><?php _e( 'Meta', 'comet' ); ?></h2>
		<ul>
			<li><?php wp_loginout(); ?></li>
			<?php wp_meta(); ?>
		</ul>
	</li>

<?php } ?>

</ul><!-- /widgets -->
