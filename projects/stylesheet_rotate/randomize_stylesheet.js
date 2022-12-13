var alertnateStylesheets;
var stylesheetCookie = "styleTitle";

var stylesheetSaver =
  function() { var title = getActiveStylesheet(); if(title) setCookie({styleTitle:title}); };

addListener(this, "unload", stylesheetSaver);

function getAlternateStylesheets() {
  if(typeof(alternateStylesheets) == "undefined") {
    alternateStylesheets = new Array();
    var links = document.getElementsByTagName("link");
    for(var i = 0; i < links.length; i++) {
      var link = links[i];
      if(link.getAttribute("rel").indexOf("style") != -1 && link.getAttribute("title")) {
        alternateStylesheets[link.getAttribute("title")] = link;
        alternateStylesheets.length++;
      }
    }
  }
  return alternateStylesheets;
}

function disableAlternateStylesheets() {
  var stylesheets = getAlternateStylesheets();
  for(styleTitle in stylesheets) {
    stylesheets[styleTitle].disabled = true;
  }
}

function setActiveStylesheet(title) {
  var stylesheets = getAlternateStylesheets();
  if(typeof(title) == "undefined") {
    var cookie = getCookie();
    title = cookie[stylesheetCookie];
  }
  if(typeof(title) == "undefined") {
    var index = Math.floor(Math.random() * stylesheets.length);
    var i = -1;
    for(title in stylesheets) {
      if(++i == index) break;
    }
  }
  if(stylesheets[title]) {
    disableAlternateStylesheets();
    stylesheets[title].disabled = false;
  }
}

function getActiveStylesheet() {
  var stylesheets = getAlternateStylesheets();
  for(title in stylesheets) {
    if(stylesheets[title].disabled == false) return title;
  }
  return undefined;
}
