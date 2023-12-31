function scramble(elementName) {
  var iterator = function() {
    var timeout = 50; // about of time between iterations

    elements = arguments.callee.elements;
    if(elements[elements.startIndex].solveCount == elements.currentCount) {
      elements[elements.startIndex].text.data = elements.correctText[elements.startIndex];
      elements[elements.startIndex].span.className = "solved";
      elements.startIndex++;
    }
    for(i = elements.startIndex; i < elements.length; i++) {
      elements[i].text.data = String.fromCharCode(32 + Math.floor(Math.random() * 222));
    }
    if(elements.startIndex < elements.length) {
      setTimeout(arguments.callee, timeout);
    }
    elements.currentCount++;
  }

  var minimum = 20; // minimum pertubations
  var base = 5; // base amount to randomize
  var step = 10; // amount each subsequent element is increased by
  var element = document.getElementById(elementName);
  
  while(element.nodeType == Node.ELEMENT_NODE) {
    element = element.firstChild;
  }
  var elements = new Array(element.data.length);
  elements.correctText = element.data;
  elements.startIndex = 0;
  elements.currentCount = 0;
  for(i = 0; i < element.data.length; i++) {
    elements[i] = new Object();
    elements[i].span = document.createElement("span");
    elements[i].span.className = "unsolved";
    elements[i].text = document.createTextNode(element.data[i]);
    elements[i].span.appendChild(elements[i].text);
    element.parentNode.insertBefore(elements[i].span, element);
  }
  element.data = "";
  elements[0].solveCount = Math.floor(minimum + Math.random() * base);
  for(i = 1; i < elements.length; i++) {
    elements[i].solveCount = Math.floor(elements[i - 1].solveCount + Math.random() * (base + i * step));
  }
  iterator.elements = elements;

  iterator();
}
