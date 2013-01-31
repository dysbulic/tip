<?php
/**
 * @package Greyzed
 */
?>
	<div id="sidebar" role="complementary">

		<!-- begin widgetized sidebar 1 -->
		<ul>
			<?php if ( !dynamic_sidebar('Sidebar 1') ) : ?>
		</ul>

		<ul>
			<li><h2><?php _e( 'Latest Posts', 'greyzed' ); ?></h2>
				<ul>
 					<?php
 					global $post;
 					$myposts = get_posts('numberposts=10');
 					foreach($myposts as $post) :
   					setup_postdata($post);?>

    				<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
 					<?php endforeach; ?>
 				</ul>

			</li>

			<li><h2><?php _e( 'Categories', 'greyzed' ); ?></h2>
				<ul>
				<?php wp_list_categories('show_count=1&title_li='); ?>
				</ul>
			</li>

		</ul>
		<ul>
			<?php endif; ?>
		</ul>
		<!-- end widgetized sidebar 1 -->

	</div>


