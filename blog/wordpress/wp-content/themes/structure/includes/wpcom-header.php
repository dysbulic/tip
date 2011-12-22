<?php
/**
 * @package WordPress
 * @subpackage Structure
 */

define('HEADER_TEXTCOLOR', '999');
define('HEADER_IMAGE', '');
define('HEADER_IMAGE_WIDTH', 960);
define('HEADER_IMAGE_HEIGHT', 120);

function structure_admin_header_style() {
?>
<style type="text/css">
#headimg {
	height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
	width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
	<?php if ( st_option( 'dark_scheme' ) ) : ?>
	background: #000;
	<?php else : ?>
	background: #fff;
	<?php endif; ?>
	margin-bottom: 1em;
	overflow: hidden;
}
#headimg h1 {
	padding: 47px 10px 0 10px;	
}
#headimg h1 a {
	color: #<?php echo HEADER_TEXTCOLOR; ?>;
	font: 36px/36px Georgia, "Times New Roman", Times, serif;
	font-style: italic;
	font-weight: bold;
	text-decoration: none;
}
#headimg #desc {
	display: none;
}
</style>
<?php
}

add_custom_image_header( 'header_style', 'structure_admin_header_style' );

function header_style() { ?>
	<style type="text/css">
	<?php if ( '' != get_header_image() ) { ?>
		#header h1 a, #header h1 a:visited {
			background: url(<?php header_image(); ?>) no-repeat top;
		}
	<?php } ?>
	<?php if ( 'blank' != get_header_textcolor() ) { ?>
		#header h1 a, #header h1 a:visited {
			color: #<?php header_textcolor(); ?> !important;
		}
	<?php } else { /* hide header text */ ?>
		#header h1 a span {
			text-indent: -1000em !important;
		}
	<?php } ?>
	</style>
<?php }