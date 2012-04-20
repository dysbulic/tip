<?php // Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');
	if ( post_password_required() ) {
		echo '<p class="nocomments">'.__('This post is password protected. Enter the password to view comments.', 'albeo').'</p>';
		return;
	}
?>

<?php
function albeo_comment_start($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
	?>

	<div <?php comment_class('com-entry'); ?> id="comment-<?php comment_ID(); ?>">
	<div class="com-entry-bot">
		<img src="<?php bloginfo('stylesheet_directory'); ?>/images/com-top.png" width="100%" height="10" />
		<div class="com-con">
			<p class="commentmetadata">
				<?php if ($args['avatar_size'] != 0) echo '<span class="avatar">'.get_avatar( $comment, $args['avatar_size'] ).'</span>'; ?>
				<span class="com-name"><?php if ( 0 == $comment->comment_parent || !get_option('thread_comments') ) { global $commentNumber; $commentNumber++; echo $commentNumber; ?> | <?php } comment_author_link(); ?></span><br />
				<span class="com-date"><a href="#comment-<?php comment_ID() ?>"><?php comment_date() ?> <?php _e('at', 'albeo'); ?> <?php comment_time() ?></a>  <?php edit_comment_link(__('edit', 'albeo'),'|&nbsp;',''); ?></span>
			</p>
	
			<?php if ($comment->comment_approved == '0') : ?>
				<p><em><?php _e('Your comment is awaiting moderation.', 'albeo'); ?></em></p>
			<?php endif; ?>									
			<?php comment_text(); ?>
			<div class="reply" id="comment-reply-<?php comment_ID(); ?>">
			<?php comment_reply_link(array_merge( $args, array('add_below' => 'comment-reply', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
			</div>
<?php }

function albeo_comment_end() { ?>
		</div>
		<img src="<?php bloginfo('stylesheet_directory'); ?>/images/com-bot.png" width="100%" height="10" />
	</div>
	</div>
<?php 
}
?>

<?php if ( have_comments() ) : ?>
<div class="com-list">
<h3 id="comments"><?php comments_number(__('No Responses Yet', 'albeo'), __('1 Response', 'albeo'), __('% Responses' , 'albeo')); ?> <?php _e('to', 'albeo'); ?> "<?php the_title(); ?>"</h3>

<?php wp_list_comments(array(
	'callback' => 'albeo_comment_start',
	'end-callback' =>'albeo_comment_end',
	'style'=>'div',
)); ?>
 
<div class="navigation">
<div class="alignleft"><?php previous_comments_link() ?></div>
<div class="alignright"><?php next_comments_link() ?></div>
</div>
</div>
<?php else : // this is displayed if there are no comments so far ?>
	<?php if (comments_open()) :
		// If comments are open, but there are no comments.
	elseif ( have_comments() ) : // comments are closed 
	?>
	<p><?php _e('Comments are closed.', 'albeo'); ?></p>
	<?php
	endif;
endif;
?>

<?php if (!comments_open() && have_comments()) : ?>
<p><?php _e('Comments are closed.', 'albeo'); ?></p>
<?php endif; ?>					

<?php comment_form(); ?>