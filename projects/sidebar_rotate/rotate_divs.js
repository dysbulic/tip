if(typeof(maxOpacity) == "undefined") {
  // Maximum permitted opacity
  // Going to 100% opacity in Firefox 1.5.03 switches the rendering mode in some way causing a
  // color change in OSX and blinking in XP. In Opera it causes the image to move.
  var maxOpacity = .99;
}

function rotate_divs(id, pause, initialPause) {
  var state = new Array();

  if(typeof(pause) != "undefined") {  // Length of time to display in milliseconds
    state.pause = pause;
  } else {
    if(typeof(visiblePause) != "undefined") {
      state.pause = visiblePause;
    } else {
      state.pause = 20000;      // 20 seconds
    }
  }
  if(typeof(initialPause) == "undefined") {
    initialPause = state["pause"]; // Length of time to display initially in milliseconds
  }

  state.elements = new Array();

  /**
   * Kintera is changing how they generate content listings. It seems to be changing, so
   * this just searches for the first element with more than one element child.
   */
  var parent = document.getElementById(id);
  do {
    var childCount = 0;
    var elementChild = undefined;
    for(var i = 0; i < parent.childNodes.length; i++) {
      var node = parent.childNodes[i];
      if(node.nodeType == Node.ELEMENT_NODE && node.nodeName.toLowerCase() != "link") {
        childCount++;
        elementChild = node;
      }
    }
    if(childCount == 1) parent = elementChild;
  } while(childCount == 1);

  var maxHeight = 0;
  var maxWidth = 0;
  for(var i = parent.childNodes.length - 1; i >= 0; i--) {
    var node = parent.childNodes[i];
    if(node.nodeType == Node.ELEMENT_NODE) {
      state.elements.push(node);
      maxHeight = Math.max(maxHeight, node.offsetHeight);
      maxWidth = Math.max(maxWidth, node.offsetWidth);
      parent.removeChild(node);
    }
  }
  state.backdrop = document.createElement("div");
  state.backdrop.style.height = maxHeight + "px";
  state.backdrop.style.widht = maxWidth + "px";
  state.backdrop.style.position = "relative";
  parent.appendChild(state.backdrop);

  state.holders = new Array(document.createElement("div"), document.createElement("div"));
  for(var i = 0; i < state.holders.length; i++) {
    state.holders[i].className = "holder";
    state.holders[i].style.position = "absolute";
    state.holders[i].style.top = "0";
    state.backdrop.appendChild(state.holders[i]);
  }
  state.activeHolderIndex = 0;

  state["elements"].randomize();
  state.currentIndex = 0;
  state.threadId = 1;
  addListener(state["backdrop"], "mouseover", function() { holderHovered(state) });
  addListener(state["backdrop"], "mouseout", function() { holderLeft(state) });
  updateElement(state.threadId, state);
}

function holderHovered(state) {
  state.hovered = true;
  if(state.state != "showing") {
    var tId = ++state.threadId;
    showElement(tId, state);
  }
}

function holderLeft(state) {
  state.hovered = false;
  var tId = ++state.threadId;
  hideElement(tId, state);
}

function hideElement(threadId, state) {
  if(state.state != "hiding" && !state.hovered) {
    state.state = "hiding";
    updateElement(threadId, state);
  }
}

function showElement(threadId, state) {
  if(state.state != "showing") {
    state.state = "showing";
    updateElement(threadId, state);
  }
}

/**
 * The threadId is needed because it is possible to spawn off a new timeout while one
 * is waiting. This guarantees that only the most recently spawned timeout will be
 * processed.
 *
 * There are two elements overlaid over each other at any given time: the active
 * element and the rising element.
 *
 * This function is called to update the elements opacity. The basic logic is this:
 * Start: active.opacity = 1; rising.opacity = 0
 * Hiding: active.opacity -= .05; rising.opacity = 1 - active.opacity
 * Hidden: active.opacity = 0; rising.opacity = 1
 * Switch: active = rising; rising = next; 
 * Paused
 * Start again
 */
function updateElement(threadId, state) {
   // if this doesn't match then another thread has run while this thread was waiting
  if(threadId == state.threadId) {
    var newState = state.state;
    var timeout = state.pause;
    var activeHolder = state.holders[state.activeHolderIndex];
    var risingIndex = (state.activeHolderIndex + 1) % state.holders.length;
    var risingHolder = state.holders[risingIndex];

    if(typeof(state.state) == "undefined" || state.state == "prepause") {
      var nextIndex = (state.currentIndex + 1) % state.elements.length;
      var activeChild = state.elements[state.currentIndex];
      var risingChild = state.elements[nextIndex];
      state.currentIndex = nextIndex;
      nextIndex = (state.currentIndex + 1) % state.elements.length;
      var nextChild = state.elements[nextIndex];

      debug("Changing: <-- " + activeChild.id + " (" + activeHolder.style.opacity + ") <<" +
            " >> " + risingChild.id + " (" + risingHolder.style.opacity + ") : " + nextChild.id + " -->");

      /* I can't remove the div and change the holder. This causes a blink. The active
       * element is changed by giving it the high z-order and adding the next element
       * to the next holder.
       */
      
      for(var i = 0; i < state.holders.length; i++) {
        state.holders[i].style.zIndex = (i == risingIndex ? 2 : 1);
      }

      activeHolder.style.opacity = 0;
      risingHolder.style.opacity = maxOpacity;

      if(activeChild.parentNode) activeChild.parentNode.removeChild(activeChild);
      if(!risingChild.parentNode) risingHolder.appendChild(risingChild);
      state.activeHolderIndex = risingIndex;
      state.holders[(state.activeHolderIndex + 1) % state.holders.length].appendChild(nextChild);

      newState = "hiding";
    } else {
      var currentOpacity = parseFloat(activeHolder.style.opacity);
      var newOpacity = 0;
      if(state.state == "hiding") {
        newOpacity = Math.max(currentOpacity - .05, 0);
      } else if(state.state == "showing") {
        newOpacity = Math.min(currentOpacity + .05, maxOpacity);
      } else {
        debug("Unknown state: " + state.state);
      }
      newOpacity = Math.round(newOpacity * 100) / 100;
      var inverseOpacity = Math.pow(maxOpacity - newOpacity, 2);
      inverseOpacity = Math.round(inverseOpacity * 100) / 100;
      
      //debug("Opacity: " + risingHolder.firstChild.id + "/" + activeHolder.firstChild.id + " = " + 
      //      risingHolder.style.opacity + "/" + currentOpacity + " -> " +
      //      inverseOpacity + "/" + newOpacity + " (" + state.state + ")");

      activeHolder.style.opacity = newOpacity;
      activeHolder.style.filter = "alpha(opacity=" + (newOpacity * 100) + ")";
      risingHolder.style.opacity = inverseOpacity;
      risingHolder.style.filter = "alpha(opacity=" + ((inverseOpacity) * 100) + ")";
      if(newOpacity == 0) { // finished hiding
        newState = "prepause";
      } else if(newOpacity == maxOpacity && !state.hovered) { // finished showing
        newState = "hiding";
      }
      timeout = 100;
      if(state.hovered) {
        timeout = 1;
      } else if(state.state == "showing") {
        timeout = 50;
      }
    }
    if(newState != state.state) {
      debug("Transition: " + state.state + " -> " + newState + " (" + timeout + ")");
      state.state = newState;
    }
    var tId = ++state.threadId;
    setTimeout(function() { updateElement(tId, state) }, timeout);
  } else {
    debug("Skipped Thread: " + threadId + " / " + state.threadId);
  }
}

function debug(message) {
  var body = document.getElementsByTagName('body')[0];
  var holder = document.createElement('div');
  holder.appendChild(document.createTextNode(message));
  body.appendChild(holder);
}
