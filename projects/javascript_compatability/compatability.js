/* Compatability layer to get scripts to work on
 *  more than just mozilla.
 */

var __WJH_COMPAT_LIB = true;

// From: http://www.w3.org/TR/2000/REC-DOM-Level-2-Core-20001113/ecma-script-binding.html
if(!Node) {
  var Node = { ELEMENT_NODE : 1,
               ATTRIBUTE_NODE : 2,
               TEXT_NODE : 3,
               CDATA_SECTION_NODE : 4,
               ENTITY_REFERENCE_NODE : 5,
               ENTITY_NODE : 6,
               PROCESSING_INSTRUCTION_NODE : 7,
               COMMENT_NODE : 8,
               DOCUMENT_NODE : 9,
               DOCUMENT_TYPE_NODE : 10,
               DOCUMENT_FRAGMENT_NODE : 11,
               NOTATION_NODE : 12 }
}

/**
 * Returns if event listeners can be added
 */
function supportsEventListeners(element) {
  if(typeof(element) == "undefined") {
    element = this;
  }
  return (typeof(element.addEventListener) != "undefined"
          || typeof(this.attachEvent) != "undefined");
}

/**
 * Returns if elements can be dynamically inserted
 */
function supportsDynamicInsertion() {
  return true;
}

/**
 * Adds a listener that fires on document loading
 */
function addLoadListener(listener, onbubble) {
  if(supportsEventListeners(this)) {
    addListener(this, "load", listener, onbubble);
  } else if(supportsEventListeners(document)) {
    addListener(document, "load", listener, onbubble);
  } else {
    alert("Could not set up load listener");
  }
}

/**
 * Adds an event listener to a component
 */
function addListener(element, event, listener, bubble) {
  if(element.addEventListener) {
    if(typeof(bubble) == "undefined") bubble = false;
    element.addEventListener(event, listener, bubble);
  } else if(this.attachEvent) {
    element.attachEvent("on" + event, listener);
  } else {
    alert("Could not set up event listener");
  }
}

/**
 * Remove an event listener from a component
 */
function removeListener(element, event, listener, bubble) {
  if(element.removeEventListener) {
    if(typeof(bubble) == "undefined") bubble = false;
    element.removeEventListener(event, listener, bubble);
  } else if(this.detachEvent) {
    element.detachEvent("on" + event, listener);
  } else {
    alert("Could not remove event listener");
  }
}

var stylesheetAlerted = false;

/**
 * Add a new rule to the last stylesheet
 */
function addStylesheetRule(selector, declaration, stylesheet) {
  if(typeof(stylesheet) == "undefined" &&
     typeof(document.styleSheets) != "undefined") {
    stylesheet = document.styleSheets[document.styleSheets.length - 1];
  }
  if(typeof(stylesheet) != "undefined") {
    var rules = undefined;
    if(typeof(stylesheet.cssRules) != "undefined") {
      rules = stylesheet.cssRules;
    } else if(typeof(stylesheet.rules) != "undefined") {
      rules = stylesheet.rules;
    }
    if(typeof(rules) != "undefined") {
      if(typeof(stylesheet.insertRule) != "undefined") {
        stylesheet.insertRule(selector + "{" + declarations + "}", rules.length);
        return rules[rules.length - 1].style;
      } else if(typeof(stylesheet.addRule) != "undefined") {
        stylesheet.addRule(selector, declarations);
        return rules[rules.length - 1].style;
      }
    }
  }
  return undefined;
}

/**
 * Add an option to a select
 */
function addOption(text, parent) {
  if(text.length > 0) {
    var option = document.createElement("option");
    option.appendChild(document.createTextNode(text));
    parent.appendChild(option);
  }
}

function createEvent(type, useBuiltIn) {
  if(typeof(document.createEvent) != "undefined" && useBuiltIn != false) {
    return document.createEvent.apply(document, arguments);
  } else {
    var event = new Object();
    event["init" + type.substring(0, type.length - 1)] = function() {
      for(var i = 0; i < arguments.length && i < this.argNames.length; i++)
        this[this.argNames[i]] = arguments[i];
    }
    var baseArgs = new Array("type", "bubbles", "cancelable");
    switch(type) {
      case "UIEvents":
        event.argNames = baseArgs.concat(new Array("view", "detail"));
        break;
      case "MouseEvents":
        event.argNames = baseArgs.concat(new Array
          ("view", "detail", "screenX", "screenY", "clientX", "clientY",
           "ctrlKey", "altKey", "shiftKey", "metaKey", "button", "relatedTarget"));
        break;
      case "MutationEvents":
        event.argNames = baseArgs.concat(new Array
          ("relatedNode", "prevValue", "newValue", "attrName", "attrChange"));
        break;
      default:
        event.argNames = baseArgs;
    }
    return event;
  }
}

/**
 * Get the source of an event
 */
function getSource(event) {
  if(event.target) {
    return event.target;
  } else if(event.srcElement) {
    return event.srcElement;
  } else {
    alert("Could not find event source");
  }
  return null;
}

/**
 * Keep an event from exhibiting its default behavior
 */
function killEvent(event) {
  if(event.preventDefault) {
    event.preventDefault();
  } else { // assume it is IE
    event.returnValue = false;
  }
}

/**
 * Get the form associated with a component that has recieved
 * a submit event
 */
function getForm(submission) {
  // The event source may be the component that
  //  caused the submit or the whole form
  if(submission.form) {
    return submission.form;
  } else if(submission.tagName.toLowerCase() == "form") {
    return submission;
  } else {
    alert("Could not find form element");
    return undefined;
  }
}

/**
 * Print in dialogs the properties an object has
 */
function printProperties(element, skipConstants) {
  var lists = new Array();
  for(property in element) {
    if(skipConstants && property.match(/^[A-Z_0-9]*$/)) continue;

    var name = "element." + property;
    var value = element[property];
    var type = typeof(value);

    if(!lists[type]) {
      lists[type] = type + "s:";
    }

    lists[type] += "\n";

    if(type == "function") {
      lists[type] += name;
    } else {
      lists[type] += name + " => " + value;
    }
  }
  for(type in lists) {
    alert(lists[type]);
  }
}

/**
 * Empty a node
 */
function clearNode(node) {
  while(node.hasChildNodes()) {
    node.removeChild(node.firstChild);
  }
}

/**
 * Append the elements in an array to an element
 */
function copyTo(holder, contents) {
  for(var i = 0; i < contents.length; i++) {
    holder.appendChild(contents[i]);
  }
}

/* Randomizes the elements of an array */
Array.prototype.randomize = function performFisherYates() {
  var i = this.length;
  if(i > 0) {
    while(--i > 0) {
      var j = Math.floor(Math.random() * (i + 1));
      var tempi = this[i];
      var tempj = this[j];
      this[i] = tempj;
      this[j] = tempi;
    }
  }
}

function nodeIsInDocument(node) {
  return (typeof(node) != "undefined" &&
          node != null &&
          typeof(node.parentNode) != "undefined" &&
          node.parentNode != null &&
          node.parentNode.nodeType == Node.ELEMENT_NODE);
}

function setStyleProperty(element, property, value) {
  if(typeof(element.style) == "undefined") {
    alert("Not stylable: " + typeof(element) + ": " + element);
  } else {
    if(element.style.setProperty) {
      element.style.setProperty(property, value, null);
    } else if(element.style.setAttribute) {
      element.style.setAttribute(property, value);
    } else {
      element.style[property] = value;
    }
  }
}

function getCurrentStyle(element) {
  if(element.currentStyle) {
    return element.currentStyle;
  } else if(document.defaultView && document.defaultView.getComputedStyle) {
    return document.defaultView.getComputedStyle(element, '');
  } else {
    return undefined;
  }
}

function setOpacity(element, opacity) {
  
  if(typeof(element.style.opacity) != "undefined") {
    element.style.opacity = opacity;
  } else if(element.style.filter) {
  }
}

function getXMLHttpRequest(callback) {
  var request;
  if(typeof(XMLHttpRequest) != "undefined") {
    request = new XMLHttpRequest();
  } else if(window.ActiveXObject) {
    var msxmlProgids = new Array("MSXML2.XMLHTTP.5.0",
                                 "MSXML2.XMLHTTP.4.0",
                                 "MSXML2.XMLHTTP.3.0",
                                 "MSXML2.XMLHTTP",
                                 "Microsoft.XMLHTTP");
    for(var i = 0; (i < msxmlProgids.length &&
                    typeof(request) == "undefined"); i++) {
      try {
        request = new ActiveXObject(msxmlProgids[i]);
      } catch(e) {
        //alert("Failed to Load: " + msxmlProgids[i]);
      }
    }
  }
  if(typeof(request) != "undefined") {
    setXMLHttpCallback(request, callback);
  }
  return request;
}

function setXMLHttpCallback(request, callback) {
  if(typeof(callback) != "undefined") {
    var handler = function() {
      if(typeof(arguments) != "undefined" &&
         typeof(arguments.callee) != "undefined") {
        arguments.callee.callback.call(arguments.callee.callback,
                                       arguments.callee.request);
      } else if(typeof(this.callback) != "undefined") {
        this.callback.call(this.callback, this.request);
      } else {
        alert("Could not find a callback from loadXMLDocument");
      }
    }
    handler.request = request;
    handler.callback = callback;
    request.onreadystatechange = handler;
    return handler;
  }
}

function loadXMLDocument(url, callback, asynchronous, request) {
  if(typeof(request) == "undefined") {
    request = getXMLHttpRequest();
  }
  setXMLHttpCallback(request, callback);
  asynchronous = typeof(asynchronous) != "undefined" ? asynchronous : true;
  try {
    request.open("GET", url, asynchronous);
    request.send(null);
  } catch(e) {
    alert("For: \"" + url + "\": " + e);
  }
  return request;
}

function selectNodes(document, xpath, namespaceID, namespace) {
  try {
    if(document.evaluate) {
      var resolver = null;
      if(namespace) {
        resolver = { normalResolver: document.createNSResolver(document.documentElement),
                     lookupNamespaceURI: function(prefix) {
                       switch(prefix) {
                       case namespaceID: return namespace;
                       default: return this.normalResolver.lookupNamespaceURI(prefix);
                       }
                     }
                   }
      }
      var nodes = document.evaluate(xpath, document, resolver,
                                    XPathResult.ORDERED_NODE_SNAPSHOT_TYPE, null);
      nodes.length = function() { return this.snapshotLength }
      nodes.item = function(index) { return this.snapshotItem(index) }
    } else if(document.documentElement &&
              typeof(document.documentElement.selectNodes) != "undefined") {
      if(namespace) {
        document.setProperty("SelectionNamespaces",
                             "xmlns:" + namespaceID + "='" + namespace + "'");
      }
      document.setProperty("SelectionLanguage", "XPath");
      nodes = document.documentElement.selectNodes(xpath);
    } else {
      alert("Could not select XPath: " + xpath + " on " + document);
    }
  } catch(e) {
    alert("[" + xpath + "]: " + e);
  }
  return nodes;
}

function getCookie() {
  var values = new Array();
  var cookieParts = document.cookie.split(/ *; */);
  for(var i = 0; i < cookieParts.length; i++) {
    var equalsIndex = cookieParts[i].indexOf("=");
    if(equalsIndex > 0) {
      var name = cookieParts[i].substring(0, equalsIndex);
      values[name] = unescape(cookieParts[i].substring(equalsIndex + 1));
    }
  }
  return values;
}

function setCookie(values, expiration) {
  if(typeof(expiration) == "undefined") {
    expiration = 1; // one day
  }
  if(typeof(values['expires']) == "undefined") {
    var expirationDate = new Date(); 
    expirationDate.setTime(expirationDate.getTime() + (expiration * 24 * 60 * 60 * 1000));
    values['expires'] = expirationDate.toGMTString();
  }
  var cookieValue = "";
  for(value in values) {
    cookieValue += value + "=" + values[value] + ";";
  }
  document.cookie = cookieValue;
}
