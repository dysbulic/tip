<?php
/**
 * @package WordPress
 * @subpackage Brand New Day
 */
?>
</div> <!-- end div.wrapper -->
<div class="footer-wrapper">
	<div class="sun"></div>
	<div class="trees"></div>
	<div class="stars"></div>
	<div class="footer-grass"></div>
	<div class="footer-sidebar-wrapper">
		<div class="footer-sidebar-list-wrapper">
		<?php get_sidebar('footer1');
		      get_sidebar('footer2');
		      get_sidebar('footer3');
		      get_sidebar('footer4'); ?>
		</div>
	</div>
	<div class="footer-colophon">
		<a href="http://wordpress.org/" rel="generator">Proudly powered by WordPress</a>
		<?php printf( __( 'Theme: %1$s by %2$s.' ), 'Brand New Day', '<a href="http://carolinemoore.net/" rel="designer">Caroline Moore</a>' ); ?>
	</div>
</div>
<?php wp_footer(); ?>

</body>
</html>


