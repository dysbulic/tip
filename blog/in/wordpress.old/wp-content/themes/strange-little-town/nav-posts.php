<?php
/**
 * Paged navigation.
 * Used everywhere multiple posts are displayed.
 *
 * @package WordPress
 * @subpackage StrangeLittleTown
 */
?>

<nav id="nav-posts" class="paged-navigation contain">
	<h1 class="assistive-text"><?php _e( 'Posts navigation', 'strange-little-town' ); ?></h1>
	<div class="nav-older"><?php next_posts_link( __( '&larr; Older Posts', 'strange-little-town' ) ); ?></div>
	<div class="nav-newer"><?php previous_posts_link( __( 'Newer Posts &rarr;', 'strange-little-town' ) ); ?></div>
</nav>