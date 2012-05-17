<?php
/**
 * @package WordPress
 * @subpackage Under_the_Influence
 */
 
	global $options;
	foreach ($options as $value) {
		if (array_key_exists('id',$value)) {
			if (get_option( $value['id'] ) === FALSE) {
				$$value['id'] = $value['std'];
			} else {
				$$value['id'] = get_option( $value['id'] );
			}
		}
	}
?>

<div id="footer">
	<div class="ornament"></div>
	<div class="cell cell-1">
		<?php
			/* Widgetized footer, if you have the plugin installed. */
			if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footercell-left') ) :
		?>
		<div class="widget widget_tag_cloud">
			<h2>Tags</h2>
			<div class="line"></div>
			<div>
				<?php wp_tag_cloud(); ?>
			</div>
		</div>
		<?php
			endif;
		?>
	</div>
	<div class="cell cell-2">
		<?php
			/* Widgetized footer, if you have the plugin installed. */
			if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footercell-mid-left') ) :
		?>
		<div class="widget">
			<h2>Calendar</h2>
			<div class="line"></div>
			<div>
				<?php get_calendar(); ?>
			</div>
		</div>
		<?php
			endif;
		?>
	</div>
	<div class="cell cell-3">
		<?php
			/* Widgetized footer, if you have the plugin installed. */
			if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footercell-mid-right') ) :
		?>
		<div class="widget">
			<h2>Archives</h2>
			<div class="line"></div>
			<ul>
				<?php wp_get_archives("monthly"); ?>
			</ul>
		</div>
		<?php
			endif;
		?>
	</div>
	<?php
		if ($uti_footer_cell4 == "on") {
	?>
	<div class="cell cell-4">
		<?php
			/* Widgetized footer, if you have the plugin installed. */
			if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footercell-right') ) :
		?>
		<div class="widget">
			<h2>Blogroll</h2>
			<div class="line"></div>
			<ul>
				<?php wp_list_bookmarks('title_li=&categorize=0'); ?>
			</ul>
		</div>
		<?php
			endif;
		?>
	</div>
	<?php
		}
	?>
	<div id="externalFooterLinks">
		<a href="http://wordpress.com/" rel="generator">Get a free blog at WordPress.com</a> | 
		<?php printf( __( 'Theme: %1$s by %2$s.', 'uti' ), 'Under the Influence', '<a href="http://www.spaceperson.net" rel="designer">spaceperson</a>' ); ?>
	</div>
</div><!--#footer-->
</div><!--#page-->
<?php wp_footer(); ?>
</body>
</html>