<?php // Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p class="nocomments"><?php _e("This post is password protected. Enter the password to view comments.", 'daydream'); ?></p>
<?php
	return;
}


function daydream_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<div id="div-comment-<?php comment_ID() ?>">
	<?php if ($comment->comment_approved == '0') : ?>
	<p class="await_mod"><?php _e('Your comment is awaiting moderation.','daydream'); ?></p>
	<?php endif; ?>

	<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
	
	<?php comment_text(); ?>
	
	<div class="comment-author vcard cmntmeta comment-meta commentmetadata"><span class="fn"><?php comment_author_link() ?></span> - <?php comment_date() ?> <?php _e('at','daydream'); ?> <?php comment_time() ?></a> <?php edit_comment_link('e','',''); ?></div>
	
	<div class="reply">
		<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	</div>
<?php
}

if (have_comments()) : ?>

	<h4 id="comments"><?php comments_number(__('No Responses Yet','daydream'), __('One Response','daydream'), __('% Responses','daydream') );?></h4>

	<ol class="commentlist">
	<?php wp_list_comments(array(
		'callback'=>'daydream_comment',
		'avatar_size'=>48,
	)); ?>
	</ol>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
	
	<?php if ('closed' == $post->comment_status) : ?> 
		<h4>Comments are closed.</h4>
	<?php endif; ?>

	<?php else : // this is displayed if there are no comments so far ?>
		<?php if ('open' == $post->comment_status) : ?>
			<?php if ( is_page() ) : ?>
			<h4><?php _e('There are no comments on this page.', 'daydream'); ?></h4>
			<?php else : ?>
			<h4><?php _e('There are no comments on this post.', 'daydream'); ?></h4>
			<?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ( comments_open() ) : ?>
		<?php comment_form(); ?>
	<?php endif; // if you delete this the sky will fall on your head ?>
