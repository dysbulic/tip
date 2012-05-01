<?php
/**
 * The template for top banner.
 *
 * @package WordPress
 * @subpackage The Morning After
 */
?>

<?php $morningafter_options = morningafter_get_theme_options();  ?>

<div id="topbanner" class="column span-14" style="background-image:url(<?php header_image(); ?>)">
	
		<?php
			$prefix = esc_html( $morningafter_options['prefix_heading'] );
			if ( $prefix ) {
				echo '<div class="pagetitle_prefix">' . $prefix . '</div>';
			}
		?>
			
		<?php	
			if ( is_home() || is_front_page() ) {
				$homepage_heading = esc_html( stripslashes( $morningafter_options['homepage_heading'] ) );
				if ( $homepage_heading ) {
					echo '<div class="pagetitle">' . $homepage_heading . '</div>';
				}
			}elseif ( is_404() ){
				$four_o_four_heading = esc_html( stripslashes( $morningafter_options['four_o_four_heading'] ) );
				if ( $four_o_four_heading ) {
					echo '<div class="pagetitle">' . $four_o_four_heading . '</div>';
				}
			}elseif ( is_archive() ){
				$archives_heading = esc_html( stripslashes( $morningafter_options['archives_heading'] ) );
				if ( $archives_heading ) {
					echo '<div class="pagetitle">' . $archives_heading . '</div>';
				}
			}elseif ( is_author() ){
				$author_archive_heading = esc_html( stripslashes( $morningafter_options['author_archive_heading'] ) );
				if ( $author_archive_heading ) {
					echo '<div class="pagetitle">' . $author_archive_heading . '</div>';
				}
			}elseif ( is_attachment() || is_single() ){
				$single_post_heading = esc_html( stripslashes( $morningafter_options['single_post_heading'] ) );
				if ( $single_post_heading ) {
					echo '<div class="pagetitle">' . $single_post_heading . '</div>';
				}
			}elseif ( is_search() ){
				$search_results_heading = esc_html( stripslashes( $morningafter_options['search_results_heading'] ) );
				if ( $search_results_heading ) {
					echo '<div class="pagetitle">' . $search_results_heading . '</div>';
				}
			}elseif ( is_page() ){
				$title = get_the_title();
				if ( $title ) {
					echo '<div class="pagetitle">' . $title . '</div>';
				}
			}else{
				$index_heading = esc_html( stripslashes( $morningafter_options['index_heading'] ) );
				if ( $index_heading ) {
					echo '<div class="pagetitle">' . $index_heading . '</div>';
				}
			}
		?>
</div>