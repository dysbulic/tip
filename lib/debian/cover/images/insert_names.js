var svgNS = "http://www.w3.org/2000/svg";

var url;
if(document.getURL) {
  url = document.getURL();
} else if(window.location) {
  url = window.location.href;
}
var params = url.substring(url.indexOf("?") + 1).split(/&/);

for(var i = 0; i < params.length; i++) {
  params[i] = params[i].replace(/\+/gi, " ");
  var equal_index = params[i].indexOf("=");
  if(equal_index >= 0) {
    params[unescape(params[i].substring(0, equal_index))] =
     unescape(params[i].substring(equal_index + 1));
  }
}

var texts = new Object();
texts["release"] = "translate(100 362)";
texts["discname"] = "translate(126 387)";
texts["discid"] = "translate(75 340)";

for(text in texts) {
  if(params[text]) {
    var node = document.createElementNS(svgNS, "text");
    node.setAttribute("id", text);
    node.setAttribute("transform", texts[text]);
    node.appendChild(document.createTextNode(params[text]));
    document.getElementsByTagName("svg").item(0).appendChild(node);
  }
}
