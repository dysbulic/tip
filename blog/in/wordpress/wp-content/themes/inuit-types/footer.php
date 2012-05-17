<?php
/**
 * @package Inuit Types
 */
?>

			<div class="footer <?php if ( !get_option('inuitypes_right_sidebar') ) { echo 'footer_left'; } else { echo 'footer_right'; } ?>">

                <div class="fl"><a href="http://wordpress.com/" rel="generator">Get a free blog at WordPress.com</a></div>

				<div class="fr"><?php printf( __( 'Theme: %1$s by %2$s.', 'it' ), 'Inuit Types', '<a href="http://bizzartic.com" rel="designer">BizzArtic</a>' ); ?></div>

                <div class="fix"><!----></div>

            </div>


    </div>

</div>

<div class="fix"><!----></div>

<?php wp_footer(); ?>

<!--[if lt IE 7]>
<script src="<?php bloginfo( 'template_directory' ); ?>/library/js/IE7.js" type="text/javascript"></script>
<![endif]-->

</body>
</html>