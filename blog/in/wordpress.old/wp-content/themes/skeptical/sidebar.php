<?php
/**
 * @package WordPress
 * @subpackage Skeptical
 */
?>
<div id="sidebar" class="col-right">
	<?php if ( !dynamic_sidebar( 'sidebar-1' ) ) : ?>
	<div class="primary">
		<div class="widget">
			<h3><?php _e( 'Archives', 'woothemes' ); ?></h3>
			<ul>
				<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
			</ul>
		</div><!-- .widget -->

		<div class="widget">
			<h3><?php _e( 'Categories', 'woothemes' ); ?></h3>
			<ul>
				<?php wp_list_categories( 'title_li=' ); ?>
			</ul>
		</div><!-- .widget -->

		<div class="widget">
			<h3><?php _e( 'Meta', 'woothemes' ); ?></h3>
			<ul>
				<?php wp_register(); ?>
				<li><?php wp_loginout(); ?></li>
				<?php wp_meta(); ?>
			</ul>
		</div><!-- .widget -->
	</div>
	<?php endif; ?>
</div><!-- /#sidebar -->