<!-- comments ................................. -->
<div id="comments">

<?php // Do not delete these lines
if ( !empty( $_SERVER['SCRIPT_FILENAME'] ) && 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) )
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) { 
	echo '<p class="nocomments">' . __( "This post is password protected. Enter the password to view comments.", 'blix' ) . '</p></div>';
	return;
}
global $commentcount;
$commentcount = 1;

function blix_callback( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
	global $comment_alt, $commentcount;
	if ( $comment_alt % 2 ) $commentalt = ' alt';
?>
<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
	<div id="div-comment-<?php comment_ID(); ?>">
	<div class="comment-author vcard">
		<p class="header<?php echo $commentalt; ?>"><strong><?php echo $commentcount ?>.</strong>

		<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>

		<span class="fn"><?php if ($comment->comment_type == "comment") comment_author_link();
			  else {
			  		strlen($comment->comment_author) > 25 ? $author = substr($comment->comment_author,0,25) . "&hellip" : $author=substr($comment->comment_author,0,25);
			  		echo get_comment_author_link();

			  }
		?></span> &nbsp;|&nbsp; <?php printf( __( '%1$s at %2$s', 'blix' ), get_comment_date(), get_comment_time() ); ?></p>
	</div>
	<?php if ($comment->comment_approved == '0') : ?><p><em><?php _e( 'Your comment is awaiting moderation.', 'blix' ); ?></em></p><?php endif; ?>
	<?php comment_text(); ?>
	<?php edit_comment_link( __( 'Edit Comment', 'blix' ), '<span class="editlink">', '</span>' ); ?>
	<span class="reply">
	<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
	</span>
	</div>
	
<?php 
	$commentcount++;
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
