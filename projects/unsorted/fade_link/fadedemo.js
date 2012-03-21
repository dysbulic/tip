var activeCells, colortable, xySplotches, zSplotches, testdiv, testtext;
var rgbTest = new Array(11), hsbTest = new Array(11);

function cellMouseOver(event) {
  if(typeof(activeCells) != "undefined") {
    for(var i = 0; i < activeCells.length; i++) {
      activeCells[i].setAttribute("title", event.target.style.backgroundColor);
      activeCells[i].style.backgroundColor = event.target.style.backgroundColor;
      activeCells[i].testArray.updateColors();
    }
    if(activeCells[0].getAttribute("class").match(/first/)) {
      setStartFadeColor(testdiv, rgbTest[0].style.backgroundColor);
      setStartFadeColor(testtext, rgbTest[0].style.backgroundColor);
    } else {
      setEndFadeColor(testdiv, rgbTest[rgbTest.length - 1].style.backgroundColor);
      setEndFadeColor(testtext, rgbTest[rgbTest.length - 1].style.backgroundColor);
    }
  }
}

function xyColorClick(event) {
  colortable.style.display = "none";
}

function zColorClick(event) {
  xySplotches.update(event.target.getBrightness());
}

function changeCellColor(event) {
  activeCells = getSource(event).colorCells;
  if(colortable.style.display != "block") {
    colortable.style.display = "block";
    colortable.style.top = (event.clientY + 5) + "px";
    if(activeCells[0].getAttribute("class").match(/first/)) {
      colortable.style.left = (event.clientX + 5) + "px";
    } else {
      colortable.style.left = (event.clientX - colortable.clientWidth - 5) + "px";
    }
  } else {
    colortable.style.display = "none";
  }
}

function setup() {
  setupColorTable();
  setupRGBTest();
  setupHSBTest();
  
  rgbTest[0].colorCells = hsbTest[0].colorCells =
    new Array(rgbTest[0], hsbTest[0]);
  rgbTest[rgbTest.length - 1].colorCells = hsbTest[hsbTest.length - 1].colorCells =
    new Array(rgbTest[rgbTest.length - 1], hsbTest[hsbTest.length - 1]);
  
  testdiv = document.getElementById("testdiv");
  setupFade(testdiv, "background-color", .1, 25);
  
  testtext = document.getElementById("testtext");
  setupFade(testtext, "color", .05, 25);
  
  var randomColor = function() {
    return "rgb(" + Math.round(Math.random() * 255) + "," +
    Math.round(Math.random() * 255) + "," +
    Math.round(Math.random() * 255) + ")";
  }
  
  rgbTest[0].style.backgroundColor =
    hsbTest[0].style.backgroundColor = randomColor();
  rgbTest[rgbTest.length - 1].style.backgroundColor =
    hsbTest[hsbTest.length - 1].style.backgroundColor = randomColor();
  rgbTest.updateColors();
  hsbTest.updateColors();
  
  setStartFadeColor(testdiv, rgbTest[0].style.backgroundColor);
  setStartFadeColor(testtext, rgbTest[0].style.backgroundColor);
  setEndFadeColor(testdiv, rgbTest[rgbTest.length - 1].style.backgroundColor);
  setEndFadeColor(testtext, rgbTest[rgbTest.length - 1].style.backgroundColor);
}

function setupColorTable() {
  colortable = document.getElementById("colortable");
  xySplotches = createXYTable("colortable-xy", 30, 20,
                              cellMouseOver, xyColorClick);
  zSplotches = createZTable("colortable-z", 10, undefined, zColorClick);
  zSplotches.update(0, 0);
}

function setupTest(array, id) {
  var test = document.getElementById(id);
  for(var i = 0; i < array.length; i++) {
    array[i] = document.createElement("div");
    array[i].setAttribute("class", "cell");
    test.appendChild(array[i]);
  }
  
  array[0].testArray = array[array.length - 1].testArray = array;
  addListener(array[0], "click", changeCellColor);
  array[0].setAttribute("class", "cell first");
  
  addListener(array[array.length - 1], "click", changeCellColor);
  array[array.length - 1].setAttribute("class", "cell last");
  
  test.appendChild(document.createElement("div"));
  test.lastChild.style.clear = "both";
}

function setupRGBTest() {
  rgbTest.updateColors = function() {
    var startColors =
    this[0].style.backgroundColor.replace(/rgb\((.*)\)/, "$1").split(/\s*,\s*/);
    var endColors =
    this[this.length - 1].style.backgroundColor.replace(/rgb\((.*)\)/, "$1").split(/\s*,\s*/);
    var delta = new Array(startColors.length);
    for(var i = 0; i < startColors.length; i++) {
      startColors[i] = new Number(startColors[i]).valueOf();
      delta[i] = startColors[i] - new Number(endColors[i]).valueOf();
    }
    for(var i = 1; i < this.length - 1; i++) {
      var percent = (this.length - i) / this.length - 1;
      var color = "rgb(" + 
      Math.round(startColors[0] + percent * delta[0]) + ", " +
      Math.round(startColors[1] + percent * delta[1]) + ", " +
      Math.round(startColors[2] + percent * delta[2]) + ")";
      this[i].setAttribute("title", color);
      this[i].style.backgroundColor = color;
    }
  }
  
  setupTest(rgbTest, "rgb-test");
}

function setupHSBTest() {
  hsbTest.updateColors = function() {
    var startColor = getHSVColor(this[0].style.backgroundColor);
    var endColor = getHSVColor(this[this.length - 1].style.backgroundColor);
    var delta = new Array(startColor.length);
    for(var i = 0; i < startColor.length; i++) {
      delta[i] = startColor[i] - endColor[i];
    }
    for(var i = 1; i < this.length - 1; i++) {
      var percent = (this.length - i) / this.length - 1;
      var color = getCSSColor(startColor[0] + percent * delta[0],
                              startColor[1] + percent * delta[1],
                              startColor[2] + percent * delta[2]);
      this[i].setAttribute("title", color);
      this[i].style.backgroundColor = color;
    }
  }
  
  setupTest(hsbTest, "hsb-test");
}
