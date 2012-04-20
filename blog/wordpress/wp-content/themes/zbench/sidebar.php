<?php
/**
 * @package WordPress
 * @subpackage zBench
 *
 * Sidebar(s)
 */


/* Grab theme options from database */
$options = get_option( 'zbench_theme_options' );


/**
 * Left hand sidebar
 * Only needed for "sidebar-content-sidebar" theme layout
 */
if ( 'sidebar-content-sidebar' == $options['theme_layout'] ) : ?>
	<div id="sidebar-two" class="sidebar">
		<div class="sidebar-border active" id="secondary-widget-area">
			<div class="sidebar-inner">
			<?php

			/* Start Secondary Widget area */
			if ( !dynamic_sidebar( 'secondary-widget-area' ) ) : ?>

			<h3><?php _e( 'Categories', 'zbench' ); ?></h3>
			<div>
				<?php wp_dropdown_categories( 'show_option_none=' . __( 'Select category', 'zbench' ) . '&hierarchical=true&orderby=name' ); ?>
				<script type="text/javascript">
				/* <![CDATA[ */
					var dropdown = document.getElementById("cat");
					function onCatChange() {
						if ( dropdown.options[dropdown.selectedIndex].value > 0 ) {
							location.href = "<?php echo home_url(); ?>/?cat="+dropdown.options[dropdown.selectedIndex].value;
						}
					}
					dropdown.onchange = onCatChange;
				/* ]]> */
				</script>
			</div>

			<h3><?php _e( 'Recent Posts', 'zbench' ); ?></h3>
			<div>
				<ul>
					<?php wp_get_archives( 'type=postbypost&limit=5' ); ?>
				</ul>
			</div>

			<?php endif; /* End Sidebar Widget Area */ ?>

			</div>
		</div>
	</div>
<?php endif; /* End of left hand sidebar */ ?>

</div><?php /* End #maincontent */


/**
 * Main sidebar
 */
?>
<div id="sidebar" class="sidebar">
	<?php /* Load RSS icon */ ?>
	<div id="rssfeed"><a href="<?php bloginfo( 'rss2_url' ); ?>"><span><?php _e( 'RSS feed', 'zbench' ); ?></span></a></div>

	<?php

	/* Featured Widget Area -  Only needed for "content-sidebar-sidebar" or "sidebar-sidebar-content" theme layouts */
	if ( 'sidebar-sidebar-content' == $options['theme_layout'] OR 'content-sidebar-sidebar' == $options['theme_layout'] ) : ?>
	<div class="sidebar-border active" id="featured-widget-area">
		<div class="sidebar-inner">
		<?php
			if ( !dynamic_sidebar( 'featured-widget-area' ) ) : ?>
			<h3><?php _e( 'Blogroll', 'zbench' ); ?></h3>
			<div>
				<ul>
					<?php wp_list_bookmarks( 'title_li=&categorize=0' ); ?>
				</ul>
			</div>
			<?php endif; ?>
		</div>
	</div>
	<?php endif;


	/* Primary Widget area */ ?>
	<div class="sidebar-border active" id="primary-widget-area">
		<div class="sidebar-inner">
		<?php
			if ( !dynamic_sidebar( 'primary-widget-area' ) ) : ?>

			<h3><?php _e( 'Archives', 'zbench' ); ?></h3>
			<div>
				<ul>
					<?php wp_get_archives( 'type=monthly' ); ?>
				</ul>
			</div>

			<?php endif; /* End Primary Widget Area */ ?>

		</div>
	</div>

	<?php
	/* Secondary Widget area - only needed for "sidebar-sidebar-content" and "content-sidebar-sidebar" theme layouts */
	if ( 'sidebar-sidebar-content' == $options['theme_layout'] OR 'content-sidebar-sidebar' == $options['theme_layout'] ) : ?>
	<div class="sidebar-border active" id="secondary-widget-area">
		<div class="sidebar-inner">
			<?php
			if ( !dynamic_sidebar( 'secondary-widget-area' ) ) : ?>

			<h3><?php _e( 'Archives', 'zbench' ); ?></h3>
			<div>
				<ul>
					<?php wp_get_archives( 'type=monthly' ); ?>
				</ul>
			</div>

			<h3><?php _e( 'Blogroll', 'zbench' ); ?></h3>
			<div>
				<ul>
					<?php wp_list_bookmarks( 'title_li=&categorize=0' ); ?>
				</ul>
			</div>
			<?php endif; ?>

		</div>
	</div>
	<?php endif; ?>


</div>
