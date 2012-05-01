<?php

	/* REQUIRE THE CORE CLASS */
	require_once ('titan-admin.php');
	/*
		Class Definition
	*/
	if (!class_exists('Titan')) {
		class Titan extends JestroCore {

			/* PHP4 Constructor */
			function Titan () {

				/* SET UP THEME SPECIFIC VARIABLES */
				$this->themename = "Titan";
				$this->themeurl = "http://thethemefoundry.com/titan/";
				$this->shortname = "T";
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
					array(
						"name" => __('Header Follow Links <span>Customize the top right links</span>'),
						"type" => "subhead"
					),

					array(
						"name" => __('Twitter username'),
						"id" => $this->shortname."_twitter",
						"desc" => __('Enter your Twitter username.'),
						"std" => '',
						"type" => "text"
					),

					array(
						"name" => __('Disable Twitter'),
						"id" => $this->shortname."_twitter_toggle",
						"desc" => __('Not hip to Twitter? That\'s cool, just check this box.'),
						"std" => '',
						"type" => "checkbox"
					),

					array(
						"name" => __('Navigation <span>Control your top navigation menu</span>'),
						"type" => "subhead"
					),

					array(
						"name" => __('Hide all pages'),
						"id" => $this->shortname."_hide_pages",
						"desc" => __('Check this box to hide all pages'),
						"std" => '',
						"type" => "checkbox"
					),

					array(
						"name" => __('Exclude specific pages'),
						"id" => $this->shortname."_pages_to_exclude",
						"desc" => __('The page ID of pages you do not want displayed in your navigation menu. Use a comma-delimited list, eg. 1,2,3'),
						"std" => '',
						"type" => "text"
					),

					array(
						"name" => __('Hide all categories'),
						"id" => $this->shortname."_hide_cats",
						"desc" => __('Check this box to hide all categories.'),
						"std" => '',
						"type" => "checkbox"
					),

					array(
						"name" => __('Exclude specific categories'),
						"id" => $this->shortname."_categories_to_exclude",
						"desc" => __('The category ID of pages you do not want displayed in your navigation menu.. Use a comma-delimited list, eg. 1,2,3'),
						"std" => '',
						"type" => "text"
					),

					array(
						"name" => __('Hide home navigation menu item'),
						"id" => $this->shortname."_hide_home",
						"desc" => __('Check this box if you are using a static page as your homepage instead of your blog (the default). The extra <em>Home</em> menu item will be removed.'),
						"std" => '',
						"type" => "checkbox"
					),

					array(
						"name" => __('Homepage Notice <span>Display a notice on your homepage</span>'),
						"type" => "subhead"
					),

					array(
						"name" => __('Enable homepage notice'),
						"id" => $this->shortname."_custom_notice",
						"desc" => __('Check this box to use a custom notice on the home page.'),
						"std" => '',
						"type" => "checkbox"
					),

					array(
						"name" => __('Custom notice'),
						"id" => $this->shortname."_notice_content",
						"desc" => __('The content of your custom notice.'),
						"std" => '',
						"type" => "textarea",
						"options" => array( "rows" => "3", "cols" => "50")
					)

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
			function excludedCategories () {
				return get_option($this->shortname.'_categories_to_exclude');
			}
			function hidePages () {
				return get_option($this->shortname.'_hide_pages');
			}
			function hideCategories () {
				return get_option($this->shortname.'_hide_cats');
			}
			function hideHome () {
				return get_option($this->shortname.'_hide_home');
			}

			/* FOLLOW LINKS */
			function feedState() {
				return get_option($this->shortname.'_feed_state');
			}
			function twitter() {
				return get_option($this->shortname.'_twitter');
			}
			function twitterToggle() {
				return get_option($this->shortname.'_twitter_toggle');
			}

			/* HOMEPAGE NOTICE */
			function noticeState() {
				return get_option($this->shortname.'_custom_notice');
			}
			function noticeContent() {
				return stripslashes(wp_filter_post_kses(wpautop(get_option($this->shortname.'_notice_content'))));
			}

		}
	}

	/* SETTING EVERYTHING IN MOTION */
	if (class_exists('Titan')) {
		$titan = new Titan();
	}