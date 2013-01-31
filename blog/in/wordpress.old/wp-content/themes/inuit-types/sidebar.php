<?php
/**
 * @package Inuit Types
 */
?>
<div class="sidebar <?php if ( !get_option('inuitypes_right_sidebar') ) { echo 'sidebar_right'; } else { echo 'sidebar_left'; } ?>">

    	<?php  if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(1) ) : ?>

		<div class="widget">
		    <form method="get" id="searchform" action="<?php bloginfo('url'); ?>">
		        <div>
		        <input type="text" value="" name="s" id="s" />

			    <input type="submit" id="searchsubmit" value="Search" />
		        </div>
		    </form>
		</div>

		<div class="fix"></div>

		<div class="widget">
		        <h3 class="widget_title"><?php _e( 'Archives', 'it' ); ?></h3>
					<ul>
						<?php wp_get_archives('type=monthly'); ?>
					</ul>
		</div>

		<div class="fix"></div>

		<div class="widget">
		        <h3 class="widget_title"><?php _e( 'Categories', 'it' ); ?></h3>
		        	<ul>
						<?php wp_list_categories('show_count=1&title_li='); ?>
					</ul>

		</div>

		<div class="fix"></div>

		<?php endif; ?>

</div>