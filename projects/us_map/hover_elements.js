var debugMap = false;

function regionover(event) {
  var target = getSource(event);
  if(debugMap) {
    var body = document.getElementsByTagName("body")[0];
    body.appendChild(document.createElement("div"));
    body.lastChild.appendChild(document.createTextNode("showing: " + target.id));
  }
  if(typeof(nameDisplay) != "undefined") {
    nameDisplay.data = target.getAttribute("title");
  }
  document.getElementById(target.id + "-img").style.display = "block";
}

function regionout(event) {
  var target = getSource(event);
  if(debugMap) {
    var body = document.getElementsByTagName("body")[0];
    body.appendChild(document.createElement("div"));
    body.lastChild.appendChild(document.createTextNode("hiding: " + target.id));
  }
  if(typeof(nameDisplay) != "undefined") {
    nameDisplay.data = '';
  }
  document.getElementById(target.id + "-img").style.display = "none";
}

function regionclick(event) {
}
