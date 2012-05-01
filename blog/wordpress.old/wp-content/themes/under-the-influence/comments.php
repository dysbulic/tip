<?php
/**
 * @package WordPress
 * @subpackage Under_the_Influence
 */

	// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');
	if ( post_password_required() ) {
?>
<p class="nocomments">
	<?php _e('This post is password protected. Enter the password to view comments.', 'uti_theme')?>
</p>
<?php
		return;
	}
?>
<!-- You can start editing here. -->
<?php if ( have_comments() ) : ?>
	<?php $comments_by_type = &separate_comments($comments); ?>
	<?php if ( ! empty($comments_by_type['comment']) && empty($comments_by_type['pings'])) : ?>
		<!--There are only comments (no pings) -->
		<h3 id="comments">
			<?php comments_number(__('Leave a Comment', 'uti_theme'), __('One Comment', 'uti_theme'), __('% Comments', 'uti_theme' ));?>
			<?php _e('to &#8220;', 'uti_theme'); echo the_title() ?>&#8221;
		</h3>
	<?php endif; ?>

	<?php if ( ! empty($comments_by_type['pings']) && empty($comments_by_type['comment']) ) : ?>
		<!--There are only pings-->
		<h3 id="comments">
			<?php comments_number('No Trackbacks', 'One Trackback', '% Trackbacks' );?>
			<?php _e('to &#8220;', 'uti_theme'); echo the_title() ?>&#8221;
		</h3>
	<?php endif; ?>

	<?php if ( ! empty($comments_by_type['comment']) && ! empty($comments_by_type['pings']) || (  empty($comments_by_type['comment']) && empty($comments_by_type['pings']))) : ?>
		<!--There are comments and pings or none of either-->
		<h3 id="comments">
			<?php comments_number('No Responses', 'One Response', '% Responses' );?>
			<?php _e('to &#8220;', 'uti_theme'); echo the_title() ?>&#8221;
		</h3>
	<?php endif; ?>

	<div class="navigation">
		<?php previous_comments_link() ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php next_comments_link() ?>
	</div>
	<ol>
		<?php
			/* Constructs comments as declared in functions.php */
			wp_list_comments('type=comment&callback=uti_comment');
		?>
	</ol>
	<?php
		if ( ! empty($comments_by_type['comment']) && ! empty($comments_by_type['pings'])) :
	?>
	<!--There are comments and pings so a sub-header is inserted-->
	<h3 id="trackback">
		<?php _e('Trackbacks', 'uti_theme') ?>
	</h3>
	<ol>
		<?php
			/* Constructs comments as declared in functions.php */
			wp_list_comments('type=pingback&callback=custom_pings');
		?>
	</ol>
	<?php
		endif;
	?>
	<div class="navigation">
		<?php previous_comments_link() ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php next_comments_link() ?>
	</div>
<?php endif; ?>

	<?php if ( comments_open() ) : ?>
			<?php comment_form(); ?>
<?php endif; ?>