<?php
/**
 * @package WordPress
 * @subpackage Fjords
 */
?>
<?php
if ( !empty( $_SERVER[ 'SCRIPT_FILENAME' ] ) && 'comments.php' == basename( $_SERVER[ 'SCRIPT_FILENAME' ] ) )
	die ( __('Please do not load this page directly. Thanks!', 'fjords') );
if ( post_password_required() ) {
?>
<p><?php _e( 'Enter your password to view comments.', 'fjords' ); ?></p>
<?php
	return;
}
?>
<?php if ( have_comments() || comments_open() ) : ?>
<h2 id="comments"><?php comments_number( __( 'No comments yet', 'fjords' ), __( '1 Comment', 'fjords' ), __( '% Comments', 'fjords' ) ); ?>
<?php if ( comments_open() ) : ?>
<a href="#postcomment" title="<?php _e( 'Leave a comment', 'fjords' ); ?>">&raquo;</a>
<?php endif; ?>
</h2>
<?php endif; ?>

<?php

function fjords_comment( $comment, $args, $depth ) {
	$GLOBALS[ 'comment' ] = $comment;
	extract( $args, EXTR_SKIP );
?>
<div <?php comment_class( empty( $args[ 'has_children' ] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID(); ?>">
	<div id="div-comment-<?php comment_ID(); ?>">
	<div class="comentarios">
		<span class="comment-author vcard"><?php if ( $args[ 'avatar_size' ] != 0 ) echo get_avatar( $comment, $args[ 'avatar_size' ] ); ?>&nbsp;
		<span class="fn"><a href="<?php comment_author_url(); ?>">
		<?php printf ( __( '%1$s wrote @ %2$s at %3$s' ), comment_author() . '</a></span>' , '<span class="comment-meta commentmetadata">' . get_comment_date(), get_comment_time().'</span>' ) ?>
		</span>
	</div>
	<?php comment_text(); ?>
	<div class="reply">
		<?php comment_reply_link( array_merge( $args, array( 'add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args[ 'max_depth' ] ) ) ); ?>
	</div>
	</div>
<?php
}

if ( have_comments() ) :
wp_list_comments( array( 'callback' => 'fjords_comment', 'style' => 'div' ) );
?>
<div class="navigation">
	<div class="alignleft"><?php previous_comments_link(); ?></div>
	<div class="alignright"><?php next_comments_link(); ?></div>
</div>
<br />

<?php if ( !comments_open() ) : ?>
	<p><?php _e( 'Sorry, the comment form is closed at this time.', 'fjords' ); ?></p>
<?php endif; ?>

<?php else : // If there are no comments yet ?>

<?php endif; ?>

<?php if ( comments_open() ) : ?>

<?php comment_form(); ?>

<?php endif; ?>