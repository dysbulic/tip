<?php
/**
 * @package WordPress
 * @subpackage Spectrum
 */
?>

			<?php
				// Check to see if the user wants to hide the optional tag cloud
				$options = get_option( 'spectrum_theme_options' );
				if ( $options[ 'spectrumtagcloud' ] == 0 ) :
			?>
			<div id="tag-cloud">
				<div class="sub-title">
					<h4><strong><?php _e( 'Tag Cloud', 'spectrum' ); ?></strong></h4>
				</div>
				<ul>
					<?php
						$tags = get_tags( array( 'orderby' => 'count', 'order' => 'DESC' ) );
						foreach ( ( array ) $tags as $tag ) {
						?>
						<?php echo '<li><a href="' . get_tag_link ( $tag->term_id ) . '" rel="tag">' . $tag->name . '</a></li>'; ?>
					<?php } ?>
				</ul>
			</div><!-- #tag-cloud -->
			<?php endif; ?>

			<div id="before-footer"></div><!-- #before-footer -->

		</div>
		<!--[if IE]>
			</div>
		<![endif]-->
	</div>
	<div id="footer">

		<div id="copyright">
			<p><a href="http://wordpress.org/" rel="generator">Proudly powered by WordPress</a> | <?php printf( __( 'Theme: %1$s by %2$s.' ), 'Spectrum', '<a href="http://www.ignacioricci.com/" rel="designer">Ignacio Ricci</a>' ); ?></p>
			<?php wp_footer(); ?>
		</div>
	</div>

</body>
</html>