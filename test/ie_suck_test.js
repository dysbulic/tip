function divtest() {
  var div = document.createElement("p");
  div.appendChild(document.createTextNode("Testing"));
  document.getElementsByTagName("body")[0].appendChild(div);
}

function paratest() {
  var para = document.createElement("p");
  para.appendChild(document.createTextNode("Also Testing"));
  document.getElementsByTagName("body")[0].appendChild(para);
}
