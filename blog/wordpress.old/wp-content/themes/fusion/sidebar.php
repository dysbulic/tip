<?php
/**
 * @package WordPress
 * @subpackage Fusion
 */
?>

<div id="sidebar">

	<div id="sidebar-wrap1">

	<div id="sidebar-wrap2">

		<ul id="sidelist">
			<?php if ( is_404() || is_category() || is_day() || is_month() || is_year() || is_search() || is_paged() ) { ?>
				<li class="infotext">
			<?php /* If this is a 404 page */ if ( is_404() ) { ?>
			<?php /* If this is a category archive */ } elseif ( is_category() ) { ?>
						<p><?php printf( __( 'You are currently browsing the archives for the %s category.', 'fusion' ), single_cat_title( '', false ) ); ?></p>

			<?php /* If this is a yearly archive */ } elseif ( is_day() ) { ?>
						<p><?php printf( __( 'You are currently browsing the archives for %s', 'fusion' ), get_the_time( __( 'l, F jS, Y', 'fusion' ) ) ); ?></p>

			<?php /* If this is a monthly archive */ } elseif ( is_month() ) { ?>
						<p><?php printf( __( 'You are currently browsing the archives for %s', 'fusion' ), get_the_time( __( 'F, Y', 'fusion' ) ) ); ?></p>

			<?php /* If this is a yearly archive */ } elseif ( is_year() ) { ?>
						<p><?php printf( __( 'You are currently browsing the archives for the year %s', 'fusion' ), get_the_time( __( 'Y', 'fusion' ) ) ); ?></p>

			<?php /* If this is a monthly archive */ } elseif ( is_search() ) { ?>
						<p><?php printf( __( 'You have searched the archives for %s.', 'fusion' ), '<strong>' . get_search_query() . '</strong>' ); ?></p>

			<?php /* If this is a monthly archive */ } elseif ( isset( $_GET['paged'] ) && !empty( $_GET['paged'] ) ) { ?>
						<p><?php _e( 'You are currently browsing the archives.', 'fusion' ); ?></p>
			<?php } ?>
				</li>
			<?php } ?>

				<?php if ( ! dynamic_sidebar( 'primary-widget-area' ) ) : ?>

				<li>
				<div class="widget widget_search">
					<form method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
						<fieldset>
						<input type="text" name="s" id="searchbox" class="searchfield" />
						<input type="submit" value="<?php esc_attr_e( 'Search', 'fusion' ); ?>" class="searchbutton" />
						</fieldset>
					</form>
				</div>
				</li>

				<li>
				<div class="widget widget_categories">

					<ul>
						<?php wp_list_categories( 'show_count=1&title_li=' ); ?>
					</ul>

				</li>

				<li class="box-wrap" id="box-archives">
					<div class="box">
					<h2 class="title"><?php _e( 'Archives', 'fusion' ); ?></h2>
					<div class="inside">
						<ul>
						<?php wp_get_archives( 'type=monthly&show_post_count=1' ); ?>
						</ul>
					</div>
					</div>
				</li>

				<?php endif; ?>
		</ul>

	</div>
	<!-- /sidebar 2nd container -->

	</div>
	<!-- /sidebar 1st container -->

</div>
<!-- /sidebar -->

		<?php
			$options = fusion_get_theme_options();
			$current_layout = $options[ 'theme_layout' ];

			$secondary_widget_area_layouts = array( 'content-sidebar-sidebar', 'sidebar-sidebar-content' );
			if ( in_array( $current_layout, $secondary_widget_area_layouts ) ) :
		?>
		<div id="sidebar2">

		<div id="sidebar2-wrap">
			<ul id="sidelist2">
			<?php if ( ! dynamic_sidebar( 'secondary-widget-area' ) ) : ?>

				<li class="widget widget_links">
					<h3 class="title"><?php _e( 'Meta', 'fusion' ); ?></h3>
					<ul>
						<?php wp_register(); ?>
						<li><?php wp_loginout(); ?></li>
						<?php wp_meta(); ?>
					</ul>
				</li>

			<?php endif; ?>
			</ul>
		</div>

		</div><!-- /2nd sidebar -->
		<?php endif; // ends the check for the current layout that determins if the third column is visible ?>