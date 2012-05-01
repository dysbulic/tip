<?php // Do not delete these lines

if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p class="nocomments">This post is password protected. Enter the password to view comments.</p>
<?php
	return;
}

function vermilion_christmas_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	global $relax_comment_count;
	extract($args, EXTR_SKIP);
	$relax_comment_count++;
?>
<li <?php comment_class(empty( $args['has_children'] ) ? 'comment-body' : 'comment-body parent') ?> id="comment-<?php comment_ID() ?>">
	<div id="div-comment-<?php comment_ID() ?>">
	<div class="vcard">
		<?php echo get_avatar( $comment, 48 ); ?>
		<div class="comment-count"><?php echo $relax_comment_count; ?></div>
		<div class="comment-author"><cite class="fn"><?php comment_author_link() ?></cite> Says:</div>
	</div>
	<?php if ($comment->comment_approved == '0') : ?>
		<em>Your comment is awaiting moderation.</em>
	<?php endif; ?>

	<?php comment_text() ?>
	<div style="text-align:right; ">
	<small class="commentmetadata">Posted on <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>" title=""><?php comment_date() ?> at <?php comment_time() ?></a> <?php edit_comment_link('e','',''); ?></small>
	</div>
	
	<div class="reply">
		<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	</div>
<?php
}


if (have_comments()) : ?>
<h3 id="comments"><?php comments_number('Leave a Comment', 'One Comment', '% Comments' );?> <!-- on &#8220;<?php the_title(); ?>&#8221; --></h3> 
<ol class="commentlist">
<?php wp_list_comments(array('callback'=>'vermilion_christmas_comment', 'avatar_size'=>48)); ?>
</ol>

<div class="navigation">
	<div class="alignleft"><?php previous_comments_link() ?></div>
	<div class="alignright"><?php next_comments_link() ?></div>
</div>
<br />

<small class="commentfeed"><?php post_comments_feed_link( 'RSS Feed for this entry' ); ?></small>

	<?php if (!comments_open()) : ?> 
		<p class="nocomments">Comments are closed.</p>
	<?php endif; ?>
<?php endif; ?>

<?php comment_form(); ?>
