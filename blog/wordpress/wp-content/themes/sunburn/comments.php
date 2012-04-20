<?php // Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p class="nocomments">This post is password protected. Enter the password to view comments.<p>
<?php
	return;
}
?>

<?php if ( have_comments() || comments_open() ) : ?>
	<h3 id="comments"><?php comments_number('No Responses Yet', 'One Response', '% Responses' );?> to &#8220;<?php the_title(); ?>&#8221;</h3> 
<?php endif; ?>

<?php if (have_comments()) : ?>
	<ol class="commentlist" id='commentlist'>
	<?php wp_list_comments(array('callback'=>'sunburn_comment', 'avatar_size'=>48)); ?>
	</ol>
	
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
	<br />

	<?php if (!comments_open()) : ?> 
	<p>Comments are closed.</p>
	<?php endif; ?>
<?php endif; ?>

<?php comment_form(); ?>
