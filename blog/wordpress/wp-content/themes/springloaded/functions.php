<?php
function widget_springloaded_search() {
?>
<div class="side-widget">
  <h3><?php _e('Search');?></h3>  
	<form method="get" action="<?php bloginfo('url'); ?>/">
<p><input type="text" name="s" onblur="this.value=(this.value=='') ? '<?php _e('Search this Blog', 'springloaded'); ?>' : this.value;" onfocus="this.value=(this.value=='<?php _e('Search this Blog', 'springloaded'); ?>') ? '' : this.value;" value="<?php _e('Search this Blog', 'springloaded'); ?>" id="s" /> <input type="submit" name="submit" value="Search" id="some_name"></p>
	</form>
</div>
<?php
}

unregister_widget('WP_Widget_Search');
wp_register_sidebar_widget('search', __('Search'), 'widget_springloaded_search');

register_sidebar( array(
	'before_widget' => '<div class="side-widget %2$s"><div class="widget-wrap">',
	'after_widget' => '</div></div>',
	'before_title' => '<h3>',
	'after_title' => '</h3>',
) );

$themecolors = array(
	'bg' => 'ffffff',
	'border' => 'dfdfdf',
	'text' => '000000',
	'link' => '9c4617',
	'url' => '9c4617'
);
$content_width = 570; // pixels

add_theme_support( 'automatic-feed-links' );

register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'springloaded' ),
) );

function springloaded_page_menu() { // fallback for primary navigation ?>
<ul id="navigation">
	<li class="page_item <?php if( is_front_page() ) { echo 'current_page_item'; } ?>"><a href="<?php bloginfo( 'url' ); ?>"><?php _e( 'Home', 'springloaded' ); ?></a></li>
	<?php wp_list_pages( 'title_li=&depth=1' ); ?>
</ul>
<?php }