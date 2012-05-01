<?php // Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');
	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e('This post is password protected.'); ?></p>
	<?php
		return;
	}

function greenery_10_comment($comment, $args, $depth) {
	global $relax_comment_count;
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
	<li <?php comment_class(empty( $args['has_children'] ) ? 'item' : 'item parent') ?> id="comment-<?php comment_ID() ?>" >
		<div id="div-comment-<?php comment_ID() ?>">
		<div class="commentcounter"><?php echo $relax_comment_count; ?></div>
		
			<div class="comment-author vcard">
				<div class="commentgravatar">
					<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
				</div>
			
				<h3 class="commenttitle"><span class="fn"><?php comment_author_link() ?></span> <?php _e('said'); ?>,</h3>
			</div>
			<p class="commentmeta comment-meta commentmetadata">
				<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
					<?php comment_date() ?> @ <?php comment_time() ?>
				</a>
				<?php if (function_exists('quoter_comment')) { quoter_comment(); } ?>
				<?php edit_comment_link(__("Edit"), ' &#183; ', ''); ?>
	
			</p>
			<?php comment_text() ?>
			<div class="reply">
				<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
			</div>
		</div>		
	<?php $relax_comment_count++; ?>
<?php
}

if (have_comments()) : ?>

	<h2 id="comments">
		<?php comments_number(__('Comments'), __('1 Response'), __('% Responses')); ?> <?php _e('so far'); ?>
		<?php if ( comments_open() ) : ?>
			<a href="#respond" title="<?php _e('Jump to the comments form'); ?>">&raquo;</a>
		<?php endif; ?>
	</h2>
	
	<ol id='commentlist' class="commentlist">
	<!-- Comment Counter -->
	<?php 
	global $relax_comment_count;
	$relax_comment_count=isset($commentcount)? $commentcount+0 : 1; 
	
	wp_list_comments(array('callback'=>'greenery_10_comment', 'avatar_size'=>48)); 
	?>
	</ol>
	
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
	<br />
	
	<p class="small">
		<?php post_comments_feed_link( __( 'Comment <abbr title="Really Simple Syndication">RSS</abbr>' ) ); ?>
		<?php if ( pings_open() ) : ?>
			&#183; <a href="<?php trackback_url() ?>" rel="trackback"><?php _e('TrackBack <abbr title="Uniform Resource Identifier">URI</abbr>'); ?></a>
		<?php endif; ?>
	</p>
<?php endif; ?>

<?php if (comments_open()) : ?>

<?php comment_form(); ?>

<?php elseif ( have_comments() ) : ?>
	<p><?php _e('Comments are closed.'); ?></p>
<?php endif; // if you delete this the sky will fall on your head ?>
