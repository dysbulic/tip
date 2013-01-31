<?php // Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');
	if ( post_password_required() ) {
		echo '<p class="nocomments">'.__('This post is password protected. Enter the password to view comments.', 'albeo').'</p>';
		return;
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