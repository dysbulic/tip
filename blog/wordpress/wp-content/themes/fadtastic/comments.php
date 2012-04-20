<?php // Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p class="nocomments"><?php _e( "This post is password protected. Enter the password to view comments." ); ?></p>
<?php
	return;
}

if ( comments_open() ) : ?>

<?php comment_form(); ?>

<?php endif; 

function fadtastic_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
<div <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="div-comment-<?php comment_ID(); ?>">
	<div class="comment_wrapper" id="comment-<?php comment_ID(); ?>">
		<div class="comment_content">
			<?php if ($comment->comment_approved == '0') : ?>
			<em><?php _e( 'Your comment is awaiting moderation.', 'fadtastic' ); ?></em>
			<?php else : ?>
			<?php comment_text(); ?>
			<?php endif; ?>
		</div>
	</div>
	<div class="comment_details comment-author vcard">
		<p class="comment-meta commentmetadata comment_meta"><strong class="fn"><?php comment_author_link(); ?></strong><br /><a href="#comment-<?php comment_ID(); ?>" title=""><?php comment_date(); ?></a><br /></p>
		<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>		
	</div>
	<div class="clear"></div>
	<div class="reply">
		<?php comment_reply_link( array_merge( $args, array( 'add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
	</div>
	<div class="clear comment_bottom"></div>
<?php
}


if ( have_comments() ) : ?>
	<h2 id="comments" class="top_border"><?php comments_number( __( 'Leave a Reply' ), __( 'One Response' ), __( '% Responses' ) ); ?> to &#8220;<?php the_title(); ?>&#8221;</h2>
	<p class="author"><img src="<?php bloginfo( 'stylesheet_directory' ); ?>/images/icon_rss.gif" alt="RSS Feed for <?php bloginfo( 'name' ); ?>" border="0" class="vertical_align" /> <strong><?php post_comments_feed_link( 'Comments RSS Feed' ); ?></strong></p>

	<div class="commentlist">
	<?php wp_list_comments( array( 'callback'=>'fadtastic_comment', 'style'=>'div' ) ); ?>
	</div>
	
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link(); ?></div>
		<div class="alignright"><?php next_comments_link(); ?></div>
	</div>
	
	<br />

	<?php if ( comments_open() ) : ?> 
		<p><a href="#respond"><?php _e( "Where's The Comment Form?", 'fadtastic' ); ?></a></p>
	<?php else : ?>
		<p class="nocomments"><?php _e( 'Comments are closed.', 'fadtastic' ); ?></p>
	<?php endif; ?>
<?php endif; ?>
