<?php
/**
 * Makes a custom Widget for displaying a recipe appropriate to the current color scheme
 *
 * Learn more: http://codex.wordpress.org/Widgets_API#Developing_Widgets
 *
 * @package WordPress
 * @subpackage Fruit Shake
 * @since Fruit Shake 1.0
 */
class Fruit_Shake_Recipe_Widget extends WP_Widget {

	/**
	 * Constructor
	 *
	 * @return void
	 **/
	function Fruit_Shake_Recipe_Widget() {
		$widget_ops = array( 'classname' => 'widget_fruit_shake_recipe', 'description' => __( 'Use this widget to display a recipe in your sidebar that perfectly matches your fruitylicious blog.', 'fruit-shake' ) );
		$this->WP_Widget( 'widget_fruit_shake_recipe', __( 'Fruit Shake Recipe', 'fruit-shake' ), $widget_ops );
		$this->alt_option_name = 'widget_fruit_shake_recipe';

		add_action( 'save_post', array(&$this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache' ) );
	}

	/**
	 * Outputs the HTML for this widget.
	 *
	 * @param array An array of standard parameters for widgets in this theme
	 * @param array An array of settings for this widget instance
	 * @return void Echoes it's output
	 **/
	function widget( $args, $instance ) {
		$cache = wp_cache_get( 'widget_fruit_shake_recipe', 'widget' );

		if ( !is_array( $cache ) )
			$cache = array();

		if ( ! isset( $args['widget_id'] ) )
			$args['widget_id'] = null;

		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract( $args, EXTR_SKIP );

		// Get the current fruit scheme
		$fruit_scheme = fruit_shake_current_fruit_scheme();
		$fruit_title = mb_convert_case( $fruit_scheme, MB_CASE_TITLE );
		$fruit_title = str_replace( '-', ' ', $fruit_title );

		// Whatever the current fruit scheme is, set a title
		$recipe_title =  sprintf( __( '%s Shake', 'fruit-shake' ), $fruit_title );


		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( $recipe_title, 'fruit-shake' ) : $instance['title'], $instance, $this->id_base);

		echo $before_widget;
		echo $before_title;
		echo $title; // Can set this with a widget option, or omit altogether
		echo $after_title;

		// Whatever the current fruit scheme is, set a recipe
		switch ( $fruit_scheme ) {
			case 'blueberry':
				?>
				<ol>
				   <li><?php _e( 'Large handful of blueberries (you could say 2/3 cup if you’d rather)', 'fruit-shake' ); ?></li>
				   <li><?php _e( '1/2 banana', 'fruit-shake' ); ?></li>
				   <li><?php _e( '2/3 cup apple juice (or apple-berry blend)', 'fruit-shake' ); ?></li>
				   <li><?php _e( 'Dash of cardamom', 'fruit-shake' ); ?></li>
				   <li><?php _e( '3-4 ice cubes', 'fruit-shake' ); ?></li>
				</ol>
				<?php
				break;

			case 'dragon-fruit':
				?>
				<ol>
				   <li><?php _e( '1 dragon fruit', 'fruit-shake' ); ?></li>
				   <li><?php _e( 'Handful of strawberries (you could say 1/2 cup if you’d rather)', 'fruit-shake' ); ?></li>
				   <li><?php _e( '2/3 cup tangerine juice', 'fruit-shake' ); ?></li>
				   <li><?php _e( '3-4 ice cubes', 'fruit-shake' ); ?></li>
				   <li><?php _e( '(if dragon fruit is on the sour end, add a swirl of agave nectar)', 'fruit-shake' ); ?></li>
				</ol>
				<?php
				break;

			case 'brown-banana':
				?>
				<ol>
				   <li><?php _e( '1 overripe banana, broken in half', 'fruit-shake' ); ?></li>
				   <li><?php _e( '2 scoops chocolate ice cream', 'fruit-shake' ); ?></li>
				   <li><?php _e( '1 scoop chocolate protein powder (optional)', 'fruit-shake' ); ?></li>
				   <li><?php _e( 'Splash of vanilla soy milk', 'fruit-shake' ); ?></li>
				</ol>
				<?php
				break;

			default:
				?>
				<ol>
				   <li><?php _e( '2 average bananas (or 1 really large one)', 'fruit-shake' ); ?></li>
				   <li><?php _e( '1/2 cup plain, vanilla, or banana yogurt', 'fruit-shake' ); ?></li>
				   <li><?php _e( '1 scoop vanilla protein powder (optional)', 'fruit-shake' ); ?></li>
				   <li><?php _e( 'Splash of milk', 'fruit-shake' ); ?></li>
				   <li><?php _e( 'Dab of honey', 'fruit-shake' ); ?></li>
				   <li><?php _e( '3-4 ice cubes', 'fruit-shake' ); ?></li>
				</ol>
				<?php
				break;
		}

		?>
			<p><?php _e( 'Combine in a blender until smooth and enjoy!', 'fruit-shake' ); ?></p>
		<?php

		echo $after_widget;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set( 'widget_fruit_shake_recipe', $cache, 'widget' );
	}

	/**
	 * Deals with the settings when they are saved by the admin. Here is
	 * where any validation should be dealt with.
	 **/
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = (int) $new_instance['number'];
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions['widget_fruit_shake_recipe'] ) )
			delete_option( 'widget_fruit_shake_recipe' );

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete( 'widget_fruit_shake_recipe', 'widget' );
	}

}
add_action( 'widgets_init', create_function( '', "register_widget( 'Fruit_Shake_Recipe_Widget' );" ) );