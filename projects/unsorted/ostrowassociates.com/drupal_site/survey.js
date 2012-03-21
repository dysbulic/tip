var prefix = "edit-medical_issues-";
var count = 1;
var lastElement;
var element;
do {
  lastElement = element;
  element = document.getElementById(prefix + count++);
} while(typeof(element) != "undefined" && element != null);

var container = lastElement.parentNode.parentNode;
container.appendChild(document.createElement("input"));
container.lastChild.className = "inline-text";
container.lastChild.setAttribute("type", "text");
container.lastChild.setAttribute("name", prefix + "-text-1");
