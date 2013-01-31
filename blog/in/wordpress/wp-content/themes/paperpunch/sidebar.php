<?php
/**
 * @package WordPress
 * @subpackage Paperpunch
 */
?>
<div id="sidebar">
	<ul>
		<?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'sidebar' ) ) : ?>
			<li class="widget widget_recent_entries">
				<h2 class="widgettitle"><?php _e( 'Recent Articles', 'paperpunch' ); ?></h2>
				<ul>
					<?php $side_posts = get_posts( 'numberposts=10' ); foreach($side_posts as $post) : ?>
					<li><a href= "<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</li>
		<?php endif; ?>
	</ul>
</div><!--end sidebar-->