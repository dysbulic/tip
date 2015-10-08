/**
 * This does not get loaded into the global environment
 * var script = document.createElement("script");
 * script.setAttribute("type", "text/javascript");
 * script.setAttribute("src", "compatability.js");
 * document.getElementsByTagName("head")[0].appendChild(script);
 */

//if(typeof(__WJH_COMPAT_LIB) == "undefined" || !__WJH_COMPAT_LIB) {
//  alert("Compatability library must be loaded");
//}
//addLoadListener(init, false);

function createXYTable(id, xElements, yElements,
                       cellOverListener, cellClickListener) {
  var div = document.getElementById(id);
  if(!div) {
    alert("XY table elm, \"" + id + "\", not found in page");
  }
  var table = document.createElement("table");
  div.appendChild(table);
  var tableBody = document.createElement("tbody");
  table.appendChild(tableBody);

  if(typeof(xElements) == "undefined") {
    xElements = 20;
  }

  if(typeof(yElements) == "undefined") {
    yElements = 10;
  }

  var xySplotches = new Array(yElements);
  xySplotches[0] = new Array(xElements);

  for(var rowIndex = 0; rowIndex < xySplotches.length; rowIndex++) {
    if(rowIndex != 0) {
      xySplotches[rowIndex] = new Array(xySplotches[0].length);
    }
    var row = document.createElement("tr");
    tableBody.appendChild(row);
    for(var colIndex = 0; colIndex < xySplotches[0].length; colIndex++) {
      xySplotches[rowIndex][colIndex] = document.createElement("td");
      //xySplotches[rowIndex][colIndex].appendChild(document.createTextNode("test"));;
      xySplotches[rowIndex][colIndex].colortable = xySplotches;
      xySplotches[rowIndex][colIndex].x = rowIndex;
      xySplotches[rowIndex][colIndex].y = colIndex;

      row.appendChild(xySplotches[rowIndex][colIndex]);
      if(typeof(cellOverListener) != "undefined") {
        addListener(xySplotches[rowIndex][colIndex], "mouseover", cellOverListener);
      }
      if(typeof(cellClickListener) != "undefined") {
        addListener(xySplotches[rowIndex][colIndex], "click", cellClickListener);
      }
      xySplotches[rowIndex][colIndex].getHue = function () {
        return 360 * (this.y + 1) / (this.colortable[this.x].length + 1);
      }
      xySplotches[rowIndex][colIndex].getSaturation = function () {
        return 1 - (this.x + 1) / (this.colortable.length + 1);
      }
    }
  }

  xySplotches.update = function(brightness) {
    for(var row = this.length - 1; row >= 0; row--) {
      for(var col = this[row].length - 1; col >= 0; col--) {
        this[row][col].style.backgroundColor =
          getCSSColor(this[row][col].getHue(),
                      this[row][col].getSaturation(),
                      brightness);
      }
    }
  }

  xySplotches.update(1);
  return xySplotches;
}  

function createZTable(id, zElements,
                      cellOverListener, cellClickListener) {
  var div = document.getElementById(id);
  if(!div) {
    alert("Z table elm, \"" + id + "\", not found in page");
  }
  var table = document.createElement("table");
  div.appendChild(table);
  var row = document.createElement("tr");
  table.appendChild(row);

  if(typeof(zElements) == "undefined") {
    zElements = 10;
  }

  var zSplotches = new Array(zElements);

  for(var colIndex = 0; colIndex < zSplotches.length; colIndex++) {
    zSplotches[colIndex] = document.createElement("td");
    zSplotches[colIndex].colortable = zSplotches;
    zSplotches[colIndex].z = colIndex;
    row.appendChild(zSplotches[colIndex]);
    if(typeof(cellOverListener) != "undefined") {
      //zSplotches[colIndex].addEventListener("mouseover", cellOverListener, false);
      addListener(zSplotches[colIndex], "mouseover", cellOverListener);
    }
    if(typeof(cellClickListener) != "undefined") {
      //zSplotches[colIndex].addEventListener("click", cellClickListener, false);
      addListener(zSplotches[colIndex], "click", cellClickListener);
    }
    zSplotches[colIndex].getBrightness = function () {
      return Math.sqrt((this.z) / (this.colortable.length - 1));
    }
  }
  
  zSplotches.update = function (hue, saturation) {
    for(var col = zSplotches.length - 1; col >= 0; col--) {
      zSplotches[col].style.backgroundColor =
         getCSSColor(hue, saturation, zSplotches[col].getBrightness());
    }
  }
  zSplotches.update(0, 1);
  return zSplotches;
}

/* Takes a CSS color and returns a possible set of hsb values.
 * The mapping is not exact because, for example, the color black
 * can have and hue or saturation values and it makes no
 * difference.
 */
function getHSVColor(rgbColor) {
  var colors = rgbColor.replace(/rgb\((.*)\)/, "$1").split(/\s*,\s*/);
  var min = 1;
  var max = 0;
  for(i = 0; i < colors.length; i++) {
    colors[i] = new Number(colors[i]).valueOf() / 255;
    if(colors[i] > max) {
      max = colors[i];
    }
    if(colors[i] < min) {
      min = colors[i];
    }
  }
  var intensity = max;
  var delta = max - min;
  var saturation = (max > 0 ? delta / max : 0);
  var hue;

  if(saturation == 0) { // A shade of gray
    hue = Math.random() * 360;
  } else {
    if(colors[0] == max ) {
      sector = (colors[1] - colors[2]) / delta;     // between yellow & magenta
    } else if(colors[1] == max) {
      sector = 2 + (colors[2] - colors[0]) / delta; // between cyan & yellow
    } else {
      sector = 4 + (colors[0] - colors[1]) / delta; // between magenta & cyan
    }
    var hue = sector * 60;
    if(hue < 0) {
      hue += 360;
    }
  }
  return new Array(hue, saturation, intensity);
}

/* Returns a css color (rgb or code) for a hsb tuple
 * These equations map a hsv cone onto a rgb
 *  cube, so they make no sense really. =)
 */
function getCSSColor(hue, saturation, brightness) {
  var red, green, blue;
  if(saturation == 0) {
    // Some shade of grey
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
