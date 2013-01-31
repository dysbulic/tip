=== NotesIL ===

Two-column theme with different color schemes. Based on the original design of http://notes.co.il by Jordan Lewinsky and built on top of the Sandbox theme.

== Changelog ==

= 0.6 - Oct 5 2011 =
* Set svn:eol-style and svn:mime-type for all files
* Properly escape translatable attribute values with esc_attr_e()
* the_post should always be called in the loop

= 0.5 - Aug 1 2011 =
* Theme colors updates
* Add missing textdomain call
* Fix screenshot size
* Footer credits: add trailing period for consistent look
* Send the comment object to get_avatar() directly instead of using the email address

= 0.4 - Jan 6 2011 =
* Layout: fix several clearing issues and correct image centering for captions
* Change sandbox_ prefix to notesil_
* Change post date from abbr to anchor to provide permalink
* Simplify footer link
* Fix comment password check
* Meta content-type before title in header
* Fix image size and layout on single image template
* Add search query to search template for no results output
* CSS code cleanup
* Missing semi-colons, spacing, remove trailing spaces, &c.
* Content width changed to 530
* svn:eol-style native for PO
* Add this readme file :)

= 0.3 - Dec 3 2010 =
* bloginfo('url') to echo home_url()
* comments_rss() to get_post_comments_feed_link()
* Use PNG for screenshot (and smaller size)
* Misc code clean up
* Enable automatic-feed-links() and add_custom_background()
* Enable comment_form()
* get_bloginfo('wpurl') to get_option( 'siteurl' )
* get_option('home') to home_url()
* get_settings('home') to home_url()
* get_option('siteurl') to site_url()

= 0.2 Apr 11 2010 =
* Fix tag list spacing
* Re-organize comment functions and fix RTL CSS
* Misc cleanup

= 0.1 Mar 28 2010 =
* Original upload