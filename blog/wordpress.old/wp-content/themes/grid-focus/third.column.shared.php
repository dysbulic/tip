<?php
/**
 *	@package WordPress
 *	@subpackage Grid_Focus
 */
?>
<div class="secondaryColumn">
	<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Secondary - Shared') ) : else : ?>
		
		<div class="widgetContainer widget_pages">
			<h3><?php _e( 'Meta', 'grid-focus' ); ?></h3>
				<ul>
					<?php wp_register(); ?>
					<li><?php wp_loginout(); ?></li>
					<li><a href="http://validator.w3.org/check/referer" title="<?php esc_attr_e( 'This page validates as XHTML 1.0 Transitional', 'grid-focus' ); ?>"><?php _e( 'Valid <abbr title="eXtensible HyperText Markup Language">XHTML</abbr>', 'default' ); ?></a></li>
					<li><a href="http://gmpg.org/xfn/"><abbr title="<?php esc_attr_e( 'XHTML Friends Network', 'grid-focus' ); ?>"><?php _e( 'XFN', 'grid-focus' ); ?></abbr></a></li>
					<li><a href="http://wordpress.com/" title="<?php esc_attr_e( 'Powered by WordPress, state-of-the-art semantic personal publishing platform.', 'grid-focus' ); ?>">WordPress</a></li>
					<?php wp_meta(); ?>
				</ul>
		</div>
		<div class="widgetContainer widget_pages">
			<?php wp_list_bookmarks('title_li=&category_before=&category_after=&title_before=<h3>&title_after=</h3>'); ?>
		</div>
	<?php endif; ?>
</div>
