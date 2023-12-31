/**
 * This is a set of scripts to make navigating hierarchies
 *  easier. The input to each option is the id of a list
 *  which is consumed and replaced with a dynamic structure.
 */

var collapsedMark = "+";
var expandedMark = "-";

// The css class of the button used for expansion
var expansionClass = "expansion-button";

// Depth that is shown initially
var defaultVisibleDepth = 1;

var timeout = 100;

// setting the height to 0 in IE6 causes the box to expand as though
// height was 100%
var minHeight = 1;

if(typeof(__WJH_COMPAT_LIB) == "undefined" || !__WJH_COMPAT_LIB) {
  alert("Compatability library must be loaded");
}

/**
 * Takes the id of a list of the structure:
 *   <ul id="treeName">
 *     <li><!-- item one data --></li>
 *     <li><!-- item two data -->
 *       <ul>
 *         <li class="myclass"><!-- item two child one data --></li>
 *         <li><!-- item two child one data --></li>
 *       </ul>
 *     </li>
 *     <li><!-- item three data --></li>
 *   </ul>
 * Elements are added to make the list dynamic:
 *   <ul id="treeName">
 *     <li class="leaf"><!-- item one data --></li>
 *     <li class="open node">
 *       <a class="tree-control" href=""><span class="link-text">-</span></a>
 *       <!-- item two data -->
 *       <ul>
 *         <li class="myclass leaf"><!-- item two child one data --></li>
 *         <li class="leaf"><!-- item two child one data --></li>
 *       </ul>
 *     </li>
 *     <li><!-- item three data --></li>
 *   </ul>
 */
function getTree(rootName) {
  var root = document.getElementById(rootName);
  if(!root) {
    alert("Root element of tree not found in page: " + rootName);
  } else {
    if(root.nodeType == Node.ELEMENT_NODE &&
       (root.nodeName.toLowerCase() == "ul" ||
        root.nodeName.toLowerCase() == "ol")) {
      cleanList(root);
    } else {
      alert("Element " + rootName + " not a list: " +
            root.nodeName);
    }
  }
}

/**
 * Takes a list and cleans it to make animation simpler:
 */
function cleanList(root) {
  for(var i = root.childNodes.length - 1; i >= 0; i--) {
    var node = root.childNodes.item(i);
    if(node.nodeType == Node.COMMENT_NODE ||
       (node.nodeType == Node.TEXT_NODE &&
        node.data.match(/^\s*$/m))) {
      root.removeChild(node);
    } else if(node.nodeType == Node.ELEMENT_NODE &&
              node.nodeName.toLowerCase() != "li") {
      alert("List had a child of type: " + node.nodeName);
    } else if(node.nodeType == Node.ELEMENT_NODE &&
              node.nodeName.toLowerCase() == "li") {
      var lists = findLists(node, 1);
      if(lists.length > 0 || node.className.match(/group/)) {
        if(!node.className.match(/\bgroup\b/)) node.className += " group";
        var link;
        var firstChild = undefined;
        for(var j = 0; j < node.childNodes.length && firstChild == undefined; j++) {
          if(node.childNodes.item(j).nodeType == Node.ELEMENT_NODE) {
            firstChild = node.childNodes.item(j);
          }
        }
        if(firstChild != undefined && firstChild.className == "tree-control") {
          link = firstChild;
        } else {
          if(!node.className.match(/\bopen\b/)) node.className += " open";
          link = document.createElement("a");
          link.className = "tree-control";
          link.appendChild(document.createElement("span"));
          link.firstChild.className = "link-text";
          link.firstChild.appendChild(document.createTextNode(expandedMark));
          node.insertBefore(link, node.firstChild);
        }
        if(link.nodeName.toLowerCase() == "a") {
          link.removeAttribute("href");
          addListener(link, "click", toggleNode);
        } else if(link.nodeName.toLowerCase() == "form") {
          if(!link.parentNode.className.match(/\bclosed\b/)) {
            addListener(link, "submit", function(event) { toggleNode(event); killEvent(event); });
          } else {
            link.setAttribute("method", "get");
            addListener(link, "submit", loadNode);
          }
        }
        for(var j = 0; j < lists.length; j++) {
          cleanList(lists[j]);
        }
      } else {
        if(!node.className.match(/leaf/)) node.className += " leaf";
      }
    }
  }
}

/**
 * loads the data for a folder via AJAX
 */
function loadNode(event) {
  var target = getSource(event);
  while(target.nodeName.toLowerCase() != "form" && target.parentNode) {
    target = target.parentNode;
  }
  target.parentNode.className += " loading";
  if(target.nodeName.toLowerCase() != "form") {
    alert("Trying to load content from a: " + target.nodeName);
    return;
  }
  var contentID = undefined;
  var search = new Array(target);
  while(search.length > 0 && contentID == undefined) {
    var currentNode = search.pop();
    for(var i = 0; i < currentNode.childNodes.length && contentID == undefined; i++) {
      var node = currentNode.childNodes.item(i);
      if(node.nodeType == Node.ELEMENT_NODE) {
        search.push(node);
        if(node.getAttribute("name") == "expand[]") {
          contentID = node.getAttribute("value");
        }
      }
    }
  }

  var callback = function(request) {
    if(request.readyState == 4 && request.status == 200) {
      var contents = buildHTMLList(request.responseXML.documentElement);
      contents.style.position = "absolute";
      contents.style.left = "-100%";
      this.target.parentNode.appendChild(contents);
      contents.trueHeight = contents.offsetHeight;
      contents.style.overflow = "hidden";
      contents.style.height = minHeight + "px"; // stupid IE
      contents.style.position = "static";
      contents.style.left = "0";
      this.target.parentNode.className =
        this.target.parentNode.className.replace(/ *\bloading\b/, '');
      treeHandler.toggleNode(this.target);
      removeListener(this.target, "submit", loadNode);
      addListener(this.target, "submit", function(event) { toggleNode(event); killEvent(event); });
    }
  }
  callback.target = target;
  loadXMLDocument("rest_filelist.php?path=" + contentID, callback);
  killEvent(event);
}

function buildHTMLList(filelist) {
  var basePath = filelist.getAttribute("path");
  var root = document.createElement("ul");
  for(var i = 0; i < filelist.childNodes.length; i++) {
    var node = filelist.childNodes.item(i);
    if(node.nodeType == Node.ELEMENT_NODE) {
      var child = document.createElement("li");
      if(node.nodeName == "directory") {
        child.className = "group closed";
        var control = document.createElement("form");
        control.className = "tree-control";
        control.appendChild(document.createElement("div"));
        var container = control.lastChild;
        container.className = "generated"; // this div is too wide in IE
        var input = document.createElement("input"); // if this is added directly in IE, the type is read-only
        input.setAttribute("type", "hidden");
        input.setAttribute("name", "expand[]");
        input.setAttribute("value", basePath + "/" + node.firstChild.data);
        container.appendChild(input);
        input = document.createElement("input");
        input.setAttribute("type", "submit");
        input.setAttribute("value", "");
        container.appendChild(input);
        child.appendChild(control);
        addListener(control, "submit", loadNode);
      } else {
        child.className = "leaf";
      }
      child.appendChild(document.createTextNode(node.firstChild.data));
      root.appendChild(child);
    }
  }
  return root;
}
  

var treeHandler = function() {
  arguments.callee.handle.call(arguments.callee, arguments);
}
treeHandler.activeNodes = new Array();
treeHandler.handle = function() {
  for(var i = this.activeNodes.length - 1; i >= 0; i--) {
    var node = this.activeNodes[i];
    var target = node.target;
    var hiding = target.firstChild.firstChild.data == expandedMark;
    var list = node.lists[(hiding ? node.lists.length - 1 : 0)];
    if(typeof(node.elementIndex) == "undefined") {
      node.elementIndex = (hiding ? list.childNodes.length - 1 : 0);
    }
    list.childNodes[node.elementIndex].style.display = hiding ? "none" : "list-item";
    if(hiding && --node.elementIndex < 0) {
      node.lists.pop();
      node.elementIndex = undefined;
    } else if(!hiding && ++node.elementIndex >= list.childNodes.length) {
      node.lists.shift();
      node.elementIndex = undefined;
    }
    if(node.lists.length == 0) {
      this.activeNodes.splice(i, 1);
      foldNode(target, !hiding);
    }
  }
  if(this.activeNodes.length > 0) {
    setTimeout(treeHandler, timeout);
  }
}
treeHandler.toggleNode = function(target) {
  var nodeHolder = new Object();
  nodeHolder.lists = findLists(target.parentNode);
  nodeHolder.target = target;
  this.activeNodes.push(nodeHolder);
  treeHandler();
}

function foldNode(node, open) {
  if(open) {
    node.parentNode.className = node.parentNode.className.replace(/closed/, "open");
  } else {
    if(!node.parentNode.className.match(/\bopen\b/)) {
      node.parentNode.className += " open";
    }
    node.parentNode.className = node.parentNode.className.replace(/open/, "closed");
  }
  if(node.nodeName.toLowerCase() == "a") {
    node.firstChild.firstChild.data = open ? expandedMark : collapsedMark;
  }
}

treeHandler = function() {
  arguments.callee.handle.call(arguments.callee, arguments);
}
// I wanted to index this array with the elements, but it doesn't work since the
// results of toString() are used to index which produces conflicts.
// treeHandler.heights = new Array();
treeHandler.handle = function() {
  if(this.target == undefined) {
    alert("Undefined");
    return;
  }
  //var hiding = (this.target.firstChild.firstChild.data == expandedMark);
  var hiding = !this.target.parentNode.className.match(/\bclosed\b/);
  delta = this.activeNode.trueHeight * .1;
  if(hiding) {
    delta *= -1;
  }
  var newHeight = this.activeNode.offsetHeight + delta;
  newHeight = Math.min(this.activeNode.trueHeight, Math.max(minHeight, newHeight));
  for(var i = 0; i < this.activeNode.childNodes.length; i++) {
    var child = this.activeNode.childNodes[i];
    if(child.nodeType == Node.ELEMENT_NODE) {
      child.style.position = 'relative';
      child.style.top = '-' + (this.activeNode.trueHeight - newHeight) + "px";
    }
  }
  if(newHeight == this.activeNode.trueHeight) {
    this.activeNode.style.height = "auto";
  } else {
    this.activeNode.style.height = newHeight + "px";
  }
  if(newHeight == minHeight || newHeight == this.activeNode.trueHeight) {
    foldNode(this.target, !hiding);
    this.target = undefined;
  } else {
    setTimeout(treeHandler, timeout);
  }
}
treeHandler.toggleNode = function(target) {
  if(this.target == undefined) {
    var lists = findLists(target.parentNode, 1);
    if(lists.length >= 1) {
      if(lists.length > 1) {
        alert("List had " + lists.length + " children");
      }
      this.target = target;
      this.activeNode = lists[0]; 
      if(this.activeNode.style.height == "" || this.activeNode.style.height == "auto") {
        this.activeNode.trueHeight = this.activeNode.offsetHeight;
      }
      this.activeNode.style.overflow = 'hidden';
      treeHandler();
    }
  }
}

function toggleNode(event) {
  var target = getSource(event);
  while(target.nodeName.toLowerCase() != "a" && target.nodeName.toLowerCase() != "form" && target.parentNode) {
    target = target.parentNode;
  }
  treeHandler.toggleNode(target);
}

var alerting = false;
/**
 * Fills array with any list descendants found through a breadth first search
 */
function findLists(node, maxDepth) {
  var lists = new Array();
  if(node == null) {
    alert("cannot find the children of a null node");
  } else {
    var currentDepth = 0; // depth in the tree currently being searched
    var depthIndex = 0;   // index into the search space marking a move to a new level of the tree
                          // when currentIndex == depthIndex a new depth is started
    var searchSpace = new Array(node); // all subnodes of the tree added breadth first
    for(var currentIndex = 0; currentIndex < searchSpace.length; currentIndex++) {
      if(alerting) alert("looping: [" + currentIndex + "] currDepth:" + currentDepth + " : depthIncrease:" + depthIndex + " : len:" + searchSpace.length);
      if(currentIndex > depthIndex) {
        currentDepth++;
        if(alerting) alert("new level: " + currentDepth);
        depthIndex = searchSpace.length; // this marks the end of the nodes at this depth
        if(currentDepth > maxDepth) {
          if(alerting) alert("maximum depth reached (" + currentDepth + "): breaking");
          break;
        }
      }
      var currentNode = searchSpace[currentIndex];
      if(alerting) alert("Checking " + currentNode.childNodes.length + " nodes in: " + currentNode.nodeName);
      for(var i = 0; i < currentNode.childNodes.length; i++) {
        node = currentNode.childNodes[i];
        // add every non-empty element node to the search space
        if(node.nodeType == Node.ELEMENT_NODE && node.childNodes.length > 0) {
          searchSpace.push(node);
          if(node.nodeName.toLowerCase() == "ul" || node.nodeName.toLowerCase() == "ol") {
            lists.push(node);
          }
        }
      }
    }
  }
  if(alerting) alert("found lists: " + lists.length);
  return lists;
}

function hideBelowDepth(depth, root, currentDepth) {
  currentDepth++;
  if(root.childNodes.length > 0 &&
     root.childNodes[0].childNodes.length > 0) {
    var button = root.childNodes[0].childNodes[0];
    if(depth <= currentDepth) {
      setVisibility(button, false);
    }
    var children = button.treeInfo.children;
    for(var i = 0; i < children.length; i++) {
      hideBelowDepth(depth, children[i], currentDepth);
    }
  }
}
