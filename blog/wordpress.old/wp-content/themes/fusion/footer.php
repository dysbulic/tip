<?php
/**
 * @package WordPress
 * @subpackage Fusion
 */
?>
	</div>
	<!-- /side wrap -->

	</div>
	<!-- /mid column wrap -->

</div>
<!-- /main wrapper -->

<div class="clearcontent"></div>

<?php if ( is_active_sidebar( 'footer-widget-area' ) ) : ?>

		<ul id="footer-widgets" class="widgetcount-<?php $sidebars_widgets = wp_get_sidebars_widgets(); $wcount=count( $sidebars_widgets[ 'footer-widget-area' ] ); print $wcount; ?>">
			<?php dynamic_sidebar( 'footer-widget-area' ); ?>
		</ul>

		<div class="clear"></div>
		
<?php endif; ?>

<div id="footer">

	<p>
		<a href="http://wordpress.com/" rel="generator">Get a free blog at WordPress.com</a>
		<?php printf( __( 'Theme: %1$s by %2$s.', 'fusion' ), 'Fusion', '<a href="http://digitalnature.ro/projects/fusion" rel="designer">digitalnature</a>' ); ?>
	</p>
</div>
<!-- /footer -->

</div>
<!-- /page -->

</div>

</div>
<!-- /page wrappers -->

<?php wp_footer(); ?>
</body>
</html>