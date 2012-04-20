<?php // Do not delete these lines

if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p class="nocomments"><?php _e("This post is password protected. Enter the password to view comments."); ?></p>
<?php
	return;
}

function ocadia_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<div id="div-comment-<?php comment_ID() ?>">
	<div class="comment-author vcard">
	<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
	<p class="commentauthor"><span class="fn"><?php comment_author_link() ?></span> <?php _e('said'); ?>,</p>
	</div>
	<p class="comment-meta commentmetadata commentmeta"><?php comment_date() ?> <?php _e('at'); ?> <a href="#comment-<?php comment_ID() ?>" title="<?php _e('Permanent link to this comment'); ?>"><?php comment_time() ?></a> <?php edit_comment_link(__("Edit"), ' &#183; ', ''); ?></p>
			
	<?php comment_text() ?>

	<div class="reply">
		<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	</div>
<?php
}

if (have_comments()) : ?>

	<h2 id="comments">
		<?php comments_number(__('Comments'), __('1 Comment'), __('% Comments')); ?>
		<?php if ( comments_open() ) : ?>
			<a href="#postcomment" title="<?php _e('Jump to the comments form'); ?>">&raquo;</a>
		<?php endif; ?>
	</h2> 

	<ol id="commentlist">
	<?php wp_list_comments(array('callback'=>'ocadia_comment','avatar_size'=>48)); ?>
	</ol>
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
	<br />
	<?php if (!comments_open()) : ?> 
		<p><?php _e('Comments are closed.'); ?></p>
	<?php endif; ?>
<?php endif; ?>

<?php if (comments_open()) : ?>

<?php comment_form(); ?>

<?php endif; // if you delete this the sky will fall on your head ?>
