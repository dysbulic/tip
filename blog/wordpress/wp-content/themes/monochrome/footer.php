	<div id="site-info"><a href="http://wordpress.com/" rel="generator">Get a free blog at WordPress.com</a> | <?php printf( __( 'Theme: %1$s by %2$s.', 'monochrome' ), 'monochrome', '<a href="http://www.mono-lab.net/" rel="designer">mono-lab</a>' ); ?></div>
	</div>

</div><!-- #wrapper end -->

<?php $options = get_option('mc_options'); if ($options['pagetop']) : ?>
<div id="return_top">
 <a href="#wrapper"></a>
</div>
<?php endif; ?>

<script type="text/javascript">
	/* <![CDATA[ */
	var menu=new menu.dd("menu");
	menu.init("menu","menuhover");
	/* ]]> */
</script>
<?php wp_footer(); ?>
</body>
</html>