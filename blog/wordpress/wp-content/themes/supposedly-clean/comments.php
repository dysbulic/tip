<?php
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p><?php _e('Enter your password to view comments.', 'supposedly-clean'); ?></p>
<?php
	return;
}



function supposedly_clean_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<div id="div-comment-<?php comment_ID() ?>" class="comment-author vcard">
	<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
	<?php comment_text(); ?>
	<p><cite><?php comment_type(__('Comment', 'supposedly-clean'), __('Trackback', 'supposedly-clean'), __('Pingback', 'supposedly-clean')); ?> <?php _e('by', 'supposedly-clean'); ?> <span class="fn"><?php comment_author_link() ?></span> &#8212; <?php comment_date() ?> @ <a href="#comment-<?php comment_ID() ?>"><?php comment_time() ?></a></cite> <?php edit_comment_link(__("Edit This", 'supposedly-clean'), ' |'); ?></p>
	<div class="reply">
		<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	</div>
<?php
}

?>
<?php if ( comments_open() || have_comments() ) : ?>
	<h2 id="comments"><?php comments_number(__('Leave a Comment', 'supposedly-clean'), __('1 Comment', 'supposedly-clean'), __('% Comments', 'supposedly-clean')); ?> 
<?php endif; ?>
<?php if ( comments_open() ) : ?>
	<a href="#postcomment" title="<?php _e("Leave a comment", 'supposedly-clean'); ?>">&raquo;</a>
<?php endif; ?>
</h2>

<?php if ( have_comments() ) : ?>
<ol id="commentlist">
<?php wp_list_comments(array('callback'=>'supposedly_clean_comment', 'avatar_size'=>16)); ?>
</ol>

<div class="navigation">
	<div class="alignleft"><?php previous_comments_link() ?></div>
	<div class="alignright"><?php next_comments_link() ?></div>
</div>
<br />
<?php endif; ?>

<p>
</p>

<?php comment_form(); ?>
