<?php // Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p class="nocomments"><?php _e( 'This post is password protected. Enter the password to view comments.', 'iceburgg' ); ?></p>
<?php
	return;
}
		

		/* Function for seperating comments from track- and pingbacks. */
	function comment_type_detection($commenttxt = 'Comment', $trackbacktxt = 'Trackback', $pingbacktxt = 'Pingback') {
		global $comment;
		if (preg_match('|trackback|', $comment->comment_type))
			return $trackbacktxt;
		elseif (preg_match('|pingback|', $comment->comment_type))
			return $pingbacktxt;
		else
			return $commenttxt;
	}

function iceburgg_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
 <li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
 <div id="div-comment-<?php comment_ID() ?>">
      <span class="comment-author vcard">
      <?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
      <span class="cauthor fn">
      <?php comment_author_link() ?>
      </span>
      </span>
      <?php if ($comment->comment_approved == '0') : ?>
      <em><?php _e( 'Your comment is awaiting moderation.', 'iceburgg' ); ?></em>
      <?php endif; ?>
      <br />
      <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>" class="permalink" title=""></a>
      <?php comment_text() ?>
      <div class="reply">
	<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
      </div>
 </div>
<?php
}
?>
<div id="commentarea">
  <?php if ($comments) : ?>
  <div id="precomments">
    <div class="comleft">
		<h3 id="comments">
			<?php
				printf( _n( 'One Response to &#8220;%2$s&#8221;', '%1$s Responses to &#8220;%2$s&#8221;', get_comments_number(), 'iceburgg' ),
				number_format_i18n( get_comments_number() ), get_the_title() );
			?>
		</h3>
    </div>
    <div class="comright">
      <?php post_comments_feed_link(); ?>
    </div>
    <div style="clear: both"></div>
  </div>
  <div style="clear: both"></div>
  <ol class="commentlist">
  
  <?php wp_list_comments( array( 'callback'=>'iceburgg_comment', 'avatar_size'=>48 ) ); ?>
  </ol>

  <div class="navigation">
  	<div class="alignleft"><?php previous_comments_link() ?></div>
  	<div class="alignright"><?php next_comments_link() ?></div>
  </div>
	
  <?php else : // this is displayed if there are no comments so far ?>
  <?php if (comments_open()) : ?>
  <!-- If comments are open, but there are no comments. -->
  <?php else : // comments are closed ?>
  <!-- If comments are closed. -->
  <?php endif; ?>
  <?php endif; ?>
  <?php if (comments_open()) : ?>
  <div style="clear: both"></div>

<?php comment_form(); ?>
  
  <?php endif; // if you delete this the sky will fall on your head ?>
</div>
