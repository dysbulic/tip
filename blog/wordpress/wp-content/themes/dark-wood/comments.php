<div class="comments">

<?php // Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if (!empty($post->post_password)) { // if there's a password
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
			?>

			<p class="nocomments"><?php _e( 'This post is password protected. Enter the password to view comments' ); ?>.</p>

			<?php
			return;
		}
	}

if (function_exists('wp_list_comments')):
	//WP 2.7 Comment Loop
	if ( have_comments() ) : ?>

		<?php if ( ! empty($comments_by_type['comment']) ) :
		$count = count($comments_by_type['comment']); ?>
		<h2 id="comments"><?php printf( __( '%s responses to' ), $count ); ?> &#8220;<?php the_title(); ?>&#8221; <a href="<?php bloginfo( 'comments_rss2_url' ); ?>" title="RSS link" target="_blank"><img id="commentsrss" title="Subscribe to comments" src="<?php bloginfo( 'stylesheet_directory' ); ?>/images/comments_rss.png" alt="" /></a></h2>

		<ul class="commentslist">
			<?php wp_list_comments( 'type=comment&callback=darkwood_comment' ); ?>
		</ul>
		<?php endif; ?>

		<div class="navigation">
			<div class="alignleft"><?php previous_comments_link(); ?></div>
            <div class="alignright"><?php next_comments_link(); ?></div>
        </div>

		<?php if ( ! empty( $comments_by_type['pings'] ) ) :
		$countp = count( $comments_by_type['pings'] ); ?>
		<h2 id="trackbacks" class="block"><?php printf( __( '%s Trackbacks &#47; Pingbacks' ), $countp ); ?></h2>

		<ul class="commentslist">
            <?php wp_list_comments( 'type=pings&callback=darkwood_ping' ); ?>
		</ul>
		<?php endif; ?>

	<?php else : // this is displayed if there are no comments so far ?>
		<?php if ('open' == $post->comment_status) :
			// If comments are open, but there are no comments.
		else : ?><p class="nocomments"></p>
		<?php endif;
	endif;
else:
	//WP 2.6 and older Comment Loop
	/* This variable is for alternating comment background */

	/* This variable is for alternating comment background */
	$oddcomment = 'class="alt" ';
?>

<!-- You can start editing here. -->
<?php if ($comments) : ?>
	<div class="clr">&nbsp;</div>
	<h3 id="comments" class="block"><?php comments_number( __('No responses to'), __('One response to'), __('% responses to') );?>
 &#8220;<?php the_title(); ?>&#8221; <a href="<?php bloginfo('comments_rss2_url'); ?>" title="RSS link"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/ico/rss.gif" alt="<?php _e('RSS icon'); ?>" /></a></h3>
	<ul class="list-4">

	<?php foreach ($comments as $comment) : ?>

		<li <?php echo $oddcomment; ?>id="comment-<?php comment_ID(); ?>">
            <div class="com-wrappertop"></div>
            <div id="com-wrapper">
        	<div class="com-header">
                <?php if ( function_exists( 'get_avatar' ) ) { echo get_avatar( $comment, '48' ); } ?>

                <p class="tp">
                    <span><?php comment_author_link(); ?></span>
                    <?php if ($comment->comment_approved == '0') : ?>
                    <em><?php _e('Your comment is awaiting moderation'); ?>.</em>
                    <?php endif; ?>

                    <span class="commentmetadata">
                    	<a href="#comment-<?php comment_ID(); ?>" title=""><?php printf( __( '%1$s at %2$s' ), get_comment_time( __( 'F jS, Y' ) ), get_comment_time( __( 'H:i' ) ) ); ?></a>
                    	<?php edit_comment_link( __( 'Edit' ), '&nbsp;&nbsp;', '' ); ?>
                    </span>
				</p>

            </div>
			<?php comment_text(); ?>
            </div>
            <div class="com-wrapperbottom"></div>
		</li>

	<?php
		/* Changes every other comment to a different class */
		$oddcomment = ( empty( $oddcomment ) ) ? 'class="alt" ' : '';
	?>

	<?php endforeach; /* end for each comment */ ?>

	</ul>

 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments"></p>
		<?php endif; ?>
	<?php endif; ?>
<?php endif; ?>
</div>

<?php if ( 'open' == $post->comment_status ) : ?>

<?php comment_form(); ?>

<?php endif; // if you delete this the sky will fall on your head ?>