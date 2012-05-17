<?php
/**
 * @package WordPress
 * @subpackage Retro MacOS
 */
?>

<div id="sidebar">
	<ul>
		<li class="home_icon"><a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'description', 'display' ) ); ?>"><?php bloginfo( 'name' ); ?></a></li>
		<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
		<li>
			<ul>
			<?php wp_get_archives( 'type=monthly&format=custom&before=<li class="archive_item">&after=</li>' ); ?>
			</ul>
		</li>
		<li>
			<ul class="categories">
				<?php wp_list_categories( 'show_count=0&title_li=' ); ?>
			</ul>
		</li>
	</ul>
</div>