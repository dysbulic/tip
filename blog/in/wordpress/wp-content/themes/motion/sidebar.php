<?php
/**
 * @package Motion
 */
?>

<div id="sidebar">
	<ul>
	<?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'sidebar' ) ) : ?>

		<li class="boxed">
			<h3><?php _e( 'Recent entries' ); ?></h3>
			<ul>
				<?php wp_get_archives( 'type=postbypost&limit=10' ); ?>
			</ul>
		</li>

		<li class="boxed" id="tagbox">
			<h3><?php _e( 'Browse popular tags' ); ?></h3>
			<?php wp_tag_cloud( 'smallest=8&largest=15&number=30' ); ?>
		</li>

		<li class="boxed">
			<h3><?php _e( 'Meta' ); ?></h3>
			<ul>
				<?php wp_register(); ?>
				<li><?php wp_loginout(); ?></li>
				<li><a href="<?php bloginfo( 'rss2_url' ); ?>"><?php _e( 'Entries RSS' ); ?></a></li>
				<li><a href="<?php bloginfo( 'comments_rss2_url' ); ?>"><?php _e( 'Comments RSS' ); ?></a></li>
				<?php wp_meta(); ?>
			</ul>
		</li>

	<?php endif; ?>
	</ul>
</div><!-- /sidebar -->