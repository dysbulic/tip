<?php // Do not delete these lines

if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p class="nocomments"><?php _e("This post is password protected. Enter the password to view comments."); ?></p>
<?php
	return;
}

?>
<div id="comments-post">
<?php if (have_comments()) : ?>
	<h3 id="comments">
		<?php printf( _n( 'One Response to  &#8220;%2$s&#8221;', '%1$s Responses to  &#8220;%2$s&#8221;', get_comments_number() ), number_format_i18n( get_comments_number() ), get_the_title() ); ?>
	</h3> 

	<ol class="commentlist">
	<?php wp_list_comments(array('callback'=>'rounded_comment', 'avatar_size'=>48)); ?>
	</ol>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
	<br />

  <?php if ( !comments_open() ) : ?> 
		<p class="nocomments"><?php _e( 'Comments are closed.' ); ?></p>
  <?php endif; ?>
<?php endif; ?>


<?php comment_form(); ?>