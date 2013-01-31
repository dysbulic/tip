<?php
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');

// Used by regulus_comment() in functions.php
global $commentCount;
$commentCount = 1;

if ( have_comments() || comments_open() ) { ?>
<div id="comments">
	<h2><?php _e('Comments'); if ( comments_open() ) : ?><a href="#postComment" title="leave a comment">&raquo;</a><?php endif; ?></h2>
<?php
	if ( post_password_required() ) { ?>
		<p>Enter your password to view comments</p>
 	<?php
	} else if ( have_comments() ) { ?>

		<dl class="commentlist">

		<?php wp_list_comments(array('callback'=>'regulus_comment', 'end-callback'=>'regulus_end_comment','style'=>'div')); 
		?>
		</dl>
		
		<div class="navigation">
			<div class="alignleft"><?php previous_comments_link() ?></div>
			<div class="alignright"><?php next_comments_link() ?></div>
		</div>
		<br />

	<?php 
		if ( !comments_open() ) echo "<p>Sorry comments are closed for this entry</p>";
	} else { // If there are no comments yet
		if ( comments_open() ) echo "<p>No comments yet &#8212 be the first.</p>";
	} ?>
	
</div>

<?php
	comment_form();
?>

<?php } ?>
