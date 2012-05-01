<?php // Do not delete these lines
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

        if (post_password_required()) { 
	?>
			
		<p class="center"><?php _e( 'This post is password protected. Enter the password to view comments.', 'unsleepable' ); ?><p>
				
<?php	return; } 

if ((have_comments()) or (comments_open())) { ?>

<hr />

<div class="comments" id="comments">

	<h4>
		<a href="#comments">
			<?php
				if ( have_comments() ) {
					printf( _n( 'One Response to &#8220;%2$s&#8221;', '%1$s Responses to &#8220;%2$s&#8221;',
						get_comments_number(), 'unsleepable' ),
				        number_format_i18n( get_comments_number() ), get_the_title()
					);
				} else {
					printf( __( 'No Responses Yet to &#8220;%1$s&#8221;', 'unsleepable' ),
						get_the_title()
					);
				}
			?>
		</a>
	</h4>

	<div class="metalinks">
		<span class="commentsrsslink"><?php post_comments_feed_link( __( 'Feed for this Entry', 'unsleepable' ) ); ?></span>
		<?php if (pings_open()) { ?><span class="trackbacklink"><a href="<?php trackback_url() ?>" title="<?php esc_attr_e( __( 'Copy this URI to trackback this entry.', 'unsleepable' ) ); ?>">Trackback Address</a></span><?php } ?>
	</div>
	
	<ol class="commentlist" id='commentlist'>
	<?php if (have_comments()) { 
		global $count_pings;
		$count_pings = 1;
		wp_list_comments(array('callback'=>'unsleepable_comment', 'avatar_size'=>48, 'type'=>'comment')); 
		?>
		</ol>

		<div class="navigation">
			<div class="alignleft"><?php previous_comments_link() ?></div>
			<div class="alignright"><?php next_comments_link() ?></div>
		</div>
		<br />

		<ol class="pinglist">
		<?php 
		$count_pings = 1; 
		wp_list_comments(array('callback'=>'unsleepable_ping', 'type'=>'pings')); 
		?>
		</ol>
		
	<?php } else { // this is displayed if there are no comments so far ?>

		<?php if (comments_open()) { ?> 
			<!-- If comments are open, but there are no comments. -->
				<li id="leavecomment"><?php _e( 'Leave a Comment', 'unsleepable' ); ?></li>

		<?php } else { // comments are closed ?>

			<!-- If comments are closed. -->

			<?php if (is_single()) { // To hide comments entirely on Pages without comments ?>
				<li><?php _e( 'Comments are currently closed.', 'unsleepable' ); ?></li>
			<?php } ?>
	
		<?php } ?>

		</ol>

	<?php } ?>

	<?php if (have_comments()) { ?>
		<?php include ( get_template_directory() . '/navigation.php' ); ?>
	<?php } ?>


	<?php comment_form(); ?>

</div> <!-- Close .comments container -->
<?php }