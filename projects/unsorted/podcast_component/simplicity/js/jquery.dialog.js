jQuery.fn.box = function(o) {
  this.wrap('<div class="dialog"></div>')
   .wrap('<div class="bd"></div>')
   .wrap('<div class="c"></div>')
   .wrap('<div class="s"></div>')
   .parent().parent().parent().parent()
   .prepend('<div class="hd"><div class="c"></div></div>')
   .append('<div class="ft"><div class="c"></div></div>');
}
