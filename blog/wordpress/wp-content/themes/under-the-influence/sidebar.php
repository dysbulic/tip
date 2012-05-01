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
			if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar') ) :
		?>
		<li></li>
	</ul>
	<ul>
		<?php wp_list_categories('show_count=1&title_li=<h2>Categories</h2>'); ?>
	</ul>
	<ul>
		<?php
			/* If this is the frontpage */
			if ( is_home() || is_page() ) {
		?>
		<li>
			<h2><?php _e('Meta', 'uti_theme')?></h2>
			<ul>
				<?php wp_register(); ?>
				<li><?php wp_loginout(); ?></li>
				<li>
					<a href="http://validator.w3.org/check/referer" title="This page validates as XHTML 1.0 Transitional">
						<?php _e('Valid', 'uti_theme')?><abbr title="eXtensible HyperText Markup Language"> XHTML</abbr>
					</a>
				</li>
				<li>
					<a href="http://gmpg.org/xfn/">
						<abbr title="XHTML Friends Network">XFN</abbr>
					</a>
				</li>
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