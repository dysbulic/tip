</div>

<div id="footer">

	<div id="miscellany">
	
	<?php if (function_exists('dynamic_sidebar')) { echo "<div class=\"widgets\">\n"; } ?>

	<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-widgets')) :  // Footer widgets ?>

		<?php if (!is_search()) { include ( get_template_directory() . "/searchform.php"); } ?>

	<?php endif; // end widgets if ?>
	
	<?php if (function_exists('dynamic_sidebar')) { echo "</div>\n"; } ?>

	</div>


	<div id="about">
		<div class="navigation">
		<?php $_SERVER['REQUEST_URI']  = preg_replace("/(.*?).php(.*?)&(.*?)&(.*?)&_=/","$2$3",$_SERVER['REQUEST_URI']); ?>
		<?php if ( is_single() ) : ?>
			<div class="left"><?php previous_post_link( '%link', '<span>' . _x( '&laquo;', 'Previous post link', 'tarski' ) . '</span> %title' ); ?></div>
			<div class="right"><?php next_post_link( '%link', '%title <span>' . _x( '&raquo;', 'Next post link', 'tarski' ) . '</span>' ); ?></div>
		<?php elseif ( ! is_singular() ) : ?>
			<div class="left"><?php next_posts_link( '<span>&laquo;</span> ' . __( 'Previous Entries', 'tarski' ) . '' ); ?></div>
			<div class="right"><?php previous_posts_link( '' . __( 'Next Entries', '' ) . ' <span>&raquo;</span>' ); ?></div>
		<?php endif; ?>
		</div>		
	</div>


	<div id="theme-info">
		<div class="primary content">
			<p><span class="designer"><?php printf( __( 'Theme: %1$s by %2$s and %3$s.' ), 'Tarski', 'Ben Eastaugh', 'Chris Sternal-Johnson' ); ?> </span><span class="generator"><a href="http://wordpress.com/" rel="generator">Get a free blog at WordPress.com</a></span></p>
		</div>
		<div class="secondary">
			<p><a class="feed" title="<?php echo esc_attr( sprintf( __( 'Subscribe to the %s feed' ), get_bloginfo( 'name', 'display' ) ) ); ?>" href="<?php echo get_bloginfo_rss('rss2_url'); ?>"><?php _e('Subscribe to feed');?>.</a></p>
		</div>
	</div>


</div>

</div><?php wp_footer(); ?></body></html>
