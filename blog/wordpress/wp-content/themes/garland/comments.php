<?php // Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', 'garland'); ?></p>
<?php
	return;
}

function garland_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent'); ?>id="comment-<?php comment_ID() ?>">
	<div id="div-comment-<?php comment_ID() ?>">
	<span class="comment-author vcard">
	<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
	<?php printf(__('<cite class="fn">%s</cite> Says:', 'garland'), get_comment_author_link()); ?>
	</span>
	<?php if ($comment->comment_approved == '0') : ?>
	<em><?php _e('Your comment is awaiting moderation.', 'garland'); ?></em>
	<?php endif; ?>
	<br />

	<small class="comment-meta commentmetadata"><a href="#comment-<?php comment_ID() ?>" title=""><?php printf(__('%1$s at %2$s', 'garland'), get_comment_date(), get_comment_time()); ?></a> <?php edit_comment_link(__('edit', 'garland'),'&nbsp;&nbsp;',''); ?></small>

	<?php comment_text(); ?>
	<div class="reply">
		<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	</div>
<?php
}

if (have_comments()) : ?>
	<h3 id="comments"><?php comments_number(__('No Responses Yet', 'garland'), __('One Response', 'garland'), __('% Responses', 'garland'));?> <?php printf(__('to &#8220;%s&#8221;', 'garland'), the_title('', '', false)); ?></h3>

	<ol class="commentlist">
	<?php wp_list_comments(array('callback'=>'garland_comment')); ?>
	</ol>
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
	<br />
<?php endif; ?>

<?php if (comments_open()) : ?>

<?php comment_form(); ?>

<?php elseif ( have_comments() ) : ?>
	<p class="nocomments"><?php _e('Comments are closed.', 'garland'); ?></p>
<?php endif; // if you delete this the sky will fall on your head ?>
