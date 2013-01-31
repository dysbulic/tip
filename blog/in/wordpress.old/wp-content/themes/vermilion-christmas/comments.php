<?php // Do not delete these lines

if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p class="nocomments"><?php _e( 'This post is password protected. Enter the password to view comments.', 'vermilionchristmas' ); ?></p>
<?php
	return;
}

if ( have_comments() ) : ?>
<h3 id="comments"><?php comments_popup_link( __( 'Leave a Comment &#187;', 'vermilionchristmas' ), __( '1 Comment &#187;', 'vermilionchristmas' ), __( '% Comments &#187;', 'vermilionchristmas' ) ); ?> <!-- on &#8220;<?php the_title(); ?>&#8221; --></h3> 
<ol class="commentlist">
<?php wp_list_comments( array( 'callback' => 'vermilion_christmas_comment', 'avatar_size' => 48 ) ); ?>
</ol>

<div class="navigation">
	<div class="alignleft"><?php previous_comments_link(); ?></div>
	<div class="alignright"><?php next_comments_link(); ?></div>
</div>
<br />

<small class="commentfeed"><?php post_comments_feed_link( __( 'RSS Feed for this entry', 'vermilionchristmas' ) ); ?></small>

	<?php if ( !comments_open() ) : ?> 
		<p class="nocomments"><?php _e( 'Comments are closed.', 'vermilionchristmas' ); ?></p>
	<?php endif; ?>
<?php endif; ?>

<?php comment_form(); ?>
