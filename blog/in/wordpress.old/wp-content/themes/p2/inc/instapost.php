<?php
/**
 * Class: P2_Instapost_Overrides
 * Authors: apeatling
 * Started: Sept 30, 2011
 *
 * Override base Instapost functionality to it integrates seamlessly with P2.
 */
class P2_Instapost_Overrides {
	/**
	 * @static P2_Instapost_Overrides::init()
	 *
	 * Instantiate the class.
	 */
	static function init() {
		static $instance = false;

		if ( !$instance )
			$instance = new P2_Instapost_Overrides;

		return $instance;
	}
	
	/**
	 * @static P2_Instapost_Overrides::init()
	 *
	 * Instantiate the class.
	 */
	public function __construct() {
		add_action( 'template_redirect', array( 'P2_Instapost_Overrides', 'css' ) );
		add_action( 'wp_footer', array( 'P2_Instapost_Overrides', 'js' ), 100 );
		
		add_filter( 'instapost_output', array( 'P2_Instapost_Overrides', 'render_output' ), 10, 2 );
		add_filter( 'instapost_preview_response', array( 'P2_Instapost_Overrides', 'prepend_response_to' ) );
		add_filter( 'instapost_published_response', array( 'P2_Instapost_Overrides', 'prepend_response_to' ) );
	}
	
	static function css() {
		wp_enqueue_style( 'p2_instapost_css', get_template_directory_uri() . '/style-instapost.css' );	
	}

	static function js() {
		wp_enqueue_script( 'p2_instapost_js', get_template_directory_uri() . '/js/instapost.js' );	
		wp_print_scripts();
	}
	
	static function render_output( $output, $the_post ) {
		// Only use this for previewing posts.
		if ( $the_post->post_status != 'draft' )
			return $output;
			
		/* TODO: This should somehow use entry.php and $the_post object to return the post content like published posts does. */
		$author = get_userdata( $the_post->post_author );
		
		$output = '
			<li id="prologue-' . $the_post->ID . '">
				<a href="" title="">
					' . get_avatar( $author->user_email, 48 ) . '
				</a>
				
				<h4>
					<a href="http://' . $author->user_url . '" title="' . $author->display_name . '">' . $author->display_name . '</a>
					
					<span class="meta">
						' . __( 'Post Preview' ) . '
					</span>
				</h4>
				
				<div id="content-' . $the_post->ID . '" class="postcontent">
					' . apply_filters( 'the_content', $the_post->post_content ) . '
				</div>
			</li>';
			
		return $output;
	}

	static function prepend_response_to( $response ) {
		$response['prepend_to'] = 'ul#postlist';
		
		return $response;
	}
}

/* Temporary until we active instapost across all p2s. */
if ( function_exists( 'instapost_render' ) )
	add_action( 'init', array( 'P2_Instapost_Overrides', 'init' ) );