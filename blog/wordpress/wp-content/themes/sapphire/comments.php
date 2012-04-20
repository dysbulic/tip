<?php // Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die (__('Please do not load this page directly. Thanks!', 'sapphire'));
if ( post_password_required() ) {
?>
<p class="nocomments"><?php _e("This post is password protected. Enter the password to view comments.", 'sapphire'); ?></p>
<?php
	return;
}

function sapphire_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<div id="div-comment-<?php comment_ID() ?>">
	<div class="comment-author vcard">
		<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
		<cite class="fn"><?php comment_author_link() ?></cite> <?php _e('Says:', 'sapphire'); ?>
	</div>
	<?php if ($comment->comment_approved == '0') : ?>
		<em><?php _e('Your comment is awaiting moderation.', 'sapphire'); ?></em>
	<?php endif; ?>
	<br />

	<small class="comment-meta commentmetadata"><a href="#comment-<?php comment_ID() ?>" title=""><?php comment_date() ?> <?php _e('at', 'sapphire'); ?> <?php comment_time() ?></a> <?php edit_comment_link(__('e', 'sapphire'),'',''); ?></small>

	<?php comment_text(); ?>

	<div class="reply">
		<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	</div>
<?php
}

if (have_comments()) : ?>
	<h3 id="comments"><?php comments_number(__('Be the First to Comment', 'sapphire'), __('One Comment', 'sapphire'), __('% Comments', 'sapphire') );?> <?php _e('on', 'sapphire'); ?> &#8220;<?php the_title(); ?>&#8221;</h3> 

	<ol class="commentlist">
	<?php wp_list_comments(array('callback'=>'sapphire_comment')); ?>
	</ol>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
	<br />
	
	<?php if (!comments_open()) : ?> 
		<p class="nocomments"><?php _e( 'Comments are closed.', 'sapphire' ); ?></p>
	<?php endif; ?>
<?php endif; ?>

<?php comment_form(); ?>
