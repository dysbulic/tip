<?php

$themecolors = array(
	'bg' => '0a0a0a',
	'border' => '0a0a0a',
	'text' => 'cccccc',
	'link' => 'cc3300'
);

$content_width = 450;

add_theme_support( 'automatic-feed-links' );

if ( function_exists('register_sidebar') )
    register_sidebar();

/*
Plugin Name: Nice Categories
Plugin URI: http://txfx.net/2004/07/22/wordpress-conversational-categories/
Description: Displays the categories conversationally, like: Category1, Category2 and Category3
Version: 1.5.1
Author: Mark Jaquith
Author URI: http://txfx.net/
*/

function the_nice_category($normal_separator = ', ', $penultimate_separator = ' and ') {
    $categories = get_the_category();
   
      if (empty($categories)) {
        _e('Uncategorized');
        return;
    }

    $thelist = '';
        $i = 1;
        $n = count($categories);
        foreach ($categories as $category) {
            $category->cat_name = $category->cat_name;
                if (1 < $i && $i != $n) $thelist .= $normal_separator;
                if (1 < $i && $i == $n) $thelist .= $penultimate_separator;
            $thelist .= '<a href="' . get_category_link($category->cat_ID) . '" title="' . sprintf(__("View all posts in %s"), $category->cat_name) . '">'.$category->cat_name.'</a>';
                     ++$i;
        }
    echo apply_filters('the_category', $thelist, $normal_separator);
}

// Custom comments
function sunburn_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<div id="div-comment-<?php comment_ID() ?>" class="vcard">
		<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
		<?php if ($comment->comment_approved == '0') : ?>
			<p style = "color: red;">Your comment is awaiting moderation.</p>
			<?php endif; ?>
			
			<?php comment_text(); ?>
			
			<p class="comment-meta commentmetadata"><span class="fn"><?php comment_author_link() ?></span> - <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>" title=""><?php comment_date() ?> at <?php comment_time() ?></a></p>
	<div class="reply">
		<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	</div>
<?php
}