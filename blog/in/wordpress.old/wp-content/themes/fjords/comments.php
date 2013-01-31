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
<a href="#postcomment" title="<?php esc_attr_e( 'Leave a comment', 'fjords' ); ?>">&raquo;</a>
<?php endif; ?>
</h2>
<?php endif; ?>

<?php
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