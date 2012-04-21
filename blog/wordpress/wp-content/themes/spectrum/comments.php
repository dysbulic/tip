<?php
/**
 * @package WordPress
 * @subpackage Spectrum
 */

// Do not delete these lines
	if ( !empty( $_SERVER[ 'SCRIPT_FILENAME' ] ) && 'comments.php' == basename( $_SERVER[ 'SCRIPT_FILENAME' ] ) )
		die ( 'Please do not load this page directly. Thanks!' );

	if ( post_password_required() ) { ?>
		<p class="alert"><?php _e( 'This post is password protected. Enter the password to view comments.', 'spectrum' ); ?></p>
	<?php
		return;
	}
?>

<!-- You can start editing here. -->

<?php if ( have_comments() ) : ?>

<div id="comment-list">
	<div class="sub-title" id="comments">
		<h4><strong><?php _e( 'Comments on:', 'spectrum' ); ?></strong> "<?php the_title(); ?>" (<?php comments_number( '0', '1', '%' ); ?>)</h4>
	</div>
	<ol class="commentlist">
		<?php wp_list_comments('avatar_size=48&callback=spectrum_comments'); ?>
	</ol>
</div>

<?php else : // this is displayed if there are no comments so far ?>

	<?php if ( comments_open() ) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments"><?php _e( 'Comments are closed.', 'spectrum' ); ?></p>

	<?php endif; ?>
<?php endif; ?>


<?php if ( comments_open() ) : ?>

<?php comment_form(); ?>

<?php endif; ?>