<?php // Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p class="nocomments"><?php _e( 'This post is password protected. Enter the password to view comments.', 'iceburgg' ); ?></p>
<?php
	return;
}
?>
<div id="commentarea">
  <?php if ($comments) : ?>
  <div id="precomments">
    <div class="comleft">
		<h3 id="comments">
			<?php
				printf( _n( 'One Response to &#8220;%2$s&#8221;', '%1$s Responses to &#8220;%2$s&#8221;', get_comments_number(), 'iceburgg' ),
				number_format_i18n( get_comments_number() ), get_the_title() );
			?>
		</h3>
    </div>
    <div class="comright">
      <?php post_comments_feed_link(); ?>
    </div>
    <div style="clear: both"></div>
  </div>
  <div style="clear: both"></div>
  <ol class="commentlist">
  
  <?php wp_list_comments( array( 'callback'=>'iceburgg_comment', 'avatar_size'=>48 ) ); ?>
  </ol>

  <div class="navigation">
  	<div class="alignleft"><?php previous_comments_link() ?></div>
  	<div class="alignright"><?php next_comments_link() ?></div>
  </div>
	
  <?php else : // this is displayed if there are no comments so far ?>
  <?php if (comments_open()) : ?>
  <!-- If comments are open, but there are no comments. -->
  <?php else : // comments are closed ?>
  <!-- If comments are closed. -->
  <?php endif; ?>
  <?php endif; ?>
  <?php if (comments_open()) : ?>
  <div style="clear: both"></div>

<?php comment_form(); ?>
  
  <?php endif; // if you delete this the sky will fall on your head ?>
</div>
