<?php // Do not delete these lines

if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p class="nocomments"><?php _e("This post is password protected. Enter the password to view comments."); ?></p>
<?php
	return;
}


function monotone_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<div id="div-comment-<?php comment_ID() ?>">
		<div class="comment-author vcard">
		<div class="gravatar"><?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?></div>
		<div class="comment-meta commentmetadata metadata">
			<a href="#comment-<?php comment_ID() ?>" title=""><?php comment_date('j M Y') ?> at <?php comment_time() ?></a>
			<cite class="fn"><?php comment_author_link() ?></cite>
			<?php edit_comment_link('edit','<br />',''); ?>
			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => 'reply', 'add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div>
		</div>
		</div>	
		<div class="content">
			
			<?php if ($comment->comment_approved == '0') : ?>
			<p><em>Your comment is awaiting moderation.</em></p>
			<?php endif; ?>
			<?php comment_text() ?>
		</div>
		<div class="clear"></div>
	</div>
<?php
}

if (have_comments()) : ?>
	<h3 id="comments"><?php comments_number('No Responses Yet', 'One Response', '% Responses' );?> to &#8220;<?php the_title(); ?>&#8221;</h3>

	<ol class="commentlist">
	<?php wp_list_comments(array('callback'=>'monotone_comment')); ?>
	</ol>
	<div class="comment-navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
	
	<br />

	<?php if (!comments_open()) : ?>
		<p class="nocomments">Comments are closed.</p>
	<?php endif; ?>
<?php endif; ?>


<?php if (comments_open()) : ?>

<?php comment_form(); ?>

<?php endif; // if you delete this the sky will fall on your head ?>