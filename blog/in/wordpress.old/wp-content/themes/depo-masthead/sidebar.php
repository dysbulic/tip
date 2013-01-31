<?php
/**
 * @package WordPress
 * @subpackage DePo Masthead
 */
?>
		</div>
	</div>
	<div id="sidebar">
		<div class="sleeve">
		<ul class="group">
			<li id="left_sidebar">
				<ul>
			<?php if ( ! dynamic_sidebar( 'Left' ) ) : ?>
				<?php depo_about_widget(); ?>
			<?php endif; ?>
				</ul>
			</li>
			<li id="middle_sidebar">
				<ul>
			<?php if ( ! dynamic_sidebar( 'Middle' ) ) : ?>
				<?php depo_archives_widget(); ?>	
			<?php endif; ?>
				</ul>
			</li>	
			<li id="right_sidebar">
				<ul>
			<?php if ( ! dynamic_sidebar( 'Right' ) ) : ?>
				<?php depo_search_widget(); ?>
				<?php depo_rss_widget(); ?>
			<?php endif; ?>
				<?php wp_meta(); ?>
				</ul>
			</li>
		</ul>
		<div class="closer"></div>
		</div>
	</div>
</div>