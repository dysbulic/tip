<?php
/**
 * @package WordPress
 * @subpackage Clean Home
 */
?>
<?php // Do not delete these lines
	if ( !empty( $_SERVER['SCRIPT_FILENAME'] ) && 'comments.php' == basename( $_SERVER[ 'SCRIPT_FILENAME' ] ) )
		die ( 'Please do not load this page directly. Thanks!' );

	if ( !empty( $post->post_password) ) { // if there's a password
		if ( $_COOKIE[ 'wp-postpass_' . COOKIEHASH ] != $post->post_password ) {  // and it doesn't match the cookie
			?>

			<p class="nocomments"><?php _e( 'This post is password protected. Enter the password to view comments.', 'cleanhome' ); ?></p>

			<?php
			return;
		}
	}
?>

<div id="comments">

<?php if ( have_comments() ) : ?>

	<h3><?php comments_number( __( 'Leave a reply' ), __( 'One Comment' ), __( '% Comments' ) ); ?> on &#8220;<?php the_title(); ?>&#8221;</h3>

	<div class="navigation">
		<?php paginate_comments_links(); ?> 
	</div>

	<ol class="commentlist">
		<?php wp_list_comments( 'avatar_size=26' ); ?>
	</ol>

	<div class="navigation">
		<?php paginate_comments_links(); ?> 
	</div>

 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ( 'open' == $post->comment_status) : ?>

	 <?php else : // comments are closed ?>
		<p class="nocomments"><?php _e( 'Comments are closed.', 'cleanhome' ); ?></p>

	<?php endif; ?>
<?php endif; ?>


<?php if ( 'open' == $post->comment_status ) : ?>

<hr/>

<?php comment_form(); ?>

<?php endif; ?>

</div>
