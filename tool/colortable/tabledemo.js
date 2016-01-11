var hue = 0;        // An angle in the hsv cone: 0 degrees - 360 degrees
var saturation = 1; // A percentage of saturation: 0 - 1
var brightness = 1; // A percentage of brightness: 0 - 1

var rulesBox; // Option box of stylesheet rules

var style;    // Style currently being altered
var property; // Property currently being altered

var sampleText;  // Text of the sample box to show rgb code
var statusText;  // Text to show the current status (on/off)

var enabled = true;

var xySplotches;
var zSplotches;

function setup_tables() {
  rulesBox = createRulesBox();
  createPropertiesBox();

  sampleText = document.createTextNode("color");
  document.getElementById("sample").appendChild(sampleText);
  
  statusText = document.createTextNode("On");
  document.getElementById("status").appendChild(statusText);
  
  addListener(document, "click", clicked);

  xySplotches = createXYTable("colortable-xy", 30, 10,
                              colorMouseOver, xyColorClick);
        
  zSplotches = createZTable("colortable-z", 10,
                            colorMouseOver, zColorClick);
}

function addOption(text, parent) {
  if(text.length > 0) {
    var option = document.createElement("option");
    option.appendChild(document.createTextNode(text));
    parent.appendChild(option);
  }
}

function createRulesBox() {
  var rulesBox = document.getElementById("rules-box");
  rulesBox.rules = new Array;
  
  rulesBox.addRule = function (name) {
    if(typeof(this.rules[name]) == "undefined" && name.length > 0) {
      var stylesheet = document.styleSheets[document.styleSheets.length - 1];
      // Normally precidence is given to the standards compliant interface,
      // but IE6 has a bug in it's stylesheet interface that seems to be
      // locking up on adding new rules to an existing style
      if(typeof(document.createStyleSheet) != "undefined") {
         stylesheet = document.createStyleSheet();
      }
      var rulesList = (typeof(stylesheet.cssRules) != "undefined" ?
                       stylesheet.cssRules :
                       (typeof(stylesheet.rules) != "undefined" ?
                        stylesheet.rules : undefined));
      if(typeof(stylesheet.insertRule) != "undefined") {
        stylesheet.insertRule(name + " {}", rulesList.length);
      } else if(typeof(stylesheet.addRule) != "undefined") {
        //stylesheet.addRule(name, undefined);
      }
      if(typeof(rulesList[rulesList.length - 1]) != "undefined") {
        this.rules[name] = rulesList[rulesList.length - 1].style;
      }
      addOption(name, rulesBox);
    }
  }
  
  var elements = new Array(".display", "body", "table", "td", "#sample", "a",
                           "input[type=\"text\"], input[type=\"password\"]",
                           "div");
  for(var i = 0; i < elements.length; i++) {
    rulesBox.addRule(elements[i]);
  }
  style = rulesBox.rules[elements[0]];
  return rulesBox;
}

function setRule(name) {
  style = rulesBox.rules[name];
}

function createPropertiesBox() {
  propertiesBox = document.getElementById("properties-box");
  
  propertiesBox.addProperty = function (name) {
    addOption(name, propertiesBox);
  }
  
  var elements = new Array("background-color", "color", "border-color");
  for(var i = 0; i < elements.length; i++) {
    propertiesBox.addProperty(elements[i]);
  }
  setProperty(elements[0]);
  return propertiesBox;
}

function setProperty(property) {
  this.property = property;
}

function clicked(event) {
  if(event.button != 0) {
    toggleStatus();
    event.preventDefault();
  }
}

function xyColorClick(event) {
  var source = event.target;
  zSplotches.update(source.getHue(), source.getSaturation());
}

function zColorClick(event) {
  var source = event.target;
  xySplotches.update(source.getBrightness());
}

function colorMouseOver(event) {
  if(typeof(style) != "undefined" && enabled) {
    var source = getSource(event);
    style.setProperty(property, source.style.backgroundColor, null);
    sampleText.data = source.style.backgroundColor;
  }
}

function toggleStatus() {
  enabled = !enabled;
  statusText.data = enabled ? "On" : "Off";
}
