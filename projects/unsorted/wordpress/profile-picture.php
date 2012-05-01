<?php
/*
Plugin Name: Author Profile Picture
Plugin URI: http://geekgrl.net/2007/01/02/profile-pics-plugin-release/
Description: Adds picture to Author profile
Version: 0.1
Author: Hannah Gray
Author URI: http://geekgrl.net
*/

// Get stored options -- substitute defaults if none exist
$profile_picture_options = get_option("profile_picture_options");
$upload_path = get_option("upload_path");
$profile_picture_defaults = array('image_dir' => $upload_path . '/authors/',
				  'image_extensions' => 'gif png jpg',
				  'image_default' => 'default.jpg',
				  'gravatar_width' => '80');

// Add actions to appropriete hooks
add_action('show_user_profile', 'add_userpic_fields');
add_action('profile_update', 'upload_pic', 1);
add_action('admin_menu', 'profile_picture_config');

//*** GUI FUNCTION: add menu item for plugin config to Options page
function profile_picture_config() {
  global $wpdb;
  if ( function_exists('add_options_page') ) {
    add_options_page('Profile Picture', 'Profile Picture', 8, __FILE__, 'profile_picture_conf_page');
  }
}

//*** GUI FUNCTION: Show config form
function profile_picture_conf_page() {
  global $profile_picture_defaults, $profile_picture_options;
  // if submit was pressed, process config data
  if ( isset($_POST['submit']) ) {
    // check user permissions
    if ( !current_user_can('manage_options') ) {
      die(__('Cheatin&#8217; uh?'));
      // if okay, store data
    } else {
      foreach($profile_picture_defaults as $key => $default) {
        if ( isset($_POST[$key]) ) {
          if ( $key == 'image_extensions' ) {
            $_POST[$key] = strtolower($_POST[$key]);
          }
          // it is not possible to set an option to an empty value
          // on an empty value, the element is set to the default
          $profile_picture_options[$key] = ($_POST[$key] != '' ? $_POST[$key] : $profile_picture_defaults[$key]);
          ${$key} = $profile_picture_options[$key];
        }
      }
      update_option('profile_picture_options', $profile_picture_options);
      echo '<div id="message" class="updated fade">Profile picture settings saved.</div>';
    }
  }
  // Set any unset or empty items to their default
  foreach($profile_picture_defaults as $key => $default) {
    ${$key} = (isset($profile_picture_options[$key]) && $profile_picture_options[$key] != ''
               ? $profile_picture_options[$key] : $default);
  }
?>
        <div class="wrap">
	  <h2>Profile Picture Options</h2>	
          <form action="" method="post" id="picture_uploader" style="margin: auto;">
	   <p class="submit"><input type="submit" name="submit" value="<?php _e('Update Options &raquo;'); ?>" /></p>
           <table class="optiontable">
           <tr>
             <th><label>Profile Pics Upload Directory: *</label></th>
             <td>
               <input size="45" name='image_dir' value='<?php _e($image_dir); ?>' class="code" />
               <br />
               Recommended: <?php _e($profile_picture_defaults['image_dir']); ?>  (*<em>must be set to chmod 777</em>)
             </td>
           </tr>
           <tr>
             <th><label>Allowed File Extensions:</label></th>
             <td>
               <input size="45" name='image_extensions' value='<?php _e($image_extensions); ?>' class="code" />
               <br />
	       Space separated list. Extensions are lower-case.
             </td>
           </tr>
           <tr>
             <th><label>Standard Width for Comment Author "Gravatar":</label></th>
             <td>
               <input size="45" name='gravatar_width' value='<?php _e($gravatar_width); ?>' class="code" />
               <br />
               Width in pixels
             </td>
           </tr>
           <tr>
             <th><label>Default Image:</label></th>
             <td>
               <input size="45" name='image_default' value='<?php _e($image_default); ?>' class="code" />
               <br />
               Must be stored in the profile pics directory specified above
             </td>
           </tr>		
           </table>
	   <p class="submit"><input type="submit" name="submit" value="<?php _e('Update Options &raquo;'); ?>" /></p>
	   </form>
	 </div>
<?php
}

//*** GUI FUNCTION: displays "add picture" box when editing your profile
function add_userpic_fields() {
  global $user_ID, $image_extensions;
  
  // build extension check string for the js
  $image_extensions_array = explode(' ', $image_extensions);
  $checkstr = "";
  foreach ($image_extensions_array as $count => $exe) {
    $checkstr .= "(ext != '.$exe') && ";
  }
  $checkstr = rtrim($checkstr, ' && ');
  
  // HTML GUI, js changes form encoding and adds error check
    ?>
    <script type="text/javascript" language="javascript">//<![CDATA[
       function uploadPic() {
         document.profile.enctype = "multipart/form-data";
         var upload = document.profile.picture.value;
         upload = upload.toLowerCase();
         var ext = upload.substring((upload.lastIndexOf('.'));
         if (<?php _e($checkstr) ?>){
           alert('Please upload an image with one of the following extentions: <?php _e($image_extensions); ?>');
         }
       }
    //]]></script>
    <fieldset>
      <legend>Profile Picture</legend>
      <p>
        <label>Current:
          <div><img src="<?php _e(author_image_path($user_ID)); ?>" width="150" /></div>
         </label>
      </p>
      <p><label>Upload a New Picture:  <input type="file" name="picture" onchange="uploadPic();" /></label></p>
    </fieldset>
    <?php
}

//*** INTERNAL FUNCTION: stores pic submitted via profile editing page
function upload_pic() {
  global $image_dir, $user_ID, $image_extensions;
	
  $raw_name = (isset($_FILES['picture']['name'])) ? $_FILES['picture']['name'] : "";	
  // if file was sumbitted, continue
  if ($raw_name != "") {
    // delete previous image if it's there
    $image_extensions_array = explode(' ', $image_extensions);
    foreach ($image_extensions_array as $image_extension) {
      $old_pic_path = clean_path(ABSPATH . '/' . $image_dir . '/' . $user_ID . '.' . $image_extension);
      if ( file_exists($old_pic_path) ) { 
        unlink($old_pic_path);
      }
    }
    // build the path and filename 		
    $clean_name = ereg_replace("[^a-z0-9._]", "", ereg_replace(" ", "_", ereg_replace("%20", "_", strtolower($raw_name))));
    $file_ext = substr(strrchr($clean_name, "."), 1);
    $file_path = clean_path(ABSPATH . '/' . $image_dir . '/' . $user_ID . '.' . $file_ext);
    // store file
    move_uploaded_file($_FILES['picture']['tmp_name'], $file_path);
  } else {
    return false;
  }
}

//*** TEMPLATE FUNCTION: returns requested dimension from specific image
//    USAGE: 
//		path: absolute path to image from server root', 
//		dimension: the dimension you want, can be either 'height' or width'
//		display: display results (ie. echo)? true or false
function author_image_dimensions($path, $dimension, $display = false) {
  $size = getimagesize($path);
  $index = 0;
  
  switch ($dimension) {
  case 'width': $index = 0; break;
  case 'height': $index = 1; break;
  }
  if ($display) { echo $size[$index]; } else { return $size[$index]; }
}



//*** TEMPLATE FUNCTION: returns image for comment author
//    USAGE: 
//		authorID: id number of author
//		tags: attributes to include in img tag (optional, defaults to no tags)
function author_gravatar_tag($authorID, $tags = '') {
  global $gravatar_width;
  if ($authorID != 0) {
    $path = author_image_path($authorID, false, 'absolute');
    $width = $gravatar_width;
    $height = author_image_dimensions($path, 'height') * ($gravatar_width / author_image_dimensions($path, 'width'));
    $tag = '<img src="' . author_image_path($authorID, false, 'url') . '" width=' . $width . ' height=' . $height . ' '. $tags . ' />';
    return $tag;
  } else {
    return false;
  }
}


//*** TEMPLATE FUNCTION: returns image for author wrapped in image tag
//    USAGE: 
//		authorID: id number of author
//		tags: attributes to include in img tag (optional, defaults to no tags)
//		display: display results (ie. echo)? true or false (optional, defaults to true)
function author_image_tag($authorID, $tags = '', $display = true) {
  $path = author_image_path($authorID, false, 'absolute');
  $width = author_image_dimensions($path, 'width');
  $height = author_image_dimensions($path, 'height');
  $tag = '<img src="' . author_image_path($authorID, false, 'url') . '" width="' . $width . '" height="' . $height . '" '. $tags . ' ' . ' id="authorpic" />';
  if ($display) { echo $tag; } else { return $tag; }
}

//*** TEMPLATE FUNCTION: returns url or absolute path to author's picture
//    USAGE: 
//		authorID: id number of author
//		display: display results (ie. echo)? true or false (optional, defaults to true)
//		type: specify what kind of path requested: 'url' or 'absolute' (optional, defaults to url)
function author_image_path($authorID, $display = true, $type = 'url') {
  switch($type) {
  case 'url' :
    $ref =  clean_path(get_settings('siteurl') . pick_image($authorID));
    if ($display) { echo $ref; } else { return $ref; }
    break;
  case 'absolute':
    $ref =  clean_path(ABSPATH . pick_image($authorID));
    if ($display) { echo $ref; } else { return $ref; }
    break;
  }
} 


//*** INTERNAL FUNCTION: strips extra slashes from paths; means user-end 
//    configuration is not picky about leading and trailing slashes
function clean_path($dirty_path) {
  $nasties = array(1 => "///", 2 => "//", 3 => "http:/");
  $cleanies = array(1 => "/", 2 => "/", 3 => "http://");
  $clean_path = str_replace($nasties, $cleanies, $dirty_path);
  return $clean_path;
}

//*** INTERNAL FUNCTION: finds the appropriate path to the author's picture
function pick_image($authorID) {
  global $image_dir, $image_extensions, $image_default;
  $image_extensions_array = explode(' ', $image_extensions);
  // look for image file based on user id
  $path = "";
  foreach ($image_extensions_array as $image_extension) {
    $path_fragment = '/' . $image_dir . '/' . $authorID . '.' . $image_extension;
    $path_to_check = clean_path(ABSPATH . $path_fragment);
    if ( file_exists($path_to_check) ) { 
      $path = $path_fragment;
      break;
    }
  }
  // if not found, use default
  if ($path == "") {
    $path = '/' . $image_dir . '/' . $image_default;
  }
  return $path;
}
?>
