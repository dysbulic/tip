<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/comment.js?1"></script>

<?php if ( !empty($post->post_password) && $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password ) : ?>
	<div class="errorbox">
		<?php _e('Enter your password to view comments.', 'inove'); ?>
	</div>
<?php return; endif; ?>

<?php
	$options = get_option('inove_options');
	$trackbacks = $comments_by_type['pings'];
?>

<?php if ( have_comments() || comments_open() ) : ?>
<div id="comments">

<div id="cmtswitcher">
	<?php if( pings_open() ) : ?>
		<a id="commenttab" class="curtab" href="javascript:void(0);" onclick="MGJS.switchTab('thecomments,commentnavi', 'thetrackbacks', 'commenttab', 'curtab', 'trackbacktab', 'tab');"><?php _e('Comments', 'inove'); echo (' (' . (count($comments)-count($trackbacks)) . ')'); ?></a>
		<a id="trackbacktab" class="tab" href="javascript:void(0);" onclick="MGJS.switchTab('thetrackbacks', 'thecomments,commentnavi', 'trackbacktab', 'curtab', 'commenttab', 'tab');"><?php _e('Trackbacks', 'inove'); echo (' (' . count($trackbacks) . ')'); ?></a>
	<?php else : ?>
		<a id="commenttab" class="curtab" href="javascript:void(0);"><?php _e('Comments', 'inove'); echo (' (' . (count($comments)-count($trackbacks)) . ')'); ?></a>
	<?php endif; ?>
	<?php if( comments_open() ) : ?>
		<span class="addcomment"><a href="#respond"><?php _e('Leave a comment', 'inove'); ?></a></span>
	<?php endif; ?>
	<?php if( pings_open() ) : ?>
		<span class="addtrackback"><a href="<?php trackback_url(); ?>"><?php _e('Trackback', 'inove'); ?></a></span>
	<?php endif; ?>
	<div class="fixed"></div>
</div>

<div id="commentlist">
	<!-- comments START -->
	<ol id="thecomments">
	<?php
		if ( have_comments() && count($comments) - count($trackbacks) > 0 ) {
			wp_list_comments('type=comment&callback=custom_comments');
		} else {
	?>
		<li class="messagebox">
			<?php _e('No comments yet.', 'inove'); ?>
		</li>
	<?php
		}
	?>
	</ol>
	<!-- comments END -->

<?php
	if ( get_option('page_comments') ) {
		$comment_pages = paginate_comments_links('echo=0');
		if ( $comment_pages ) {
?>
		<div id="commentnavi">
			<span class="pages"><?php _e('Comment pages', 'inove'); ?></span>
			<div id="commentpager">
				<?php echo $comment_pages; ?>
				<span id="cp_post_id"><?php echo $post->ID; ?></span>
			</div>
			<div class="fixed"></div>
		</div>
<?php
		}
	}
?>

	<!-- trackbacks START -->
	<?php if ( pings_open() ) : ?>
		<ol id="thetrackbacks">
			<?php if ( $trackbacks ) : $trackbackcount = 0; ?>
				<?php foreach ($trackbacks as $comment) : ?>
					<li class="trackback">
						<div class="date">
							<?php printf( __('%1$s at %2$s', 'inove'), get_comment_time(get_option('date_format')), get_comment_time(get_option('the_time')) ); ?>
							 | <a href="#comment-<?php comment_ID() ?>"><?php printf('#%1$s', ++$trackbackcount); ?></a>
						</div>
						<div class="act">
							<?php edit_comment_link(__('Edit', 'inove'), '', ''); ?>
						</div>
						<div class="fixed"></div>
						<div class="title">
							<a href="<?php comment_author_url() ?>">
								<?php comment_author(); ?>
							</a>
						</div>
					</li>
				<?php endforeach; ?>

			<?php else : ?>
				<li class="messagebox">
					<?php _e('No trackbacks yet.', 'inove'); ?>
				</li>

			<?php endif; ?>
		</ol>
	<?php endif; ?>
	<div class="fixed"></div>
	<!-- trackbacks END -->
</div>

</div>
<?php endif; ?>

<?php if ( !comments_open() ) : // If comments are closed. ?>
	<?php if ( have_comments() ) : ?>
	<div class="messagebox">
		<?php _e('Comments are closed.', 'inove'); ?>
	</div>
	<?php endif; ?>
<?php elseif ( get_option('comment_registration') && !$user_ID ) : // If registration required and not logged in. ?>
	<div id="comment_login" class="messagebox">
		<?php printf(__('You must be <a href="%s">logged in</a> to post a comment.', 'inove'), wp_login_url()); ?>
	</div>

<?php else : ?>

<?php comment_form(); ?>

	<?php if ( $options['ctrlentry'] ) : ?>
		<script type="text/javascript">MGJS.loadCommentShortcut();</script>
	<?php endif; ?>

<?php endif; ?>
