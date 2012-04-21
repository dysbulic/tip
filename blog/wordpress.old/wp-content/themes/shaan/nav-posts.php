<?php
/**
 * Paged navigation.
 * Used everywhere multiple posts are displayed.
 *
 * @package Shaan
 */
?>

<nav id="nav-posts" class="paged-navigation">
	<h1 class="assistive-text"><?php _e( 'Posts navigation', 'shaan' ); ?></h1>
	<div class="nav-older"><?php next_posts_link( __( '&larr; Older Posts', 'shaan' ) ); ?></div>
	<div class="nav-newer"><?php previous_posts_link( __( 'Newer Posts &rarr;', 'shaan' ) ); ?></div>
</nav>