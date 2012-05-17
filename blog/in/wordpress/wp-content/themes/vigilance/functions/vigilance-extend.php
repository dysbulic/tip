<?php
	/* REQUIRE THE CORE CLASS */
	require_once( 'vigilance-admin.php' );
	/*
		Class Definition
	*/
	if (!class_exists('Vigilance')) {
		class Vigilance extends JestroCore {

			/* PHP4 Constructor */
			function Vigilance () {

				/* SET UP THEME SPECIFIC VARIABLES */
				$this->themename = "Vigilance";
				$this->themeurl = "http://thethemefoundry.com/vigilance/";
				$this->shortname = "V";
				$directory = get_bloginfo('stylesheet_directory');
				/*
					OPTION TYPES:
					- checkbox: name, id, desc, std, type
					- radio: name, id, desc, std, type, options
					- text: name, id, desc, std, type
					- colorpicker: name, id, desc, std, type
					- select: name, id, desc, std, type, options
					- textarea: name, id, desc, std, type, options
				*/
				$this->options = array(

					array(  "name" => __('Navigation', 'vigilance'),
									"type" => "subhead",
									'hidden' => true),

					array(  "name" => __('Exclude specific pages', 'vigilance'),
									"id" => $this->shortname."_pages_to_exclude",
									"desc" => __('The page ID of pages you do not want displayed in your navigation menu. Use a comma-delimited list, eg. 1,2,3.<br /><br /><em>Note:</em> this theme now offers a fully customizable menu. To enable go to <em>Appearance</em> &rarr; <em>Menus</em>.', 'vigilance'),
									"std" => '',
									"type" => "text"),

					array(  "name" => __('Color Scheme', 'vigilance'),
									"type" => "subhead"),

					array(  "name" => __( 'Base Color Scheme', 'vigilance' ),
									"id" => $this->shortname."_base_color_scheme",
									"desc" => __( 'Choose your base color scheme', 'vigilance' ),
									"std" => "Light",
									"type" => "radio",
									"options" => array( "Light" => __( 'Light', 'vigilance' ),
																			"Dark"  =>  __( 'Dark', 'vigilance' ) ) ),

					array(  "name" => __('Customize colors', 'vigilance'),
									"id" => $this->shortname."_background_css",
									"desc" => __('If enabled your theme will use the colors you choose below.', 'vigilance'),
									"std" => "Disabled",
									"type" => "select",
									"options" => array( "Disabled" => __('Disabled', 'vigilance'),
																			"Enabled"  =>  __('Enabled', 'vigilance'))),

					array(  "name" => __('Background color', 'vigilance'),
									"id" => $this->shortname."_background_color",
									"desc" => __('Use hex values and be sure to include the leading #.', 'vigilance'),
									"std" => "#a39c8a",
									"type" => "colorpicker"),

					array(  "name" => __('Border color', 'vigilance'),
									"id" => $this->shortname."_border_color",
									"desc" => __('Use hex values and be sure to include the leading #.', 'vigilance'),
									"std" => "#9a927f",
									"type" => "colorpicker"),

					array(  "name" => __('Link color', 'vigilance'),
									"id" => $this->shortname."_link_color",
									"desc" => __('Use hex values and be sure to include the leading #.', 'vigilance'),
									"std" => "#772124",
									"type" => "colorpicker"),

					array(  "name" => __('Link hover color', 'vigilance'),
									"id" => $this->shortname."_hover_color",
									"desc" => __('Use hex values and be sure to include the leading #.', 'vigilance'),
									"std" => "#58181b",
									"type" => "colorpicker"),

					array(  "name" => __('Disable hover background images', 'vigilance'),
									"id" => $this->shortname."_image_hover",
									"desc" => __('Check this box if you use custom link colors and do not want the default red showing when a user hovers over the comments bubble or the sidebar menu items.', 'vigilance'),
									"std" => "false",
									"type" => "checkbox"),

					array(  "name" => __('Alert Box', 'vigilance'),
									"type" => "subhead"),

					array(  "name" => __('Alert Box on/off switch', 'vigilance'),
									"id" => $this->shortname."_alertbox_state",
									"desc" => __('Toggle the alert box on or off.', 'vigilance'),
									"std" => "Off",
									"type" => "select",
									"options" => array( "Off" => __('Off', 'vigilance'),
																			"On" => __('On', 'vigilance'))),

					array(  "name" => __('Alert Title', 'vigilance'),
									"id" => $this->shortname."_alertbox_title",
									"desc" => __('The heading for your alert.', 'vigilance'),
									"std" => "Your Alert Header",
									"type" => "text"),

					array(  "name" => __('Alert Message', 'vigilance'),
									"id" => $this->shortname."_alertbox_content",
									"desc" => __('A special alert message that is shown on the front page of your site.', 'vigilance'),
									"std" => "Your alert message goes here.",
									"type" => "textarea",
									"options" => array( "rows" => "8",
																			"cols" => "70")),

				);
				parent::JestroCore();
			}

			/*
				ALL OF THE FUNCTIONS BELOW
				ARE BASED ON THE OPTIONS ABOVE
				EVERY OPTION SHOULD HAVE A FUNCTION

				THESE FUNCTIONS CURRENTLY JUST
				RETURN THE OPTION, BUT COULD BE
				REWRITTEN TO RETURN DIFFERENT DATA
			*/

			/* NAVIGATION FUNCTIONS */
			function excludedPages () {
				return get_option($this->shortname.'_pages_to_exclude');
			}

			/* ALERTBOX FUNCTIONS */
			function alertboxState() {
				return get_option($this->shortname.'_alertbox_state');
			}
			function alertboxTitle() {
				return stripslashes(wp_filter_post_kses(get_option($this->shortname.'_alertbox_title')));
			}
			function alertboxContent() {
				return stripslashes(wp_filter_post_kses(wpautop(get_option($this->shortname.'_alertbox_content'))));
			}

			/* CSS FUNCTIONS */
			function baseColorScheme() {
				return get_option( $this->shortname.'_base_color_scheme' );
			}
			function backgroundCss() {
				return get_option( $this->shortname.'_background_css' );
			}
			function backgroundColor() {
				return $this->check_hash( get_option( $this->shortname.'_background_color' ), '#a39c8a' );
			}
			function borderColor() {
				return $this->check_hash( get_option( $this->shortname.'_border_color' ), '#9a927f' );
			}
			function linkColor() {
				return $this->check_hash( get_option( $this->shortname.'_link_color' ), '#772124' );
			}
			function hoverColor() {
				return $this->check_hash( get_option( $this->shortname.'_hover_color' ), '#58181b' );
			}
			function imageHover() {
				return get_option( $this->shortname.'_image_hover' );
			}
			/*
				For CSS color options filter output to make sure data is in the correct format
				Must be hexadecimal with 3 or 6 characters
				If not valid, fall back to default value
			*/
			function check_hash( $value, $default_value ) {
				// Validate data format
				if ( !preg_match( '/^#?([a-f0-9]{3}){1,2}$/i', $value ) ) {
					if ( empty( $default_value ) )
						$default_value = '#000000';
					return $default_value;
				}
				// If color value doesn't have a hash, add it
				if ( false === strpos( $value, '#' ) )
					$value = '#' . $value;
				return $value;
			}
		}
	}
	/* SETTING EVERYTHING IN MOTION */
	if (class_exists('Vigilance')) {
		$vigilance = new Vigilance();
	}

?>