<?php
/**
 * The template for displaying Comments.
 *
 * @package WordPress
 * @subpackage The Morning After
 */
 
// Do not delete these lines

if ( !empty( $_SERVER['SCRIPT_FILENAME'] ) && 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) )
	die ( 'Please do not load this page directly. Thanks!' );

	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e( 'This post is password protected. Enter the password to view comments.', 'woothemes' ); ?></p>
	
	<?php return; } ?>
	
	<?php $comments_by_type = &separate_comments( $comments ); ?>
	
	<!-- You can start editing here. -->
	<div id="comments">
	
		<?php if ( is_page() && ! comments_open() && ! have_comments() ) : // hode the Discussion heading when comment is disabled on a page ?>
		<?php else : ?>
			<div id="commenthead">
				<h2 class="post_comm"><?php _e( 'Discussion','woothemes' );?></h2>
			</div>
		<?php endif; ?>
	
		<?php if ( have_comments() ) : ?>
	
			<?php if ( ! empty( $comments_by_type['comment'] ) ) : ?>
	
				<h3><?php comments_number(__( 'No Responses', 'woothemes' ), __( 'One Response', 'woothemes' ), __( '% Responses', 'woothemes' ) );?> <?php _e( 'to', 'woothemes' ); ?> &#8220;<?php the_title(); ?>&#8221;</h3>
	
				<ol id="commentlist">
	
					<?php wp_list_comments( 'avatar_size=40&callback=custom_comment&type=comment' ); ?>
	
				</ol>
	
				<div class="navigation clear-fix">
					<div class="fl"><?php previous_comments_link(); ?></div>
					<div class="fr"><?php next_comments_link(); ?></div>
				</div>
			
			<?php endif; ?>
	
	
			<?php if ( ! empty( $comments_by_type['pings'] ) ) : ?>
	
				<h3 id="pings"><?php _e( 'Trackbacks/Pingbacks', 'woothemes' ); ?></h3>
	
				<ol id="pinglist">
					<?php wp_list_comments( 'type=pings&callback=list_pings' ); ?>
				</ol>
	
			<?php endif; ?>
	
		<?php else : // this is displayed if there are no comments so far ?>
	
			<?php if ( comments_open() ) : ?>
				<!-- If comments are open, but there are no comments. -->
				<h3 class="mast3"><?php _e( 'No comments yet.', 'woothemes' ); ?></h3>
	
			<?php else : // comments are closed ?>
				<!-- If comments are closed. -->
				<h3 class="nocomments mast3"><?php _e( 'Comments are closed.', 'woothemes' ); ?></h3>
	
			<?php endif; ?>
	
		<?php endif; ?>
	
	</div><!-- end #comments -->
		
	<?php if ( comments_open() ) : ?>
	
		<?php comment_form(); ?>

	<?php endif; // if you delete this the sky will fall on your head ?>
