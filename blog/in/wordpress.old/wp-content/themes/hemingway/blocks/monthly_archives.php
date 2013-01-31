<h2><?php _e('Monthly Archives', 'hemingway'); ?></h2>
<ul class="counts">
	<?php wp_get_archives('type=monthly&limit=12&show_post_count=1'); ?>
</ul>
