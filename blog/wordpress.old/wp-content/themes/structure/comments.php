<?php
/**
 * @package WordPress
 * @subpackage Structure
 */

// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e("This post is password protected. Enter the password to view comments.", 'structuretheme'); ?></p>
	<?php
		return;
	}
?>

<!-- You can start editing here. -->

<div id="comments">
	<?php if ( have_comments() ) : ?>
	<h5>Comments</h5>
	<b><?php comments_number('No Responses', 'One Response', '% Responses' );?> to &#8220;<?php the_title(); ?>&#8221;</b>
	<ol class="commentlist snap_preview">
	<?php wp_list_comments('type=comment&avatar_size=60'); ?>
	</ol>
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
	
	<?php if ( !empty($comments_by_type['pings']) ) :  ?>
	<h5><?php _e("Trackbacks", 'structuretheme'); ?></h5>
	<b><?php _e("Check out what others are saying...", 'structuretheme'); ?></b>
	<ol class="commentlist">
	<?php wp_list_comments('type=pings'); ?>
	</ol><br /><br />
	<?php endif; ?>
	
 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
	 
	 	<?php if ( ! is_page() ) : ?>
		<!-- If comments are closed. -->		
		<p class="nocomments"><?php _e("Comments are closed.", 'structuretheme'); ?></p>
		<?php endif; ?>

	<?php endif; ?>
	
<?php endif; ?>

<?php comment_form(); ?>
</div>