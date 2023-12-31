/**
 * IE specific code for making elements the same height as their container.
 * (Used for uniform height columns.)
 */

Node = { ELEMENT_NODE : 1 }; // Still not defined in IE7

function heightOf(element) {
  if(element.style.height == "" && element.className.match(/ height-processed/) == null) {
    var parentHeight = (element.parentNode.offsetHeight
                        - (element.parentNode.currentStyle.borderTopStyle != "none" ? parseInt(element.parentNode.currentStyle.borderTopWidth) : 0)
                        - (element.parentNode.currentStyle.borderBottomStyle != "none" ? parseInt(element.parentNode.currentStyle.borderBottomWidth) : 0));
    element.className += " height-processed";
    if(parentHeight <= element.offsetHeight) {
      return 'auto';
    } else {
      var elementHeight = (parentHeight
                           - (element.currentStyle.borderTopStyle != "none" ? parseInt(element.currentStyle.borderTopWidth) : 0)
                           - (element.currentStyle.borderBottomStyle != "none" ? parseInt(element.currentStyle.borderBottomWidth) : 0)
                           - parseInt(element.currentStyle.paddingTop)
                           - parseInt(element.currentStyle.paddingBottom)) + "px";
      return elementHeight;
    }
  } else {
    return element.style.height;
  }
}

function removeProcessedMark(element, processedChildren) {
  var topLevel = typeof(element.srcElement) != "undefined"; // the first loop, element is an event
  if(topLevel) {
    element = document.documentElement;
    processedChildren = new Array();
  }
  if(element.className && element.className.match(/ height-processed/) != null) {
    element.className = element.className.replace(/ height-processed/g, '');
    element.style.height = "";
    processedChildren.push(element);
  }
  if(element.childNodes && element.childNodes.length > 0) {
    for(var index = 0; index < element.childNodes.length; index++) {
      if(element.childNodes.item(index).nodeType == Node.ELEMENT_NODE) {
        removeProcessedMark(element.childNodes.item(index), processedChildren);
      }
    }
  }
  if(topLevel) {
    for(var i = 0; i < processedChildren.length; i++) {
      var height = heightOf(processedChildren[i]);
      processedChildren[i].style.height = height;
    }
  }
}

if(typeof(this.attachEvent) != "undefined") {
  this.attachEvent("onresize", removeProcessedMark);
  this.attachEvent("onload", removeProcessedMark);
}
