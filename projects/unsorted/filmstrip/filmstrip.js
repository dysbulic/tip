function getRelativePercentage(container, position, stillBlockWidth) {
  if(typeof(position) == "undefined" || typeof(container) == "undefined") { return 0; }
  position = Math.max(position, 0);
  position = Math.min(position, container.offsetWidth);
  var relativePercentage = 0;
  var relativePosition = position - container.offsetWidth / 2;
  if(Math.abs(relativePosition) > stillBlockWidth / 2) {
    relativePosition -= relativePosition / Math.abs(relativePosition) * stillBlockWidth / 2;
    relativePercentage = relativePosition / ((container.offsetWidth - stillBlockWidth) / 2);
  }
  return relativePercentage;
}

function FilmstripController() {
  // width in pixels of the area where movement stops
  this.deadAreaWidth = 120;
  // state variable
  this.position = 0;

  this.activeThreads = 0;
  this.timeout = 50;
  this.minOpacity = 0;

  this.animationLoop.controller = this;

  // the built-in event interface only applies to DOM objects
  this.scrollListeners = new Array();
}

FilmstripController.prototype.makeStrip = function(id) {
  this.container = document.getElementById(id);

  this.holder = document.createElement("div");
  this.holder.style.position = "relative";
  var width = 0;
  while(this.container.childNodes.length > 0) {
    var cell = this.container.firstChild;
    if(cell.nodeType == Node.ELEMENT_NODE) {
      width += cell.offsetWidth;
      this.container.removeChild(cell);
      this.holder.appendChild(cell);
    } else {
      this.container.removeChild(cell);
    }
  }
  this.holder.style.width = width + "px";
  this.container.appendChild(this.holder);

  // the step is how far the elements are shifted each iteration
  this.step = width / 25;

  // sets the initial position of everything
  this.animationLoop.call(this.animationLoop);
  this.initialized = true;

  var controller = this;
  var mouseHandler =
    function(event) { controller.active = true;
                      controller.mousePosition = event.clientX - controller.container.offsetLeft;
                      if(controller.activeThreads == 0)
                        controller.animationLoop.call(controller.animationLoop); }

  addListener(this.container, "mouseover", mouseHandler);
  addListener(this.container, "mouseout",
              function() { controller.active = false; });
  addListener(this.container, "mousemove", mouseHandler);
}

FilmstripController.prototype.addEventListener = function(type, listener, onbubble) {
  // not sure what to do with onbubble
  if(type == "stripscrolled") {
    this.scrollListeners.push(listener);
  }
}

FilmstripController.prototype.animationLoop = function() {
  var controller = this.controller;
  if(controller.active || !controller.initialized) {
    var relativePosition = getRelativePercentage(controller.container, controller.mousePosition, controller.deadAreaWidth);
    controller.setPosition(controller.position - controller.step * relativePosition);
        
    if(controller.activeThreads++ == 0) {
      var func = this;
      setTimeout(function() { var inFunc = func; inFunc.controller.activeThreads--; inFunc.call(inFunc) }, controller.timeout);
    } else {
      controller.activeThreads--;
    }
  }
}

FilmstripController.prototype.setActiveIndex = function(index) {
  if(index >= this.holder.childNodes.length) {
    throw RangeError("Invalid child index: " + index);
  }
  var position = this.holder.childNodes[index].offsetLeft;
  this.setPosition(position);
}

/**
 * Valid position values are from:
 *   0: center of the leftmost element
 *   max = container.width - element[0].width / 2 - element[n - 1].width / 2
 */
FilmstripController.prototype.setPosition = function(position) {
  position = Math.min(position, (this.holder.offsetWidth
                                 - this.holder.firstChild.offsetWidth / 2
                                 - this.holder.lastChild.offsetWidth / 2));
  position = Math.max(position, 0);
  if(position == this.position) { return; }
  this.position = position;

  position = this.container.offsetWidth / 2 - this.holder.firstChild.offsetWidth / 2 - position;
  this.holder.style.left = position + "px";

  for(var i = 0; i < this.container.firstChild.childNodes.length; i++) {
    var cell = this.container.firstChild.childNodes[i];
    var percent = Math.abs(getRelativePercentage(this.container, position + cell.offsetLeft + cell.offsetWidth / 2, this.deadAreaWidth));
    if(percent == 0) {
      this.activeIndex = i;
      // this is off by 4px; I assume from border issues
      this.activePercent = Math.max(0, -(this.holder.offsetLeft + cell.offsetLeft - 4 - this.container.offsetWidth / 2) / cell.offsetWidth);
    }
    var opacity = Math.max(0, this.minOpacity + (1 - this.minOpacity) * (1 - percent) - .0001); // 100% opacity causes a blink in Safari
    cell.style.opacity = opacity;
    cell.style.filter = "alpha(opacity=" + (opacity * 100) + ")";
  }

  // the built-in event object don't allow setting the target
  var evt = createEvent("stripscrolled", false);
  evt.target = this;
  for(var i = 0; i < this.scrollListeners.length; i++) {
    this.scrollListeners[i](evt);
  }
}
