/**
 * Any input child elements of containers with a class of "required"
 * must have a value.
 */
$(document).ready(function() {
  $("form").submit(function() {
    /* The elements can either be required or optional. By default
     * everything is optional. If there are any elements marked
     * "optional" then it is assumed the rest are required. If 
     */
    var defaultOptional = $(".optional input").size() == 0 && $(this).attr("class") != "required";
    var checked = new Array();
    $("input").each(function() {
      var fieldName = $(this).attr("name");
      checked[fieldName] = (checked[fieldName] ||
                            (typeof($(this).fieldValue()[0]) != "undefined" &&
                             $(this).fieldValue()[0].length > 0));
    });
    var valid = true;
    for(item in checked) {
      if(typeof(item) == "undefined" || item == "undefined") continue;
      //alert(item + " : " + checked[item] + " : " + defaultOptional);
      valid = valid && checked[item];
      parent = $("input[@name=" + item + "]").parents("li:first");
      if(!checked[item] && !defaultOptional &&
         $("input[@name=" + item + "]").parents(".optional").size() == 0) {
        parent.addClass("invalid");
        if(parent.children(".errmsg").length == 0) {
          if(parent.children("ol").size() == 0) {
            parent.append($("<p class='errmsg'>Required value</p>").hide());
          } else {
            parent.children("ol:first").append($("<p class='errmsg'>Required value</p>").hide());
          }
        }
        parent.children(".errmsg").slideDown();
      } else {
        $("input[@name=" + item + "]").parents("li:first").removeClass("invalid");
        parent.children(".errmsg").slideUp();
      }
    }
    return valid;
  });
});
