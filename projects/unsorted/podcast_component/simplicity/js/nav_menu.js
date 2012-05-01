$(document).ready(function(){
  $("#navmenu li").hover(function(){ $("ul", this).fadeIn("fast"); }, 
                         function(){ $("ul", this).fadeOut("fast"); });
  /*@cc_on
   @if (@_jscript_build < 7)
    $("#navmenu li").hoverClass("hover");
   @end
   @*/
});

$.fn.hoverClass = function(c) {
  return this.each(function(){
    $(this).hover(function() { $(this).addClass(c); },
                  function() { $(this).removeClass(c); });
  });
};    
