<hr />

<div id="comment-block">
	
<?php
	// Do not access this file directly
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME'])) { die (__('Please do not load this page directly. Thanks!','redo_domain')); }

	// Password Protection
	if (post_password_required()) {
?>

	<p class="nopassword"><?php _e('This post is password protected. Enter the password to view comments.','redo_domain'); ?></p>
	</div>

<?php return; 
} 

if ((have_comments()) or (comments_open())) : $shownavigation = 'yes'; ?>

	<div class="comments">

		<h4><?php printf(__('%1$s %2$s to &#8220;%3$s&#8221;','redo_domain'), '<span id="comments">' . get_comments_number() . '</span>', (1 == $post->comment_count) ? __('Response','redo_domain'): __('Responses','redo_domain'), the_title_attribute( 'echo=0' ) ); ?></h4>

		<div class="metalinks">
			<span class="commentsrsslink"><?php post_comments_feed_link( __( 'Feed for this Entry','redo_domain' ) ); ?></span>
			<?php if (pings_open()) { ?><span class="trackbacklink"><a href="<?php trackback_url(); ?>" title="<?php esc_attr_e( 'Copy this URI to trackback this entry.', 'redo_domain' ); ?>"><?php _e('Trackback Address','redo_domain'); ?></a></span><?php } ?>
		</div>
	<hr />

	<ol id="commentlist">
	<?php wp_list_comments(array('callback'=>'redoable_lite_comment', 'type'=>'comment')); ?>
	</ol> <!-- END #commentlist -->

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>	
	<br />
		
	<ol id="pinglist">
	<?php wp_list_comments(array('callback'=>'redoable_lite_ping', 'type'=>'pings')); ?>
	</ol> <!-- END #pinglist -->
		
		<?php /* Comments open, but empty */ if ( ($post->comment_count < 1) and ('open' == $post->comment_status) ) { ?> 
		<ol id="commentlist">
			<li id="leavecomment">
				<?php _e('Leave a Comment','redo_domain'); ?>
			</li>
		</ol>
		<?php } ?>
		
		<?php /* Comments closed */ if (!comments_open() and is_single()) { ?>
			<div><?php _e('Comments are currently closed.','redo_domain'); ?></div>
		<?php } ?>

	</div> <!-- END .comments 1 -->
		
	<?php endif; ?>
	
	<?php comment_form(); ?>
	
	<div class="comments">
		<?php if ($shownavigation) { include (TEMPLATEPATH . '/navigation.php'); } ?>
	</div>

</div>
