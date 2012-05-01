<?php
/**
 * @package WordPress
 * @subpackage Inuit Types
 */
?>
<?php // Do not delete these lines

	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))

		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>

		<p class="nocomments"><?php echo $comment_password_name; ?></p>

	<?php

		return;
	}

	/* This variable is for alternating comment background */
	$oddcomment = 'alt';
?>

<?php

	global $bm_comments;

	global $bm_trackbacks;

	split_comments( $comments );

?>

<!-- You can start editing here. -->

<?php if ( have_comments() ) : ?>


	<?php

		$trackbackcounter = count( $bm_trackbacks );

		$commentcounter = count( $bm_comments );

	?>

	<div class="commh2"><?php comments_number(
		sprintf(__('Be the first to start a conversation', 'it')),
		sprintf(__('One Response %s &rarr;', 'it'), ' &#8220;' . get_the_title() . '&#8221'),
		sprintf(__('%% Responses %s &rarr;', 'it'), ' &#8220;' . get_the_title() . '&#8221')
	); ?> </div>

<?php if ( get_comment_pages_count() > 1 ) : // are there comments to navigate through ?>

			<div id="comment-nav-above" class="navigation">

				<div class="nav-previous"><?php previous_comments_link( __('&larr; Older Comments', 'it') ); ?></div>

				<div class="nav-next"><?php next_comments_link( __('Newer Comments &rarr;', 'it') ); ?></div>

			</div>

<?php endif; // check for comment navigation ?>

	<ol class="commentlist snap_preview">

	<?php wp_list_comments('type=comment&callback=inuitypes_comment'); ?>

	</ol>

<?php if ( get_comment_pages_count() > 1 ) : // are there comments to navigate through ?>

			<div id="comment-nav-below" class="navigation">

				<div class="nav-previous"><?php previous_comments_link( __('&larr; Older Comments', 'it') ); ?></div>

				<div class="nav-next"><?php next_comments_link( __('Newer Comments &rarr;', 'it') ); ?></div>

			</div>

<?php endif; // check for comment navigation ?>

	<?php if ( count( $bm_trackbacks ) > 0 ) { ?>

	<div class="commh2">
	<?php
		if ( $trackbackcounter == 1 ) {
			echo $trackbackcounter . __( ' Trackback For This Post', 'it' );
		} else {
			echo $trackbackcounter . __( ' Trackbacks For This Post', 'it' );
		}
	?>
	</div>

	<ol class="trackbacklist snap_preview">

	<?php foreach ($bm_trackbacks as $comment) : ?>

		<li class="<?php echo $oddcomment; ?> <?php if(function_exists("author_highlight")) author_highlight(); ?>" id="trackback-<?php comment_ID() ?>">

			<cite><?php comment_author_link() ?></cite> &rarr;
			<?php if ($comment->comment_approved == '0') : ?>
			<em><?php _e( 'Your comment is awaiting moderation.', 'it' ); ?></em>
			<?php endif; ?>
			<br />

			<small class="commentmetadata"><a href="#trackback-<?php comment_ID() ?>" title=""><?php comment_date('F jS, Y') ?> &rarr; <?php comment_time() ?></a> <?php edit_comment_link('e','',''); ?></small>

			<?php comment_text() ?>

		</li>

	<?php /* Changes every other comment to a different class */
		if ('alt' == $oddcomment) $oddcomment = '';
		else $oddcomment = 'alt';
	?>

	<?php endforeach; /* end for each comment */ ?>

	</ol>

	<?php } ?>

 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->
		<div class="commh2"><?php _e( 'Be the first to start a conversation', 'it' ); ?></div>

	 <?php elseif ( ! is_page() && 'closed' == $post->comment_status) : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments"><?php _e( 'Comments are closed.', 'it' ); ?></p>

	<?php endif; ?>
<?php endif; ?>


<?php if ('open' == $post->comment_status) : ?>

<?php comment_form(); ?>

<?php endif; // if you delete this the sky will fall on your head ?>
