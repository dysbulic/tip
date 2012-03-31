== Changelog ==

= 1.1 Oct 5 2011 =
* Fix translatable attribute values that were not using esc_attr_e()
* Fix category highlighting in the menu
* Set svn:eol-style on TXT files
* Fix get_the_author() escaping
* Set wider content_width for full-width template and image template
* Hide RTL byline instead of off-screen positioning to prevent horizontal scrollbars
* Tag updates for style.css
* Make sure wp_print_styles does not run for wp-admin
* Avoid running update_option on init from front-end
* Add sanitize_title to category lookup to avoid clash with special characters
* Show category chooser in Theme Options to sites that set a category in the past, but remove for newer blogs
* Enable post formats for asides and galleries
* Misc layout and markup fixes; trim trailing whitespace