<?php
/**
 * @package WordPress
 * @subpackage Dark Wood
 */
?>
<div class="sidebar">
	<ul>
		<?php if ( function_exists( 'dynamic_sidebar' ) && dynamic_sidebar( 1 ) ) : else : ?>
		<li class="widget widget_recent_entries">
			<h2><?php _e( 'Recent Posts' ); ?></h2>
			<ul>
				<?php wp_get_archives( 'postbypost', 10 ); ?>
			</ul>
		</li>
		<?php endif; ?>
	</ul>
</div>

<div class="sidebar">
	<ul>
		<?php if ( function_exists( 'dynamic_sidebar' ) && dynamic_sidebar( 2 ) ) : else : ?>
		<li id="search" class="widget widget_search">
			<?php get_search_form(); ?>
		</li>
		<li class="widget widget_pages">
			<h2><?php _e( 'Pages' ); ?></h2>
			<ul>
				<?php wp_list_pages( 'title_li=' ); ?>
			</ul>
		</li>
		<?php endif; ?>
	</ul>
</div>