<?php
/**
 * The Sidebar containing the main widget area.
 *
 * @package WordPress
 * @subpackage Bold_Life
 */
?>

<div id="sidebar">
	<ul>
		<?php if ( ! dynamic_sidebar( 'primary-sidebar' ) ) : ?>
		<li>
			<a href="<?php bloginfo( 'rss2_url' ); ?>" class="rss"><?php _e( 'Subscribe to RSS', 'bold-life' ); ?></a>
		</li>
		<li>
			<?php get_search_form(); ?>
		</li>
		<li>
			<h2><?php _e( 'Categories', 'bold-life' ); ?></h2>
			<ul>
				<?php wp_list_categories( 'title_li=0&categorize=0' ); ?>
			</ul>
		</li>
		<li>
			<h2><?php _e( 'Blogroll', 'bold-life' ); ?></h2>
			<ul>
				<?php wp_list_bookmarks( 'title_li=0&categorize=0' ); ?>
			</ul>
		</li>
		<li>
			<h2><?php _e( 'Meta', 'bold-life' ); ?></h2>
			<ul>
				<?php wp_register(); ?>
				<li>
					<?php wp_loginout(); ?>
				</li>
				<li>
					<a href="<?php bloginfo( 'rss2_url' ); ?>" title="<?php esc_attr_e( 'Syndicate this site using RSS', 'bold-life' ); ?>">
						<abbr title="<?php esc_attr_e( 'Really Simple Syndication', 'bold-life' ); ?>"><?php _e( 'RSS', 'bold-life' ); ?></abbr>
					</a>
				</li>
				<li>
					<a href="<?php bloginfo( 'comments_rss2_url' ); ?>" title="<?php esc_attr_e( 'The latest comments to all posts in RSS', 'bold-life' ); ?>">
						<?php _e( 'Comments', 'bold-life' ); ?> <abbr title="<?php esc_attr_e( 'Really Simple Syndication', 'bold-life' ); ?>"><?php _e( 'RSS', 'bold-life' ); ?></abbr>
					</a>
				</li>
			</ul>
		</li>
		<?php endif; ?>
	</ul>
</div><!-- #sidebar -->