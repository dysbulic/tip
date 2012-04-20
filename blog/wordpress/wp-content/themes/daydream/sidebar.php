	<ul id="sidebar">

		<div id="sidebar_wrapper">

		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>

			<li id="categories" class="widget widget_categories"><h2><?php _e('Categories', 'daydream'); ?></h2>
				<ul>
				<?php wp_list_cats('sort_column=name&optioncount=1&hierarchical=0'); ?>
				</ul>
			</li>

			<li id="archives" class="widget widget_archive"><h2><?php _e('Archives', 'daydream'); ?></h2>
				<ul>
				<?php wp_get_archives('type=monthly'); ?>
				</ul>
			</li>

			<li id="meta" class="widget widget_meta"><h2><?php _e('Meta', 'daydream'); ?></h2>
				<ul>
					<?php wp_register(); ?>
					<li><?php wp_loginout(); ?></li>
					<li><a href="http://validator.w3.org/check/referer" title="This page validates as XHTML 1.0 Transitional">Valid <abbr title="eXtensible HyperText Markup Language">XHTML</abbr></a></li>
					<li><a href="http://gmpg.org/xfn/"><abbr title="XHTML Friends Network">XFN</abbr></a></li>
					<li><a href="http://wordpress.org/" title="Powered by WordPress, state-of-the-art semantic personal publishing platform.">WordPress</a></li>
					<?php wp_meta(); ?>
				</ul>
			</li>

			<li class="widget widget_search">
				<?php include (TEMPLATEPATH . '/searchform.php'); ?>
			</li>

		<?php endif; ?>

		</div>

	</ul>