<?php
/**
 * @package WordPress
 * @subpackage Bueno
 */
?>
<div id="sidebar" class="col-right">
	<?php do_action( 'before_sidebar' ); ?>
		
	<!-- Widgetized Sidebar -->	
	<?php if ( ! dynamic_sidebar(1) ) : // begin primary widget area ?>
	<?php
		the_widget('WP_Widget_Archives', '', array('name' => 'Sidebar','id' => 'sidebar-1','before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3>','after_title' => '</h3>'));
		the_widget('WP_Widget_Categories', '', array('name' => 'Sidebar','id' => 'sidebar-1','before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3>','after_title' => '</h3>'));
		the_widget('WP_Widget_Meta', '', array('name' => 'Sidebar','id' => 'sidebar-1','before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3>','after_title' => '</h3>'));
		
	?>
	<?php endif; ?>

</div><!-- /#sidebar -->