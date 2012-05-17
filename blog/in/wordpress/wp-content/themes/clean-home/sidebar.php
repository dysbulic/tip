<?php
/**
 * @package WordPress
 * @subpackage Clean Home
 */
?>
	<div id="sidebar">
	<?php if ( !dynamic_sidebar( 'primary-widget-area' ) ) : ?>

		<div class="block">
			<h3><?php _e( 'Recent Posts', 'cleanhome' ); ?></h3>
				<?php query_posts( 'showposts=5' ); ?>
				<ul>
					<?php while ( have_posts() ) : the_post(); ?>
					<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
					<?php endwhile;?>
				</ul>
		</div>

		<div class="block">
			<h3><?php _e( 'Archives', 'cleanhome' ); ?></h3>
				<ul>
				<?php wp_get_archives( 'type=monthly' ); ?>
				</ul>
		</div>

		<div class="block">
			<h3><?php _e( 'Categories', 'cleanhome' ); ?></h3>
				<ul>
					<?php wp_list_categories( 'title_li=' ); ?>
				</ul>
		</div>

		<div class="block">
			<?php wp_list_bookmarks( 'title_before=<h3>&title_after=</h3>&category_before=&category_after=' ); ?>
		</div>

	<?php endif; ?>
	</div>