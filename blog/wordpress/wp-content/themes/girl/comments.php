<?php // Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.','girl'); ?></p>
<?php
	return;
}

function girl_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
<div <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
<div class="comment-author vcard">
<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
<div class="heading"><span class="fn"><?php comment_author_link() ?></span> <?php _e('says:','girl'); ?></div>
</div>
<div class="entry">
<?php if ($comment->comment_approved == '0') : ?>
<b><?php _e('Your comment is awaiting moderation.','girl'); ?></em></b><br />
<?php endif; ?>
<?php comment_text() ?>
<div class="reply">
	<?php comment_reply_link(array_merge( $args, array('add_below' => 'comment-footer', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
</div>
</div>
<div class="footer comment-meta commentmetadata" id="comment-footer-<?php comment_ID() ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>" title=""><?php comment_date(get_option('date_format')) ?> <?php _e('at','girl'); ?> <?php comment_time() ?></a> <?php edit_comment_link(__('e','girl'),'',''); ?></div>
<br />
<br />
<?php
}

if (have_comments()) : ?>

<div class="commentlist">
	<?php wp_list_comments(array('callback'=>'girl_comment', 'style'=>'div')); ?>
</div>
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
<?php endif; ?>

<div class="comments">
<?php if (comments_open()) : ?>

<?php comment_form(); ?>

<?php elseif ( have_comments() ) : ?>
	<p class="nocomments"><?php _e('Comments are closed.','girl'); ?></p>
<?php endif; // if you delete this the sky will fall on your head ?>

</div>
