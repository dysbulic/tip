function makestars(background, holder) {
  if(typeof(navigator) != "undefined" && navigator.appCodeName == "Mozilla") {
    // getBBox won't work in Firefox from onload
    setTimeout(function() { _makestars(background, holder); }, 0);
  } else {
    _makestars(background, holder);
  }
}

function getCSSColor(hue, saturation, brightness) {
  var red, green, blue;
  if(saturation == 0) { // Some shade of grey
    red = green = blue = brightness;  
  } else {
    var sector = (hue % 360) / 60;
    var f = sector - Math.floor(sector);
    var p = brightness * (1 - saturation);
    var q = brightness * (1 - (saturation * f));
    var r = brightness * (1 - (saturation * (1 - f)));
    switch(Math.floor(sector)) {
      case 0: red = brightness; green = r;          blue = p;          break;
      case 1: red = q;          green = brightness; blue = p;          break;
      case 2: red = p;          green = brightness; blue = r;          break;
      case 3: red = p;          green = q;          blue = brightness; break;
      case 4: red = r;          green = p;          blue = brightness; break;
      case 5: red = brightness; green = p;          blue = q;          break;
      default: return "error";
    }
  }
  // Values currently vary between 0 - 1
  red = Math.round(red * 255);
  green = Math.round(green * 255);
  blue = Math.round(blue * 255);
  
  var color = "rgb(" + red + ", " + green + ", " + blue + ")";
  return color;
}

/**
 * I would like there to be fewer stars at the bottom than at the top,
 * so I need a probability distribution function that isn't simply
 * constant. This one should be linearly increasing from some min to
 * some max.
 */
function lin_dist() {
  var pmin = .1;
  var pmax = .7;
  var porig = Math.random();
  return Math.random();
  return (porig - .5) * (pmax - pmin) + .5;
}

function _makestars(background, holder) {
  var bbox = document.getElementById(background).getBBox();
  var numStars = bbox.width * bbox.height / 500;
  var maxRadius = 2;
  if(typeof(holder) == "undefined") {
    holder = document.getElementsByTagName("svg").item(0);
  } else {
    holder = document.getElementById(holder);
  }
  for(var i = 0; i < numStars; i++) {
    var star = document.createElementNS("http://www.w3.org/2000/svg", "circle");
    radius = Math.random() * maxRadius;
    star.setAttribute("r", radius);
    star.setAttribute("cx", bbox.x + radius + lin_dist() * (bbox.width - 2 * radius * 1.01));
    star.setAttribute("cy", bbox.y + radius + lin_dist() * (bbox.height - 2 * radius * 1.01));
    star.style.setProperty("fill",
                           getCSSColor(Math.random() * 360, Math.random() * .25, .5 + Math.random() * .5),
                           null);
    holder.appendChild(star);
  }
}
