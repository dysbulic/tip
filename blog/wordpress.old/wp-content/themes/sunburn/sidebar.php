<ul id="sidebar">
<?php if ( !function_exists('dynamic_sidebar')
	|| !dynamic_sidebar() ) : ?>

		<?php wp_list_pages('title_li=<h2>Pages</h2>'); ?>

		<li><h2><?php _e( 'Archives', 'sunburn' ); ?></h2>
			<ul>
				<?php wp_get_archives('type=monthly'); ?>
			</ul>
		</li>

		<li><h2><?php _e( 'Categories', 'sunburn' ); ?></h2>
			<ul>
				<?php wp_list_cats('sort_column=name&optioncount=1&hierarchical=0'); ?>
			</ul>
		</li>

		<li><h2><?php _e( 'Search', 'sunburn' ); ?></h2>
			<?php get_search_form(); ?>
		</li>

		<li><h2><?php _e( 'Meta', 'sunburn' ); ?></h2>
			<ul>
				<?php wp_register(); ?>
				<li><?php wp_loginout(); ?></li>
				<li><a href="http://validator.w3.org/check/referer" title="<?php esc_attr_e( 'This page validates as XHTML 1.0 Transitional', 'sunburn' );  ?>"><?php printf( __( 'Valid %1$s', 'sunburn' ), '<abbr title="' . esc_attr( 'eXtensible HyperText Markup Language', 'sunburn' ) . '">XHTML</abbr>' ); ?></a></li>
				<li><a href="http://gmpg.org/xfn/"><abbr title="<?php esc_attr_e( 'XHTML Friends Network', 'sunburn' ); ?>">XFN</abbr></a></li>
				<li><a href="http://wordpress.org/" title="Powered by WordPress, state-of-the-art semantic personal publishing platform.">WordPress</a></li>
				<?php wp_meta(); ?>
			</ul>
		</li>

<?php endif; ?>
</ul>


