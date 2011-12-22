<?php // Do not delete these lines
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

        if (!empty($post->post_password)) { // if there's a password
            if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
				?>

				<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', 'chaoticsoul'); ?></p>

				<?php
				return;
            }
        }

		/* This variable is for alternating comment background */
		$oddcomment = 'alt';
?>

<!-- You can start editing here. -->

<?php if (have_comments()) : ?>
<div class="comments" id="comments">
	<h3><?php comments_number(__('No Responses Yet', 'chaoticsoul'), __('One Response', 'chaoticsoul'), __('% Responses', 'chaoticsoul') );?> to &#8220;<?php the_title(); ?>&#8221;</h3> 

	<ol class="commentlist">
	<?php wp_list_comments(array('callback' => 'chaoticsoul_comment')); ?>	
	</ol>
	
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>

</div>
<?php endif; ?>

<?php if ( $comments && !comments_open() ) : ?>
	<p class="nocomments"><?php _e('Comments are closed.', 'chaoticsoul'); ?></p>
<?php endif; ?>

<?php if ( comments_open() ) : ?>

	<div class="comments clearfix"><?php comment_form(); ?></div>

<?php endif; // if you delete this the sky will fall on your head ?>
