<?php
/**
 * @package WordPress
 * @subpackage Neutra
 */
?>
<ul>
<?php if ( function_exists( 'dynamic_sidebar' ) && dynamic_sidebar( 'neutra_sidebar' ) ) : else : ?>

	<li>
		<h2><?php _e( 'Archives', 'neutra' ); ?></h2>
		<ul><?php wp_get_archives( 'type=monthly' ); ?></ul>
	</li>

	<?php wp_list_bookmarks(); ?>

	<li>
		<h2><?php _e( 'Meta', 'neutra' ); ?></h2>
		<ul>
			<?php wp_register(); ?>
			<li><?php wp_loginout(); ?></li>
			<li><a href="<?php echo bloginfo( 'rss2_url' ); ?>"><?php _e( 'RSS Entries', 'neutra' ); ?></a></li>
			<li><a href="<?php echo bloginfo( 'comments_rss2_url' ); ?>"><?php _e( 'Comments RSS', 'neutra' ); ?></a></li>
		</ul>
	</li>

	<?php wp_meta(); ?>

<?php endif; ?>
</ul>