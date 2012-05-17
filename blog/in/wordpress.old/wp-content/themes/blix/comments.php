<!-- comments ................................. -->
<div id="comments">

<?php // Do not delete these lines
if ( !empty( $_SERVER['SCRIPT_FILENAME'] ) && 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) )
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) { 
	echo '<p class="nocomments">' . __( "This post is password protected. Enter the password to view comments.", 'blix' ) . '</p></div>';
	return;
}

if (have_comments()) : ?>

	<h2><?php comments_number( __( 'Leave a Comment', 'blix' ), __( '1 Comment', 'blix' ), __( '% Comments', 'blix' ) ); if(comments_open()) { ?> <a href="#commentform" class="more"><?php _e( 'Add your own', 'blix' ); ?></a><?php } ?></h2>

	<ul class="commentlist">
		<?php wp_list_comments(array('callback'=>'blix_callback', 'avatar_size'=>23)); ?>
	</ul>
	<div class="navigation">
	<div class="alignleft"><?php previous_comments_link(); ?></div>
	<div class="alignright"><?php next_comments_link(); ?></div>
	</div>

<?php endif; ?>

<?php comment_form(); ?>

<?php if ( comments_open() && pings_open() ) { ?>
	<p><a href="<?php trackback_url(display); ?>"><?php _e( 'Trackback this post', 'blix' ); ?></a> &nbsp;|&nbsp; <?php post_comments_feed_link( __( 'Subscribe to the comments via RSS Feed', 'blix' ) ); ?></p>
<?php } elseif ( comments_open() ) {?>
	<p><?php post_comments_feed_link( __( 'Subscribe to the comments via RSS Feed', 'blix' ) ); ?></p>
<?php } elseif ( pings_open() ) { ?>
	<p><a href="<?php trackback_url(display); ?>"><?php _e( 'Trackback this post', 'blix' ); ?></a></p>
<?php } ?>

</div> <!-- /comments -->
