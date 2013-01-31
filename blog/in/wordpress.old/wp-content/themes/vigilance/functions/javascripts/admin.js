function toggleColorpicker (link, id, toggledir, opentext, closetext) {
  jQuery('.colorpicker_container').hide();

  if (toggledir == "open") {
    jQuery('#'+id+'_colorpicker').show();
    jQuery(link).replaceWith('<a href="return false;" onclick="toggleColorpicker (this, \''+id+'\', \'close\', \''+opentext+'\', \''+closetext+'\')">'+closetext+'</a>');
  } else {
    jQuery(link).replaceWith('<a href="return false;" onclick="toggleColorpicker (this, \''+id+'\', \'open\', \''+opentext+'\', \''+closetext+'\')">'+opentext+'</a>');
  }
}