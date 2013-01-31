<?php
/**
 * @package WordPress
 * @subpackage Fjords
 */
?>
</div>
<div id="sidebar-1" class="sidebar">
<ul>
	 <?php if ( function_exists( 'dynamic_sidebar' ) && dynamic_sidebar( 1 ) ) : else : ?>

	<?php wp_list_pages( array( 'title_li' => '<h2>' . __( 'Pages', 'fjords' ) . '</h2>' ) ); ?>
	
	<li>
		<h2><?php _e( 'Archives', 'fjords' ); ?></h2>
		<ul>
		<?php wp_get_archives( 'type=monthly' ); ?>
		</ul>
	</li>
	
	<li>
		<h2><?php _e( 'Categories', 'fjords' ); ?></h2>
		<ul>
		<?php wp_list_cats(); ?> 
		</ul>
	</li>

	<?php endif; ?>

</ul>
</div>
<div id="sidebar-2" class="sidebar">
<ul>
 <?php if ( function_exists( 'dynamic_sidebar' ) && dynamic_sidebar( 2 ) ) : else : ?>
	<li>
	<h2><?php _e( 'About', 'fjords' ); ?></h2>
	<p><?php bloginfo( 'description', 'fjords' ); ?></p>
	</li>

      <li>
		<h2><?php _e( 'Links', 'fjords' ); ?></h2>
<?php get_links( '-1', '<li>', '</li>', '', 0, 'name', 0, 0, -1, 0 ); ?>
</li>

      <li class="widget widget_search">
		<h2><?php _e( 'Search', 'fjords' ); ?></h2>
		<?php get_search_form(); ?>
	</li>
	<?php endif; ?>
</ul>
</div>

<div id="sidebar-3" class="sidebar">
<ul>
 <?php if ( function_exists( 'dynamic_sidebar' ) && dynamic_sidebar( 3 ) ) : else : ?>
	<li>
	<h2><?php _e( 'Meta', 'fjords' ); ?></h2>
		<ul>
			<li><?php wp_loginout(); ?></li>
			<li><a href="<?php bloginfo( 'rss2_url' ); ?>" title="<?php esc_attr_e( 'Syndicate this site using RSS 2.0', 'fjords' ); ?>"><?php _e( 'Entries <abbr title="Really Simple Syndication">RSS</abbr>', 'fjords' ); ?></a></li>
			<li><a href="<?php bloginfo( 'comments_rss2_url' ); ?>" title="<?php esc_attr_e( 'The latest comments to all posts in RSS', 'fjords' ); ?>"><?php _e( 'Comments <abbr title="Really Simple Syndication">RSS</abbr>', 'fjords' ); ?></a></li>
			<?php wp_meta(); ?>
		</ul>
	</li>
	<?php endif; ?>
	<li>
	<h2><?php _e( 'Credits', 'fjords' ); ?></h2>	
	<p><a href="http://wordpress.com/" rel="generator">Get a free blog at WordPress.com</a>
	<?php printf( __( 'Theme: %1$s by %2$s.', 'fjords' ), '<a href="http://theme.wordpress.com/themes/fjords/">Fjords04</a>', 'Peterandrej' ); ?></p>
	</li>
</ul>
</div>