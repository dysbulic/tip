/* Using white-space: pre is causing leading and trailing newlines to show up that I don't want */
function trimCode() {
  var divs = document.getElementsByTagName("div");
  for(var i = 0; i < divs.length; i++) {
    var div = divs.item(i);
    if(div.className == "code") {
      div.firstChild.data = div.firstChild.data.replace(/^\s+/, "");
      div.firstChild.data = div.firstChild.data.replace(/\s+$/, "");
    }
  }
}
addListener(this, "load", trimCode);
