<?php // Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p class="center"><?php _e("This post is password protected. Enter the password to view comments."); ?></p>
<?php
	return;
}
?>
<?php if ( ( have_comments() ) or ( comments_open() ) ) { ?>
<hr />
<div class="comments" id="comments">
<div class="center">
<h4><a href="#comments"><?php comments_number( __( 'Leave a Comment', 'twenty-eight' ), __( 'One Reply', 'twenty-eight'), __( '% Replies', 'twenty-eight' ) ); ?></a></h4>
</div>
<ol class="commentlist" id='commentlist'>
<?php if (have_comments()) : ?>
<?php wp_list_comments(array('callback'=>'twenty_eight_comment', 'avatar_size'=>48, 'type'=>'comment')); ?>
</ol>

<ol class="pinglist">
<?php global $count_pings; $count_pings = 1; ?>
<?php wp_list_comments(array('callback'=>'twenty_eight_ping', 'avatar_size'=>48, 'type'=>'pings')); ?>
</ol>
<div class="navigation">
	<div class="alignleft"><?php previous_comments_link(); ?></div>
	<div class="alignright"><?php next_comments_link(); ?></div>
</div>
<br />
<?php endif; ?>

<?php include (TEMPLATEPATH . '/navigation.php'); ?>
<?php comment_form(); ?>

</div>
<?php } ?>
