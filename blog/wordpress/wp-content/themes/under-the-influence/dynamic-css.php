<?php
 /**
 * @package WordPress
 * @subpackage Under_the_Influence
 */

global $options;

foreach ($options as $value) {
	if (array_key_exists('id',$value)) {
		if (get_option( $value['id'] ) === FALSE) {
			$$value['id'] = $value['std'];
		} else {
			$$value['id'] = get_option( $value['id'] );
		}
	}
}

if ( ! get_query_var( 'preview' ) ) {
	header('Content-type: text/css');
} else {
	echo '<style type="text/css" media="screen">';
}

$headerimage = get_header_image();
if ($headerimage == ""){
	$headerstyle = 1;
	}
else{
	$headerstyle = 2;
	}

$accent = $uti_accent_color;
$mutedaccent = $uti_muted_accent;
$column = $uti_column;
$columnwidth = $uti_column_width;
$sidebarwidth = $uti_sidebar_width;
$contentwidth = $uti_column_width+40;
$headerdesign = $uti_header_design;
$maxpicwidth = $uti_pic_width;
$maxpicheight = $uti_pic_width;
$allwidth = $uti_site_width;
$allwidth2 = $uti_site_width-14;
$footer = $uti_footer_cell4;


echo '
/*This css file manages the css rules for dynamic content.*/

#title_box, .corner_tr, .corner_tl, .entry .navigation a:hover, .entry .navigation a:focus, .navigation a:hover, .navigation a:focus, #wp-calendar a:hover, #wp-calendar a:focus {
	background-color: '.$accent.';
	color: #fff;
	}

#page {
	border-top: 3px solid '.$accent.';
	}

a:hover, .postmetadata a:hover, .tags a:hover, .entry a:hover, #credits a:hover, .commentbox cite a:hover, .commentmetadata a:hover,
a:focus, .postmetadata a:focus, .tags a:focus, .entry a:focus, #credits a:focus, .commentbox cite a:focus, .commentmetadata a:focus {
	color: '.$accent.';
	}

a:hover img.centered, a:hover img.alignright, a:hover img.alignleft, a:hover img, .gallery a:hover img, .wp-caption a:hover img, #wp-calendar #today,
a:focus img.centered, a:focus img.alignright, a:focus img.alignleft, a:focus img, .gallery a:focus img, .wp-caption a:focus img, #wp-calendar #today {
	border: 1px solid '.$accent.';
	}

.postmetadata a, .tags a, #credits a, .commentmetadata a, .read_more {
	color: '.$mutedaccent.';
	}

#footer {
	border-top: 2px solid '.$accent.';
	border-bottom: 1px solid '.$accent.';
	}

.sticky {
	border-top: 2px dotted '.$accent.';
	border-bottom: 1px dotted '.$accent.';
	}

';

if ($headerstyle == 1) {

	if ($headerdesign == 2) {
		echo '
		/*active when header design 2 is selected and no custom header image is set*/
		#header h1, #header h1 a, #header h1 a:visited {
		font-family: Candara, "Trebuchet MS", Calibri, Helvetica, Arial, sans-serif;
		font-size: 1.7em;
		text-align: center;
		padding-bottom: 3px;
		color: #fff;
	}
	.description {
		text-align: center;
		color: #fff;
		font-size: 1em;
		}
	#header {
		height: 120px;
		padding-bottom: 20px;
		width: '.$allwidth.'px;
		margin: 0 auto;}';
	}
	else {
	echo '
	/*active when header design 1 is selected and no custom header image is set*/
	#header h1, #header h1 a, #header h1 a:visited {
		color: #333;
		font-size: 2em;
		text-align: left;
	}
	.description {
		color: '.$accent.';
		font-size: 1.5em;
		font-style: italic;
		margin-top: -5px;
		}
	#header {
		margin: 0 auto;
		width: '.$allwidth2.'px;
		padding: 40px 0 40px 20px;
	}
	';
	}
} else {
		echo '
		/*active when header is replaced with custom image */
		#header h1, #header h1 a, #header h1 a:visited {
		padding-top: 10px;
		font-size: 2.3em;
		text-align: left;
	}
	.description {
		font-size: 1.5em;
		padding-left: 10px;
		font-style: italic;
		margin: -5px;
		}
	#header {
		height: 85px;
		padding: 35px 0 40px 20px;
		width: '.$allwidth2.'px;
		margin: 0 auto;}';
	}
if ($column == "on") {
echo '
	.post, .search .page {
		width: 370px;
		float: left;
	}
	#content, .navigation_box{
		padding-left: 6px;
		width: 794px;
	}
	';
}

else {
	echo '
	.post, .search .page {
		width: '.$columnwidth.'px;
		margin: auto;
	}
	#content, .navigation_box {
		width: '.$contentwidth.'px;
	}
	';}
echo '
#three_column img.alignright, #three_column img.alignleft, #three_column .wp-caption.alignleft img, #three_column .wp-caption.alignleft, #three_column .wp-caption.alignright img, #three_column .wp-caption.alignright {
	max-width: '.$maxpicwidth.'px !important;
	height: auto;
	max-height: '.$maxpicheight.'px !important
	}

.singlepage .post, .singlepage .page {
	width:'.$columnwidth.'px;
	margin: auto;
	}
';
echo '
#sidebar {
	width: '.$sidebarwidth.'px;
	}

#content_container, #footer, #page_list {
	width: '.$allwidth.'px;
	}

	';


	if ($footer == "on") {
echo '
	#footer .cell {
	width: 20.5%;
	}
	.cell-4 {
	padding: 20px;
	float: left;
	}
	#footer a[rel="generator"], #footer a[rel="designer"] {
	color: '.$accent.';
	}
	';
}

else {
	echo '
	#footer .cell {
	width: 28%;
	}
	.cell-4 {
		width: 0px;
		visibility: hidden;
		}
	';}

if ( get_query_var( 'preview' ) ) {
	echo '</style>';
}
?>