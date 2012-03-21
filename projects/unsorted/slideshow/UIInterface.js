/**
 * Represents an interface between a Slideshow and the containing page
 */

function UIInterface(container) {
  this.container = container;
  container.tempImageHolder = document.createElement("div");
  container.tempImageHolder.className = "tablecell";
  container.appendChild(container.tempImageHolder);

  this.removeElements = true; // either removeChild or display="none" elements on hide
  this._unloadedObjects = 0;    // number of images requested, but not uploaded
  this.loaded = false;
  this.loadingStyle = new Array();
  this.loadingStyle["border"] = "2px solid red";
  this.loadingStyle["width"] = this.loadingStyle["height"] = "40px";
  this.loadedStyle = new Array();
  this.loadedStyle["border-color"] = "green";
  this.loadErrorStyle = new Array();
  this.loadErrorStyle["border-color"] = "orange";
}
UIInterface.prototype = new Object;

UIInterface.prototype.loadImage = function(filename) {
  var image = new Image();
  for(var prop in this.loadingStyle) {
    setStyleProperty(image, prop, this.loadingStyle[prop]);
  }
  var loadListener = function() { arguments.callee.ui.imageLoaded(arguments.callee.image) }
  var errorListener = function() { arguments.callee.ui.imageError(arguments.callee.error) }
  loadListener.image = errorListener.image = image;
  loadListener.ui = errorListener.ui = this;
  addListener(image, "load", loadListener, false);
  addListener(image, "error", errorListener, false);
  this.incrementObjectCount();
  image.src = filename;
  this.container.tempImageHolder.appendChild(image);
  return image;
}

UIInterface.prototype.imageLoaded = function(image) {
  for(var prop in this.loadedStyle) {
    setStyleProperty(image, prop, this.loadedStyle[prop]);
  }
  this.decrementObjectCount();
}

UIInterface.prototype.imageError = function(image) {
  for(var prop in this.loadErrorStyle) {
    setStyleProperty(image, prop, this.loadErrorStyle[prop]);
  }
  this.decrementObjectCount();
}

UIInterface.prototype.loadHTML = function(filename, info) {
  var callback = function(request) {
    if(request.readyState == 4) {
      if(request.status != 200) {
        alert("Error Loading (" + request.status + "): \"" + request.statusText + "\"");
      } else if(request.responseXML.parseError && request.responseXML.parseError != 0) {
        alert("Parse Error Loading HTML: " + 
              request.responseXML.parseError + 
              " (" + request.responseXML.parseError.srcText + ")");
      } else {
        this.ui.loadDocument(request.responseXML, this.info);
      }
      this.ui.decrementObjectCount();
    }
  }
  callback.info = info;
  callback.info.element.className = "htmlholder";
  callback.ui = this;
  this.incrementObjectCount();
  loadXMLDocument(filename, callback);
  return callback.info.element;
}

UIInterface.prototype.loadDocument = function(loadedDocument, info) {
  if(typeof(info.timings) == "undefined") {
    alert("Error: Timings not specified");
  } else {
    var timings = info.timings;
    for(var i = 0; i < timings.length; i++) {
      /* getElementById is not defined in IE, so use XPath */
      var nodes = selectNodes(loadedDocument, "//*[@id=\"" + timings[i]["id"] + "\"]");
      if(!nodes || nodes.length == 0) {
        alert("Couldn't find: " + timings[i]["id"]);
        timings.splice(i, 1);
      } else {
        timings[i].element = nodes.item(0);
      }
    }
    nodes = selectNodes(loadedDocument, "//html:body", "html", "http://www.w3.org/1999/xhtml");
    if(!nodes || nodes.length == 0) {
      alert("Could not locate html:body element");
    } else {
      var body = nodes.item(0);
      while(body.hasChildNodes()) {
        var child = body.firstChild;
        body.removeChild(child);
        try {
          info.element.appendChild(child);
        } catch(e) {
          info.element.appendChild(document.createElement("div"));
          info.element.lastChild.appendChild(document.createTextNode("Error Loading: " + child.nodeName));
        }
      }
    }
    nodes = selectNodes(loadedDocument, "//html:head", "html", "http://www.w3.org/1999/xhtml");
    if(!nodes || nodes.length == 0) {
      alert("Could not locate html:head element");
    } else {
      var head = nodes.item(0);
      var dochead = document.getElementsByTagName("head").item(0);
      while(head.hasChildNodes()) {
        var child = head.firstChild;
        head.removeChild(child);
        if(child.nodeType == Node.ELEMENT_NODE &&
           (child.nodeName.toLowerCase() == "style" ||
            child.nodeName.toLowerCase() == "link")) {
          try {
            dochead.appendChild(child);
            child.disabled = true;
          } catch(e) {
            info.element.appendChild(document.createElement("div"));
            info.element.lastChild.appendChild(document.createTextNode("Error Loading: " + child.nodeName));
          }
          if(typeof(info.styleSheets) == "undefined") {
            info.styleSheets = new Array();
          }
          info.styleSheets.push(child);
        }
      }
    }
  }
}

UIInterface.prototype.hideElement = function(info) {
  if(this.removeElements) {
    if(nodeIsInDocument(info.element)) {
      info.savedParent = info.element.parentNode;
      info.element.parentNode.removeChild(info.element);
    }
  } else {
    if(typeof(info.savedDisplay) == "undefined") {
      if(typeof(window.getComputedStyle) != "undefined") {
        info.savedDisplay = window.getComputedStyle(info.element, null).display;
      } else if(typeof(info.element.currentStyle) != "undefined") {
        info.savedDisplay = info.element.currentStyle.display;
      } else {
        info.savedDisplay = "inline";
      }
    }
    info.element.style.display = "none";
  }
  if(typeof(info.styleSheets) != "undefined") {
    for(var i = 0; i < info.styleSheets.length; i++) {
      try {
        info.styleSheets[i].disabled = true;
      } catch(e) {}
    }
  }
}

UIInterface.prototype.showElement = function(info) {
  if(this.removeElements) {
    if(!nodeIsInDocument(info.element)) {
      info.savedParent.appendChild(info.element);
    }
  } else {
    info.element.style.display = info.savedDisplay;
  }
  if(typeof(info.styleSheets) != "undefined") {
    for(var i = 0; i < info.styleSheets.length; i++) {
      try {
        info.styleSheets[i].disabled = false;
      } catch(e) {}
    }
  }
}

UIInterface.prototype.incrementObjectCount = function() {
  this._unloadedObjects++;
}

UIInterface.prototype.decrementObjectCount = function() {
  this._unloadedObjects--;
  if(this._unloadedObjects == 0 && slideshow.loaded) { // config is parsed
    this.loaded = true;
    var event = createEvent("Events");
    event.initEvent("load", true, true); //true for can bubble, true for cancelable
    this.dispatchEvent(event);
  }
}

UIInterface.prototype.layoutSlide = function(slide) {
  if(nodeIsInDocument(this.container.tempImageHolder)) {
    this.container.removeChild(this.container.tempImageHolder);
  }
  var events = new Array();
  if(slide.length > 0) {
    var element = document.createElement("div");
    if(typeof(slide.loader) != "undefined") {
      events = slide.loader.call(this, slide, element);
    } else  if(slide.type == "html") {
      for(var i = 0; i < slide.length; i++) {
        element.className = "tablecell single";
        events = this.layoutHTML(slide, element);
      }
    } else { // image slide, no mixed mode slides at this point
      var customLayout = typeof(slide[0].style) != "undefined";
      for(i = 1; i < slide.length; i++) {
        if((customLayout && typeof(slide[i].style) == "undefined") ||
           (!customLayout && typeof(slide[i].style) != "undefined")) {
          // Must all be custom or none
          alert("Mixed table and custom layout not currently supported");
        }
      }
      if(customLayout) {
        events = this.layoutCustomImages(slide, element);
      } else if(slide.length == 1) {
        //element.setAttribute("class", "tablecell single"); // doesn't work in IE6
        element.className = "tablecell single";
        if(nodeIsInDocument(slide[0].image)) {
          slide[0].image.parentNode.removeChild(slide[0].image);
        }
        element.appendChild(slide[0].image);
      } else {
        events = this.layoutImageTable(slide, element);
      }
    }
    for(i = 0; i < slide.length; i++) {
      if(typeof(slide[i].image) != "undefined") {
        for(var prop in this.loadingStyle) {
          setStyleProperty(slide[i].image, prop, null);
        }
      }
    }
    this.container.appendChild(element);
    events.push(new DisplayEvent({ element : element }, slide.startTime, slide.endTime));
  }
  return events;
}

/**
 * Take a slide and lay the elements out in a customlayout div
 */
UIInterface.prototype.layoutCustomImages = function(slide, holder) {
  var events = new Array();
  holder.className = "customlayout";
  for(var i = 0; i < slide.length; i++) {
    var info = slide[i];
    info.element = document.createElement("div");
    info.element.className = "customelm";
    try {
      holder.appendChild(info.element);
    } catch(e) {
      alert("Error: Couldn't add custom image holder");
    }
    if(typeof(slide[i].style) != "undefined") {
      for(var prop in slide[i].style) {
        setStyleProperty(info.element, prop, slide[i].style[prop]);
      }
    }
    if(nodeIsInDocument(slide[i].image)) {
      slide[i].image.parentNode.removeChild(slide[i].image);
    }
    info.element.appendChild(slide[i].image);
    events.push(new DisplayEvent
                (info, slide[i].startTime, slide[i].endTime));
  }
  return events;
}

/**
 * Take a slide and put the elements in a css table
 */
UIInterface.prototype.layoutImageTable = function(slide, holder) {
  var events = new Array();
  holder.className = "multipics";
  var col = undefined;
  var switchIndex = typeof(leftCount) != "undefined" ? leftCount : Math.floor(slide.length / 2);
  for(var index = 0; index < slide.length; index++) {
    var info = slide[index];
    if(index == 0 || index == switchIndex) {
      col = document.createElement("div");
      var className = (index == 0 ? "left" : "right") + "col";
      if(index == 0) {
        className += " elm-" + switchIndex;
      } else {
        className += " elm-" + (slide.length - switchIndex);
      }
      col.className = className;
      holder.appendChild(col);
    }
    info.element = document.createElement("div");
    info.element.className = "innertable";
    col.appendChild(info.element);
    if(typeof(slide[index]) != "undefined") {
      var tablecell = document.createElement("div");
      tablecell.className = "tablecell";
      info.element.appendChild(tablecell);
      if(nodeIsInDocument(slide[index].image)) {
        slide[index].image.parentNode.removeChild(slide[index].image);
      }
      tablecell.appendChild(slide[index].image);
      events.push(new DisplayEvent(info,
                                   slide[index].startTime,
                                   slide[index].endTime));
    }
  }
  return events;
}

UIInterface.prototype.layoutHTML = function(slide, holder) {
  var events = new Array();
  for(var i = 0; i < slide.length; i++) {
    holder.appendChild(slide[i].element);
    events.push(new DisplayEvent(slide[i],
                                 slide.startTime,
                                 slide.endTime));
    for(var j = 0; j < slide[i].timings.length; j++) {
      var timing = slide[i].timings[j];
      events.push(new DisplayEvent(timing, timing.startTime, timing.endTime));
    }
  }
  return events;
}

UIInterface.prototype.addEventListener = function(event, listener, bubble) {
  if(event == "load") {
    if(typeof(this.loadListeners) == "undefined") {
      this.loadListeners = new Array();
    }
    this.loadListeners.push(listener);
  }
}

UIInterface.prototype.dispatchEvent = function(event) {
  if(event.type == "load" && 
     typeof(this.loadListeners) != "undefined") {
    for(var i = 0; i < this.loadListeners.length; i++) {
      this.loadListeners[i].call(this.loadListeners[i], event);
    }
  }
}
