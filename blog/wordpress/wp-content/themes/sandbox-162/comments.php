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
<?php
function sandbox_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
	global $sandbox_comment_alt; 
	?>
						<li id="comment-<?php comment_ID() ?>" class="<?php sandbox_comment_class() ?>">
							<div class="comment-author vcard"><?php sandbox_commenter_link() ?></div>
							<div class="comment-meta"><?php printf(__('Posted %1$s at %2$s <span class="meta-sep">|</span> <a href="%3$s" title="Permalink to this comment">Permalink</a>', 'sandbox'),
										get_comment_date(),
										get_comment_time(),
										'#comment-' . get_comment_ID() );
										echo comment_reply_link(array('depth' => $depth, 'max_depth' => $args['max_depth'], 'before' => ' | ')); 
										edit_comment_link(__('Edit', 'sandbox'), ' <span class="meta-sep">|</span> <span class="edit-link">', '</span>'); ?></div>
<?php if ($comment->comment_approved == '0') _e("\t\t\t\t\t<span class='unapproved'>Your comment is awaiting moderation.</span>\n", 'sandbox') ?>
							<?php comment_text() ?>
<?php } ?>
<?php
function sandbox_trackbacks($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
	global $sandbox_comment_alt; 
	?>
    <li id="comment-<?php comment_ID() ?>" class="<?php sandbox_comment_class() ?>">
    	<div class="comment-author"><?php printf(__('By %1$s on %2$s at %3$s', 'sandbox'),
    			get_comment_author_link(),
    			get_comment_date(),
    			get_comment_time() );
    			edit_comment_link(__('Edit', 'sandbox'), ' <span class="meta-sep">|</span> <span class="edit-link">', '</span>'); ?></div>
    	<?php if ($comment->comment_approved == '0') _e('\t\t\t\t\t<span class="unapproved">Your trackback is awaiting moderation.</span>\n', 'sandbox') ?>
    	<?php comment_text() ?>
<?php } ?>

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
