<div class="navigation">
<?php if ( $paged < 2 ) { // Do stuff specific to first page ?>
<?php next_posts_link( '<span class="previous">' . __( 'Previous Entries', 'vermilionchristmas' ) . '</span>' ); ?>
<?php } else { // Do stuff specific to non-first page ?>
<?php next_posts_link( '<span class="previous">' . __( 'Previous Entries', 'vermilionchristmas' ) . '</span>' ); ?> &nbsp;|&nbsp; <?php previous_posts_link( '<span class="next">' . __( 'Next Entries', 'vermilionchristmas' ) . '</span>' ); ?>
<?php } ?>
</div>