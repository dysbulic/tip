<?php
if ( function_exists('register_sidebar') )
    register_sidebar( array(
        'name'=> __('Wide Sidebar', 'vigilance'),
        'id' => 'sidebar-1',
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ) );
    register_sidebar( array(
        'name'=> __('Left Sidebar', 'vigilance'),
        'id' => 'sidebar-2',
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ) );
    register_sidebar( array(
        'name'=> __('Right Sidebar', 'vigilance'),
        'id' => 'sidebar-3',
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ) );
?>