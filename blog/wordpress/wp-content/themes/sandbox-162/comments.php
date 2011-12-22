<?php
	if ( 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']) )
		die ( 'Please do not load this page directly. Thanks.' );
?>
			<div id="comments">
<?php
	if ( !empty($post->post_password) ) :
		if ( $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password ) :
?>
				<div class="nopassword"><?php _e( 'This post is protected. Enter the password to view any comments.', 'sandbox' ) ?></div>
			</div><!-- .comments -->
<?php
		return;
	endif;
endif;
?>

<?php // Number of pings and comments
$ping_count = $comment_count = 0;
foreach ( $comments as $comment )
	get_comment_type() == "comment" ? ++$comment_count : ++$ping_count;
?>

<?php if ( $comment_count ) : ?>
	<?php $sandbox_comment_alt = 0 ?>
	<div id="comments-list" class="comments">
		<h3><?php printf($comment_count > 1 ? __('<span>%d</span> Comments', 'sandbox') : __('<span>One</span> Comment', 'sandbox'), $comment_count) ?></h3>
		<ol>
			<?php wp_list_comments( 'type=comment&callback=sandbox_comments' ); ?>
		</ol>

		<div class="navigation">
			<div class="alignleft"><?php previous_comments_link() ?></div>
			<div class="alignright"><?php next_comments_link() ?></div>
		</div>
	</div><!-- #comments-list .comments -->
<?php endif; // REFERENCE: if ( $comment_count ) ?>

<?php if ( $ping_count ) : ?>
<?php $sandbox_comment_alt = 0 ?>
	<div id="trackbacks-list" class="comments">
	<h3><?php printf($ping_count > 1 ? __('<span>%d</span> Trackbacks', 'sandbox') : __('<span>One</span> Trackback', 'sandbox'), $ping_count) ?></h3>
	<ol>
		<?php wp_list_comments( 'type=pings&callback=sandbox_trackbacks' ); ?>
	</ol>
	</div><!-- #trackbacks-list .comments -->

<?php endif // REFERENCE: if ( $ping_count ) ?>
<div class="navigation">
 <?php paginate_comments_links(); ?> 
</div>

<?php comment_form(); ?>

			</div><!-- #comments -->
