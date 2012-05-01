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

function redoable_lite_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	global $comment_count;
	$comment_count++;
	extract($args, EXTR_SKIP);
?>
<li id="comment-<?php comment_ID(); ?>" <?php comment_class(redo_comment_class($comment_count, false)); ?>>
	<div id="div-comment-<?php comment_ID() ?>">
	<div class="vcard">
	<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
	<a href="#comment-<?php comment_ID(); ?>" class="counter" title="<?php _e('Permanent Link to this Comment','redo_domain'); ?>"><?php echo $comment_count; ?></a>
	<span class="commentauthor fn"><?php comment_author_link(); ?></span>
	</div>

	<small class="comment-meta">
	<?php
		printf('<a href="#comment-%1$s" title="%2$s">%3$s</a>', 
			get_comment_ID(),
			(function_exists('time_since')?
				sprintf(__('%s ago.','redo_domain'),
					time_since(abs(strtotime($comment->comment_date_gmt . " GMT")), time())
				):
				__('Permanent Link to this Comment','redo_domain')
			),
			sprintf(__('%1$s at %2$s','redo_domain'),
				get_comment_date(get_option('date_format')),
				get_comment_time()
			)
		);
	?>
	</small>

	<div class="comment-content">
		<?php comment_text(); ?> 
	</div>
	<div class="reply">
		<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>

	<?php if ('0' == $comment->comment_approved) { ?><p class="alert"><strong><?php _e('Your comment is awaiting moderation.','redo_domain'); ?></strong></p><?php } ?>
	</div>
<?php 
}

function redoable_lite_ping($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	global $ping_count;
	$ping_count++;
	extract($args, EXTR_SKIP);
?>
<li id="comment-<?php comment_ID(); ?>" <?php comment_class(redo_comment_class($ping_count, false)); ?>>
	<div id="div-comment-<?php comment_ID() ?>">
	<a href="#comment-<?php comment_ID() ?>" title="<?php _e('Permanent Link to this Comment','redo_domain'); ?>" class="counter"><?php echo $ping_index; ?></a>
	<span class="commentauthor"><?php comment_author_link(); ?></span>
	<small class="comment-meta">				
	<?php
		printf(__('%1$s on %2$s','redo_domain'), 
			'<span class="pingtype">' . get_redo_ping_type(__('Trackback','redo_domain'), __('Pingback','redo_domain')) . '</span>',
			sprintf('<a href="#comment-%1$s" title="%2$s">%3$s</a>',
				get_comment_ID(),	
				(function_exists('time_since')?
					sprintf(__('%s ago.','redo_domain'),
						time_since(abs(strtotime($comment->comment_date_gmt . " GMT")), time())
					):
					__('Permanent Link to this Comment','redo_domain')
				),
				sprintf(__('%1$s at %2$s','redo_domain'),
					get_comment_date(__('M jS, Y','redo_domain')),
					get_comment_time()
				)			
			)
		);
	?>				
	<?php if ($user_ID) { edit_comment_link(__('Edit','redo_domain'),'<span class="comment-edit">','</span>'); } ?>
	</small>
	</div>
<?php 
}


if ((have_comments()) or (comments_open())) : $shownavigation = 'yes'; ?>

	<div class="comments">

		<h4><?php printf(__('%1$s %2$s to &#8220;%3$s&#8221;','redo_domain'), '<span id="comments">' . get_comments_number() . '</span>', (1 == $post->comment_count) ? __('Response','redo_domain'): __('Responses','redo_domain'), the_title_attribute( 'echo=0' ) ); ?></h4>

		<div class="metalinks">
			<span class="commentsrsslink"><?php post_comments_feed_link( __( 'Feed for this Entry','redo_domain' ) ); ?></span>
			<?php if (pings_open()) { ?><span class="trackbacklink"><a href="<?php trackback_url(); ?>" title="<?php _e('Copy this URI to trackback this entry.','redo_domain'); ?>"><?php _e('Trackback Address','redo_domain'); ?></a></span><?php } ?>
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
