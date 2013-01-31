<?php
	// Do not access this file directly
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME'])) { die (__('Please do not load this page directly. Thanks!','k2_domain')); }

	// Password Protection
	if (post_password_required()) {
?>

	<p class="nopassword"><?php _e('This post is password protected. Enter the password to view comments.','k2_domain'); ?></p>

<?php return; }

if ((have_comments()) or (comments_open())) : $shownavigation = 'yes'; ?>

	<div class="comments">

		<h4><?php printf(__('%1$s %2$s to &#8220;%3$s&#8221;','k2_domain'), '<span id="comments">' . get_comments_number() . '</span>', (1 == $post->comment_count) ? __('Response','k2_domain'): __('Responses','k2_domain'), esc_html( get_the_title() ) ); ?></h4>

		<div class="metalinks">
			<span class="commentsrsslink"><?php post_comments_feed_link( __( 'Feed for this Entry', 'k2_domain' ) ); ?></span>
			<?php if ('open' == $post->ping_status) { ?><span class="trackbacklink"><a href="<?php trackback_url(); ?>" title="<?php esc_attr_e( 'Copy this URI to trackback this entry.', 'k2_domain' ); ?>"><?php _e('Trackback Address','k2_domain'); ?></a></span><?php } ?>
		</div>
	<hr />

	<ol id="commentlist">
	<?php wp_list_comments(array('callback'=>'k2_comment', 'avatar_size'=>48, 'type'=>'comment')); ?>
	</ol> <!-- END #commentlist -->
		
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
	<br />

	<ol id="pinglist">
	<?php wp_list_comments(array('callback'=>'k2_ping', 'type'=>'pings')); ?>
	</ol> <!-- END #pinglist -->
		
	<?php /* Comments open, but empty */ if ( ($post->comment_count < 1) and (comments_open()) ) { ?> 
	<ol id="commentlist">
		<li id="leavecomment">
			<?php _e('Leave a Comment','k2_domain'); ?>
		</li>
	</ol>
	<?php } ?>

	<?php /* Comments closed */ if ( !comments_open() ) { ?>
		<div><?php _e('Comments are currently closed.','k2_domain'); ?></div>
	<?php } ?>

	</div> <!-- END .comments 1 -->
		
	<?php endif; ?>
	
	<?php /* Reply Form */ if (comments_open()) { ?>

	<?php comment_form(); ?>

	<?php } // comment_status ?>

	<?php if ($shownavigation) { include (TEMPLATEPATH . '/navigation.php'); } ?>