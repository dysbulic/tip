<?php

//admin interface
	
add_action('admin_menu', 'duotone_add_page');
function duotone_add_page() {
	add_theme_page(__('Theme Options'), __('Theme Options'), 'edit_theme_options', 'theme-options', 'duotone_do_page');
}
function duotone_do_page() { 
	register_setting('duotoneoptions', 'duotone-background');
	register_setting('duotoneoptions', 'background_color_manual');
	if( esc_attr( $_POST['action'] ) == 'update' ) {
		echo '<div id="message" class="updated fade"><p><strong>'.__('Options saved.').'</strong></p></div>';
		check_admin_referer('duotoneoptions-options');
		delete_option('background_color');
		if(esc_attr( $_POST['duotone-background'] ) == 'manual') {
				$background_color = preg_replace('/(#[0-9a-fA-F]{6})+/', '$1', esc_attr( $_POST['background_color_manual'] ) );
				update_option('background_color', $background_color);
		}
	}
	?>
	<div class="wrap">
		<h2><?php _e('Duotone Theme Options'); ?></h2>
		<form method="post">
			<?php settings_fields( 'duotoneoptions' ); ?>		
			<p>
				<input type="radio" name="duotone-background" value="manual" id="choice_manual" <?php if(get_option('background_color') != '') echo 'checked="checked"'; ?> />
				<label for="background_color_manual" onclick="jQuery('#choice_manual').attr('checked','checked');"><?php _e('Manual Background Color:'); ?> <input type="text" onclick="tgt=document.getElementById('background_color_manual'); jQuery('#choice_manual').attr('checked','checked'); colorSelect(tgt,'background_color_manual');return false;" name="background_color_manual" id="background_color_manual" value="<?php echo esc_attr( get_option('background_color') ); ?>" /> <small><?php printf(__('Any CSS color (%s or %s or %s)'), '<code>red</code>', '<code>#FF0000</code>', '<code>rgb(255, 0, 0)</code>'); ?></small></label>
				<input type="hidden" name="pick1" id="pick1" value="<?php esc_attr_e( 'Background Color' ); ?>"></input>
				<div id="colorPickerDiv" style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;visibility:hidden;"> </div>
				
			</p>
			<p>
				<input type="radio" name="duotone-background" value="automatic" id="choice_automatic" <?php if(get_option('background_color') == '') echo 'checked="checked"'; ?>/>
				<label for="automatic" onclick="jQuery('#choice_automatic').attr('checked','checked');"><?php echo __('Automatic Background Color'); ?></label>
			</p>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Update Background Color') ?>" />
		</p>
	
		</form>
	</div>
<?php
}

add_action('admin_head', 'duotone_admin_head');
function duotone_admin_head() { ?>
	<script type="text/javascript" src="../wp-includes/js/colorpicker.js"></script>
	<script type='text/javascript'>
	// <![CDATA[
		function pickColor(color) {
			ColorPicker_targetInput.value = color;
			kUpdate(ColorPicker_targetInput.id);
		}
		function PopupWindow_populate(contents) {
			contents += '<br /><p style="text-align:center;margin-top:0px;"><input type="button" value="<?php esc_attr_e( 'Close Color Picker' ); ?>" onclick="cp.hidePopup(\'prettyplease\')"></input></p>';
			this.contents = contents;
			this.populated = false;
		}
		function PopupWindow_hidePopup(magicword) {
			if ( magicword != 'prettyplease' )
				return false;
			if (this.divName != null) {
				if (this.use_gebi) {
					document.getElementById(this.divName).style.visibility = "hidden";
				}
				else if (this.use_css) {
					document.all[this.divName].style.visibility = "hidden";
				}
				else if (this.use_layers) {
					document.layers[this.divName].visibility = "hidden";
				}
			}
			else {
				if (this.popupWindow && !this.popupWindow.closed) {
					this.popupWindow.close();
					this.popupWindow = null;
				}
			}
			return false;
		}
		function colorSelect(t,p) {
			if ( cp.p == p && document.getElementById(cp.divName).style.visibility != "hidden" )
				cp.hidePopup('prettyplease');
			else {
				cp.p = p;
				cp.select(t,p);
			}
		}
		function PopupWindow_setSize(width,height) {
			this.width = 162;
			this.height = 210;
		}

		var cp = new ColorPicker();
		// ]]>
		</script>
<?php }


add_action('save_post', 'duotone_save_postdata');
function duotone_save_postdata( $post_id ) {
  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times
  if ( !wp_verify_nonce( $_POST['duotone_nonce'], plugin_basename(__FILE__) )) {
    return $post_id;
  }

  if ( 'page' == $_POST['post_type'] ) {
    if ( !current_user_can( 'edit_page', $post_id ))
      return $post_id;
  } else {
    if ( !current_user_can( 'edit_post', $post_id ))
      return $post_id;
  }

  // OK, we're authenticated: we need to find and save the data
	delete_post_meta($post_id, 'single_background_color');
	if(trim($_POST['single_background_color']) != '')
		add_post_meta($post_id, 'single_background_color', $_POST['single_background_color']);

  	return $mydata;
}