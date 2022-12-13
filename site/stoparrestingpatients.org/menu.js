$(document).ready(function(){
  $("#navigation ul").hide();
  $("#navigation li").hover(function(){ $("ul", this).fadeIn("fast"); }, 
                            function(){ $("ul", this).fadeOut("fast"); });
  /*@cc_on
   @if (@_jscript_build < 7)
    $(".nav li").hoverClass("hover");
   @end
   @*/
});

$.fn.hoverClass = function(c) {
  return this.each(function(){
    $(this).hover(function() { $(this).addClass(c); },
                  function() { $(this).removeClass(c); });
  });
};    
