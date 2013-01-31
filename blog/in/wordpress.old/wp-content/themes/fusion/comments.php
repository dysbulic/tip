<?php
/**
 * @package WordPress
 * @subpackage Fusion
 */
?>
<?php if ( post_password_required() ) : ?>
	<p class="error"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'fusion' ); ?></p>
<?php return; endif; ?>

<?php if ( $comments || comments_open() ) : ?>
<?php
	/* Count the totals */
	$numPingBacks	= 0;
	$numComments	= 0;

	/* Loop throught comments to count these totals */
	foreach ( $comments as $comment )
		if ( get_comment_type() != "comment" ) $numPingBacks++; else $numComments++;
?>

<div id="post-extra-content">

	<ul class="secondary-tabs">
		<li><a href="#comments"><span><span><?php _e( 'Comments', 'fusion' ); echo ' ( '.$numComments.' )'; ?></span></span></a></li>
		<?php if ( $numPingBacks > 0 ) : ?><li><a href="#trackbacks"><span><span><?php _e( 'Trackbacks', 'fusion' ); echo ' ( '.$numPingBacks.' )';?></span></span></a></li><?php endif; ?>
	</ul>
	
	<div id="commentlist">
		<ol id="comments">

		<?php if ( have_comments() ) : ?>

			<?php wp_list_comments( 'type=comment&callback=list_comments' ); ?>

		<?php else : // this is displayed if there are no comments so far ?>
			
			<?php if ( 'open' == $post->comment_status ) : ?>
			
				<li><?php _e( 'Leave a Comment', 'fusion' ); ?></li>

			<?php else : // comments are closed ?>

				<li><?php _e( 'Comments are closed.', 'fusion' ); ?></li>

			<?php endif; ?>
		
		<?php endif; ?>
	
		</ol>

		<?php if ( ( $numPingBacks > 0 ) ) : ?>
		<ol id="trackbacks">
			<?php wp_list_comments( 'type=pings&callback=list_pings' ); ?>
		</ol>
		<?php endif; ?>

		<div class="commentnavi">
			<div class="commentpager">
				<?php paginate_comments_links(); ?>
			</div>
		</div>
			
		<?php comment_form(); ?>		
		
	</div>

</div>
<?php endif; ?>