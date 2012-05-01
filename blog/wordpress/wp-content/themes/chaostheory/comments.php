<?php
/**
 * @package WordPress
 * @subpackage ChaosTheory
 */
?>
<div class="comments">

<?php
	if ( 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']) )
		die ( 'Please do not load this page directly. Thanks!' );
	if ( post_password_required() ) :
?>
	<div class="nopassword"><?php _e( 'This post is password protected. Enter the password to proceed.', 'chaostheory' ); ?></div>
	</div>
<?php
		return;
	endif;


function chaostheory_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
	<div id="div-comment-<?php comment_ID(); ?>">
	<ul class="comment-meta">
		<li class="comment-author vcard">
		<div class="comment-avatar"><?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?></div>
		<span class="fn"><?php comment_author_link(); ?></span></li>
		<?php printf( __( '<li>Posted %1$s at %2$s</li> <li><a href="%3$s" title="Permalink to this comment">Permalink</a></li>', 'chaostheory' ),
			get_comment_date(),
			get_comment_time(),
			'#comment-' . get_comment_ID() );
			?> <li><?php edit_comment_link( __( '(Edit)', 'chaostheory' ), ' ', '' ); ?> <?php comment_reply_link(array_merge( $args, array( 'add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth'])) ); ?></li>
	</ul>
	<div class="comment-content">
		<?php if ($comment->comment_approved == '0' ) : ?><span class="unapproved"><?php _e( 'Your comment is awaiting moderation.', 'chaostheory' ); ?></span><?php endif; ?>
		<?php comment_text(); ?>
	</div>
	</div>
<?php
}

function chaostheory_ping($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
	<div id="div-comment-<?php comment_ID(); ?>">
	<div class="comment-meta">
		<?php printf( __( 'By %1$s on %2$s at %3$s', 'chaostheory' ),
			get_comment_author_link(),
			get_comment_date( 'd M Y' ),
			get_comment_time( 'g:i a' ));
		?>
		<?php edit_comment_link( __( '(Edit)', 'chaostheory' ), ' ', '' ); ?>
	</div>
	<div class="trackback-content">
	<div class="comment-mod"><?php if ($comment->comment_approved == '0' ) _e( '<em>Your trackback/pingback is awaiting moderation.</em>', 'chaostheory' ); ?></div>
	<?php comment_text(); ?>
	</div>
	</div>
<?php
}

?>

<?php if ( have_comments() ) : ?>

<?php /* NUMBERS OF PINGS AND COMMENTS */
$ping_count = $comment_count = 0;
foreach ( $comments as $comment )
	get_comment_type() == "comment" ? ++$comment_count : ++$ping_count;
?>

<?php if ( $comment_count ) : ?>

	<h3 class="comment-header" id="numcomments"><?php printf( __($comment_count > 1 ? '%d Comments' : 'One Comment', 'chaostheory' ), $comment_count); ?></h3>
	<ol id="comments" class="commentlist">
		<?php wp_list_comments(array( 'callback'=>'chaostheory_comment', 'avatar_size'=>16, 'type'=>'comment' ) ); ?>
	</ol>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link(); ?></div>
		<div class="alignright"><?php next_comments_link(); ?></div>
	</div>
	<br />

<?php endif; /* if ( $comment_count ) */ ?>

<?php if ( $ping_count ) : ?>

	<h3 class="comment-header" id="numpingbacks"><?php printf( __($ping_count > 1 ? '%d Trackbacks/Pingbacks' : 'One Trackback/Pingback', 'chaostheory' ), $ping_count); ?></h3>
	<ol id="pingbacks" class="commentlist">
		<?php wp_list_comments(array( 'callback'=>'chaostheory_ping', 'type'=>'pings' ) ); ?>
	</ol>

<?php endif /* if ( $ping_count ) */ ?>

<?php endif /* if ( $comments ) */ ?>

<!-- formcontainer around #commentform -->

<?php if ( comments_open() ) : ?>

	<?php comment_form(); ?>

<?php endif /* if ( 'open' == $post->comment_status ) */ ?>

</div>