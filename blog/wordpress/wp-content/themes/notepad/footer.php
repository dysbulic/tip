<?php
/**
 * @package WordPress
 * @subpackage Notepad
 */
?>
</div>
<!--/wrapper -->

<div id="footer">
	<p class="generator">
		<a href="http://wordpress.com/" rel="generator">Get a free blog at WordPress.com</a>
	</p>
	<p class="credits">
		<?php printf( __( 'Theme: %1$s by %2$s.' ), 'Notepad', '<a href="http://www.ndesign-studio.com/" rel="designer">N. Design</a>' ); ?>
	</p>
</div>
<!--/footer -->

</div>
<!--/pagewrapper -->

<script type="text/javascript">
/* <![CDATA[ */
	document.getElementById("s").onfocus = function() {
		if (this.value == "<?php _e( 'Search...','notepad-theme' ); ?>")
			this.value = "";
	}
	document.getElementById("s").onblur = function() {
		if (this.value == '')
			this.value = "<?php _e( 'Search...','notepad-theme' ); ?>";
	}
/* ]]> */
</script>

<?php wp_footer(); ?>
</body>
</html>