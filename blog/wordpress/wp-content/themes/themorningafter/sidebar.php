<?php
/**
 * The template for displaying the sidebar.
 *
 * @package WordPress
 * @subpackage The Morning After
 */
?>
<div class="column span-3 last">
	
	<?php if ( !dynamic_sidebar( 'primary-sidebar' ) ) : ?>
		
		<div class="widget widget_categories">
			<h3 class="mast"><?php _e( 'Categories', 'woothemes' ); ?></h3>
			<ul>
				<?php wp_list_categories( 'title_li=' ); ?>
			</ul>
		</div>
		
		<div class="widget widget_archive">
			<h3 class="mast"><?php _e( 'Archives', 'woothemes' ); ?></h3>
			<ul>
				<?php wp_get_archives( 'type=monthly' ); ?>
			</ul>
		</div>
		
		<div class="widget widget_links">
			<h3 class="mast"><?php _e( 'Blogroll', 'woothemes' ); ?></h3>
			<ul class="xoxox">
				<?php wp_list_bookmarks( array( 'title_li' => '', 'categorize' => 0 ) ); ?>
			</ul>
		</div>
		
		<div class="widget widget_meta">
			<h3 class="mast"><?php _e( 'Meta', 'woothemes' ); ?></h3>
			<ul>
				<?php wp_register(); ?>
				<li><?php wp_loginout(); ?></li>
				<?php wp_meta(); ?>
			</ul>
		</div>
	
	<?php endif; ?>

</div>