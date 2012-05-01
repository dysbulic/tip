<?php
/**
 * @package WordPress
 * @subpackage Bueno
 */	
	// Do not delete these lines
	
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', 'woothemes') ?></p>
	
	<?php return; } ?>

<!-- You can start editing here. -->

	<?php if ( have_comments() ) : ?>
		<?php if ( ! empty($comments_by_type['comment']) || ! empty($comments_by_type['pings'])  ) : ?>
		
		<div id="comments">

			<h3><?php comments_number(__('No Responses', 'woothemes'), __('One Response', 'woothemes'), __('% Responses', 'woothemes') );?> <?php _e('to', 'woothemes') ?> &#8220;<?php the_title(); ?>&#8221;</h3>

			<ol class="commentlist">
	
				<?php wp_list_comments('avatar_size=48&callback=custom_comment&type=comment'); ?>
		
			</ol>    

			<div class="navigation">
				<div class="fl"><?php previous_comments_link() ?></div>
				<div class="fr"><?php next_comments_link() ?></div>
				<div class="fix"></div>
			</div><!-- /.navigation -->
			
		</div> <!-- /#comments_wrap -->
		    
			<?php if ( ! empty($comments_by_type['pings']) ) : ?>
			
			<div id="pings">
			
				<h3><?php _e('Trackbacks/Pingbacks', 'woothemes') ?></h3>
			
				<ol class="pinglist">
					<?php wp_list_comments('type=pings&callback=list_pings'); ?>
				</ol>
				
			</div><!-- /#pings -->

			<?php endif; ?>	
    	
    	<?php endif; ?>

	<?php endif; // have_comments() ?>
	
	<?php
	/* If there are comments and comments are closed, let's leave a little note, shall we?
	 * But we only want the note on posts and pages that had comments in the first place.
	 * It's just the polite thing to do!
	 */
	if ( ! comments_open() && '0' != get_comments_number() ) :
	?>
		<div id="comments">
	
			<p class="nocomments"><?php _e('Comments are closed.', 'woothemes') ?></p>
		
		</div><!-- /#comments -->
	<?php endif; ?>

<?php comment_form(); ?>
