$(document).ready(function(){
  $("#navigation li").hover(function(){ $("ul", this).fadeIn("fast"); }, 
                            function(){ $("ul", this).fadeOut("fast"); });
  $("#categories li").prepend("<img class='reflection' src='reflection.png' alt='' />")
    .hover(function(){ $(this).find(".reflection").slideUp("fast"); },
           function(){ $(this).find(".reflection").slideDown("fast"); });
  /*@cc_on
   @if (@_jscript_build < 7)
    $("#navigation li").hoverClass("hover");
   @end
   @*/
});

$.fn.hoverClass = function(c) {
  return this.each(function(){
    $(this).hover(function() { $(this).addClass(c); },
                  function() { $(this).removeClass(c); });
  });
};    
