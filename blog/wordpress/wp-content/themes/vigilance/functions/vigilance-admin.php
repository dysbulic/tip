<?php
	/*
		Class Definition
	*/
	if (!class_exists('JestroCore')) {
		class JestroCore {

			var $themename = "Jestro";
			var $themeurl = "http://thethemefoundry.com/";
			var $shortname = "jestro_themes";
			var $options = array();

			/* PHP4 Compatible Constructor */
			function JestroCore () {
				add_action('init', array(&$this, 'printAdminScripts'));
				add_action('admin_menu', array(&$this, 'addAdminPage'));
			}

			/* Add Custom CSS & JS */
			function printAdminScripts () {
				if ( $_GET['page'] == basename(__FILE__) ) {
					wp_enqueue_style('jestro', get_bloginfo('template_directory').'/functions/stylesheets/admin.css');
					wp_enqueue_script('jestro', get_bloginfo('template_directory').'/functions/javascripts/admin.js', array('jquery') );
					wp_enqueue_script('farbtastic');
					wp_enqueue_style('farbtastic');
				}
			}

			/* Process Input and Add Options Page*/
			function addAdminPage() {
				// global $themename, $shortname, $options;
				if ( current_user_can( 'edit_theme_options' ) && isset( $_GET['page'] ) && $_GET['page'] == basename(__FILE__) ) {
					if ( ! empty( $_REQUEST['save-theme-options-nonce'] ) && wp_verify_nonce( $_REQUEST['save-theme-options-nonce'], 'save-theme-options' ) && isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'save' ) {
						foreach ($this->options as $value) {
							if ( array_key_exists('id', $value) ) {
								if ( isset( $_REQUEST[ $value['id'] ] ) ) {
									if (
										in_array(
											$value['id'],
											array(
												$this->shortname.'_background_color',
												$this->shortname.'_border_color',
												$this->shortname.'_hover_color',
												$this->shortname.'_link_color',
											)
										)
									) {
										$opt_value = preg_match( '/^#?([a-f0-9]{3}){1,2}$/i', trim( $_REQUEST[ $value['id'] ] ) ) ? trim( $_REQUEST[ $value['id'] ] ) : '';
										update_option( $value['id'], $opt_value );
									} else {
										update_option( $value['id'], stripslashes( $_REQUEST[ $value['id'] ] ) );
									}
								}
							}
						}
						wp_redirect("themes.php?page=".basename(__FILE__)."&saved=true");
						exit;
					} else if ( ! empty( $_REQUEST['reset-theme-options-nonce'] ) && wp_verify_nonce( $_REQUEST['reset-theme-options-nonce'], 'reset-theme-options' ) && isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'reset' ) {
						foreach ($this->options as $value) {
							if ( array_key_exists('id', $value) ) {
								delete_option( $value['id'] );
							}
						}
						wp_redirect("themes.php?page=".basename(__FILE__)."&reset=true");
						exit;
					}
				}
				add_theme_page(__( 'Theme Options' ), __( 'Theme Options' ), 'edit_theme_options', basename(__FILE__), array(&$this, 'adminPage'));
			}

			/* Output of the Admin Page */
			function adminPage () {
				// global $themename, $shortname, $options;
				if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>' . $this->themename . __(' options saved.', 'vigilance') . '</strong></p></div>';
				if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>' . $this->themename . __(' options reset.', 'vigilance') . '</strong></p></div>'; ?>

<div id="v-options" class="wrap">
	<?php screen_icon(); echo "<h2>" . get_current_theme() . __( ' Theme Options' ) . "</h2>"; ?>
	<div id="vop-body">
		<form method="post">
<?php
				wp_nonce_field( 'save-theme-options', 'save-theme-options-nonce' );
				for ($i = 0; $i < count($this->options); $i++) :
					switch ($this->options[$i]["type"]) :

						case "subhead":
							if ($i != 0) { ?>
	</div>
</div><!--end v-option--><?php } ?>
<div class="v-option"<?php echo ( isset( $this->options[$i]['hidden'] ) && $this->options[$i]['hidden'] ) ? ' style="display: none;"' : ''; ?>>
	<h3><?php echo $this->options[$i]["name"]; ?></h3>
	<div class="v-option-body clear">
						<?php
							break;


					case "checkbox":
						?>
		<div class="v-field check clear">
			<div class="v-field-d"><span><?php echo $this->options[$i]["desc"]; ?></span></div>
			<input id="<?php echo $this->options[$i]["id"]; ?>" type="checkbox" name="<?php echo $this->options[$i]['id']; ?>" value="true"<?php echo (get_settings($this->options[$i]['id'])) ? ' checked="checked"' : ''; ?> />
			<label for="<?php echo $this->options[$i]["id"]; ?>"><?php echo $this->options[$i]["name"]; ?></label>
		</div><!--end v-field check-->
						<?php
							break;


						case "radio":
							?>
		<div class="v-field radio clear">
			<div class="v-field-d"><span><?php echo $this->options[$i]["desc"]; ?></span></div>
				<?php
				$radio_setting = get_settings($this->options[$i]['id']);
				$checked = '';
				foreach ($this->options[$i]['options'] as $key => $val) :
					if ($radio_setting != '' &&  $key == get_settings($this->options[$i]['id']) ) {
						$checked = ' checked="checked"';
					} else {
						if ($key == $this->options[$i]['std']){
							$checked = 'checked="checked"';
						}
					}
					?>
				<input type="radio" name="<?php echo $this->options[$i]['id']; ?>" value="<?php echo $key; ?>"<?php echo $checked; ?> /><?php echo $val; ?><br />
				<?php endforeach; ?>
			<label for="<?php echo $this->options[$i]["id"]; ?>"><?php echo $this->options[$i]["name"]; ?></label>
		</div><!--end v-field radio-->
						<?php
							break;

						case "text":
							?>
		<div class="v-field text clear">
			<div class="v-field-d"><span><?php echo $this->options[$i]["desc"]; ?></span></div>
			<label for="<?php echo $this->options[$i]["id"]; ?>"><?php echo $this->options[$i]["name"]; ?></label>
			<input id="<?php echo $this->options[$i]["id"]; ?>" type="text" name="<?php echo $this->options[$i]["id"]; ?>" value="<?php echo stripslashes((get_settings($this->options[$i]["id"]) != '')) ? get_settings($this->options[$i]["id"]) : $this->options[$i]["std"]; ?>" />
		</div><!--end v-field text-->
						<?php
							break;

						case "colorpicker":
							?>
		<div class="v-field colorpicker clear">

			<div class="v-field-d"><span><?php echo $this->options[$i]["desc"]; ?></span></div>
			<label for="<?php echo $this->options[$i]["id"]; ?>"><?php echo $this->options[$i]["name"]; ?> <a href="return false;" onclick="toggleColorpicker (this, '<?php echo $this->options[$i]["id"]; ?>', 'open', '<?php _e('show color picker', 'vigilance'); ?>', '<?php _e('hide color picker', 'vigilance'); ?>'); return false;"><?php _e('show color picker', 'vigilance'); ?></a></label>
			<div id="<?php echo $this->options[$i]["id"]; ?>_colorpicker" class="colorpicker_container"></div>
			<input id="<?php echo $this->options[$i]["id"]; ?>" type="text" name="<?php echo $this->options[$i]["id"]; ?>" value="<?php echo (get_settings($this->options[$i]["id"]) != '') ? get_settings($this->options[$i]["id"]) : $this->options[$i]["std"]; ?>" />
		</div><!--end v-field colorpicker-->
						<?php
							break;

						case "select":
							?>
		<div class="v-field select clear">
			<div class="v-field-d"><span><?php echo $this->options[$i]["desc"]?></span></div>
			<label for="<?php echo $this->options[$i]["id"]; ?>"><?php echo $this->options[$i]["name"]; ?></label>
			<select id="<?php echo $this->options[$i]["id"]; ?>" name="<?php echo $this->options[$i]["id"]; ?>">
				<?php
					foreach ($this->options[$i]["options"] as $key => $val) :
						if (get_settings($this->options[$i]["id"]) == '' || is_null(get_settings($this->options[$i]["id"]))) : ?>
					<option value="<?php echo $key; ?>"<?php echo ($key == $this->options[$i]['std']) ? ' selected="selected"' : ''; ?>><?php echo $val; ?></option>
						<?php else : ?>
					<option value="<?php echo $key; ?>"<?php echo get_settings($this->options[$i]["id"]) == $key ? ' selected="selected"' : ''; ?>><?php echo $val; ?></option>
					<?php
						endif;
					endforeach;
				?>
			</select>
		</div><!--end v-field select-->
						<?php
							break;

						case "textarea":
							?>
		<div class="v-field textarea clear">
			<div class="v-field-d"><span><?php echo $this->options[$i]["desc"]?></span></div>
			<label for="<?php echo $this->options[$i]["id"]?>"><?php echo $this->options[$i]["name"]?></label>
			<textarea id="<?php echo $this->options[$i]["id"]?>" name="<?php echo $this->options[$i]["id"]?>"<?php echo ($this->options[$i]["options"] ? ' rows="'.$this->options[$i]["options"]["rows"].'" cols="'.$this->options[$i]["options"]["cols"].'"' : ''); ?>><?php
				echo (get_settings($this->options[$i]['id']) != '') ? esc_textarea(get_settings($this->options[$i]['id'])) : esc_textarea($this->options[$i]['std']);
			?></textarea>
		</div><!--end vop-v-field textarea-->
						<?php
							break;

					endswitch;
				endfor;
			?>
			</div>
			<div class="v-saveall-button submit">
				<input type="hidden" name="action" value="save" />
				<input class="button-primary" type="submit" value="<?php _e('Save Options', 'vigilance'); ?>" name="save"/>
			</div>
			</form>
			<div class="v-reset-button submit">
				<form method="post">
					<?php wp_nonce_field( 'reset-theme-options', 'reset-theme-options-nonce' ); ?>
					<input type="hidden" name="action" value="reset" />
					<input class="v-reset" type="submit" value="<?php _e('Reset Options', 'vigilance'); ?>" name="reset"/>
				</form>
			</div>

			<script type="text/javascript">
			<?php
				for ($i = 0; $i < count($this->options); $i++) :
					if ( $this->options[$i]['type'] == 'colorpicker' ) :
			?>
					jQuery("#<?php echo $this->options[$i]["id"]; ?>_colorpicker").farbtastic("#<?php echo $this->options[$i]["id"]; ?>");
			<?php
					endif;
				endfor;
			?>
				jQuery('.colorpicker_container').hide();
			</script>
	</div><!--end vop-body-->
</div><!--end v-options-->

			<?php
			}
		}
	}

?>