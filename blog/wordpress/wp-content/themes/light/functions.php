<?php

$themecolors = array(
	'bg' => 'ffffff',
	'text' => 'e5e5e5',
	'link' => 'aac8e0'
);

// actually 483px
$content_width = 480;

if ( function_exists( 'register_sidebars' ) )
	register_sidebars( 1 );

add_theme_support( 'automatic-feed-links' );

add_custom_background();

register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'light' ),
) );

function light_page_menu() { // fallback for primary navigation ?>
<ul class="navigation">
	<?php if ( is_home() || is_front_page() ) { $pg_li .="current_page_item"; } ?>
	<li class="<?php echo $pg_li; ?>"><a href="<?php bloginfo( 'url' ); ?>" title="Blog"><span><?php _e( 'Blog' ); ?></span></a></li>
	<?php wp_list_pages( 'depth=1&title_li=&link_before=<span>&link_after=</span>' ); ?>
</ul>
<?php }

function light_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>

<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
<div id="div-comment-<?php comment_ID() ?>">
      <div class="commentname comment-author vcard">
	<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
        <span class="fn"><?php comment_author_link()?></span>
        on
        <span class="comment-meta commentmetadata">
        <?php comment_date() ?>
        <?php edit_comment_link(__("Edit This"), ''); ?>
        </span>
      </div>
      <?php if ($comment->comment_approved == '0') : ?>
      <em><?php _e('Your comment is awaiting moderation.'); ?></em>
      <?php endif; ?>
      <div class='commenttext'>
        <div class="commentp">
          <?php comment_text();?>
        </div>
      </div>
      <div class="reply">
	<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
      </div>
</div>
<?php
}