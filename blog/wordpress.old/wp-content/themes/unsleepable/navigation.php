	<?php /*
		This navigation is used on most pages to move back and forth in your archives.
		It has been placed in its own file so it's easier to change across all of K2
	*/ ?>

	

	<?php if (is_single()) { ?>

	<div class="navigation">
		<div class="left"><?php previous_post('<span>&laquo;</span> %','','yes') ?></div>
		<div class="right"><?php next_post(' % <span>&raquo;</span>','','yes') ?></div>
		<div class="clear"></div>
	</div>

	<?php } else { ?>
		
	<div class="navigation">
		<div class="left"><?php next_posts_link( __( '<span>&laquo;</span> Older posts', 'unsleepable' ) ); ?></div>
		<div class="right"><?php previous_posts_link( __( 'Newer posts <span>&raquo;</span>', 'unsleepable' ) ); ?></div>
		<div class="clear"></div>
	</div>

	<?php } ?>
