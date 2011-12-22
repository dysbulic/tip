<?php
/**
 * @package Titan
 */
?>
	<div id="sidebar">
		<ul>
		<?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'titan_sidebar' ) ) : ?>
			<li class="widget widget_recent_entries">
				<h2 class="widgettitle"><?php _e( 'Recent Posts', 'titan' ); ?></h2>
				<ul>
					<?php $side_posts = get_posts( 'numberposts=10' ); foreach( $side_posts as $post ) : ?>
					<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</li>
			<li class="widget widget_categories">
				<h2 class="widgettitle"><?php _e( 'Categories', 'titan' ); ?></h2>
				<ul>
					<?php wp_list_categories( 'sort_column=name&title_li=' ); ?>
				</ul>
			</li>
			<li class="widget widget_archive">
				<h2 class="widgettitle"><?php _e( 'Archives', 'titan' ); ?></h2>
				<ul>
					<?php wp_get_archives( 'type=monthly' ); ?>
				</ul>
			</li>
		<?php endif; ?>
		</ul>
	</div><!--end sidebar-->