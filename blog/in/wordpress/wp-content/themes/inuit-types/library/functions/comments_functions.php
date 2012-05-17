<?php

// Add a new default gravatar
function newgravatar ($avatar_defaults) {
    $myavatar = get_bloginfo('template_directory') . '/images/gravatar.png';
    $avatar_defaults[$myavatar] = "Inuitypes";
    return $avatar_defaults;
}
add_filter( 'avatar_defaults', 'newgravatar' );

$bm_trackbacks = array();
$bm_comments = array();

function split_comments( $source ) {

    if ( $source ) foreach ( $source as $comment ) {

        global $bm_trackbacks;
        global $bm_comments;

        if ( $comment->comment_type == 'trackback' || $comment->comment_type == 'pingback' ) {
            $bm_trackbacks[] = $comment;
        } else {
            $bm_comments[] = $comment;
        }
    }
}

function inuitypes_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
 
        <li <?php comment_class('wrap'); ?> id="comment-<?php comment_ID() ?>">
		
		    <div class="meta-left">
			
				<div class="meta-wrap">
					
					<?php echo get_avatar( $comment, 48 ); ?><br />
					
					<p class="authorcomment"><?php comment_author_link() ?><br /></p>
					
					<p><small><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><?php comment_date( get_option( 'date_format' ) ); ?></a></small></p>
				
				</div>
				
			</div>
			    
			<div class="text-right <?php if (1 == $comment->user_id) $oddcomment = "authcomment"; echo $oddcomment; ?>">
				
				<?php comment_text() ?>
					
				<?php if ($comment->comment_approved == '0') : ?>
					
				<p><em><?php _e( 'Your comment is awaiting moderation.', 'it' ); ?></em></p>
					
				<?php endif; ?>
				
			</div>
		

		<span class="comm-reply">
		
		<?php comment_reply_link(array_merge( $args, array('add_below' => 'comment', 'reply_text' => __( 'Reply', 'it' ), 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
		
		<?php edit_comment_link( __('Edit', 'it' ), '&nbsp;-&nbsp;&nbsp;', ''); ?>
		
		</span>

		<div class="fix"></div>
		
	    </li>
 
<?php
        }
?>