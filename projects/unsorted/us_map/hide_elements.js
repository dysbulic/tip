function hideExceptSelected() {
  var url;
  if(document.getURL) {
    url = document.getURL();
  } else if(window.location) {
    url = window.location.href;
  }
  var params = {};
  if(url.indexOf("?") >= 0) {
    var paramStrings = url.substring(url.indexOf("?") + 1).split(/&/);
    for(var i = 0; i < paramStrings.length; i++) {
      var param = unescape(paramStrings[i].replace(/\+/gi, " "));
      var equal_index = param.indexOf("=");
      if(equal_index >= 0) {
        key = param.substring(0, equal_index);
        value = param.substring(equal_index + 1);
      } else {
        key = param;
        value = null;
      }
      if(typeof(params[key]) == "undefined") {
        params[key] = new Array();
      }
      params[key].push(value);
      if(value != null) {
        params[key][value] = true;
      }
    }
  } else {
    /* I can't figure out how to pass in query parameters, to the batik 
     * command line rasterizer, so I will allow for special formats
     * of the filename
     */
    var match = /\/([A-Z][A-Z])((_label(ed)?)?)\.svg$/.exec(url);
    if(match != null) {
      params['show'] = new Array();
      params['show'][match[1]] = true;
      if(match[2] != "") {
        params['showlabels'] = true;
      }
      if(match[3] == "") {
        params['onlylabels'] = true;
      }
    }
  }
  document.getElementsByTagName("svg").item(0).setAttribute("class", "greyed");
  if(typeof(params['showlabels']) != "undefined" || typeof(params['onlylabels']) != "undefined") {
    for(abbr in params['show']) {
      params['show'][abbr + "-label"] = true;
      if(typeof(params['onlylabels']) == "undefined") {
        params['show'][abbr + "-line"] = true;
      } else {
       delete params['show'][abbr];
      }
    }
  }
  if(typeof(params['show']) != "undefined") {
    hideNodes(document.documentElement, params['show']);
  }
}

function hideNodes(parent, exceptions) {
  for(var i = 0; i < parent.childNodes.length; i++) {
    var child = parent.childNodes.item(i);
    if(child.nodeType == Node.ELEMENT_NODE) {
      switch(child.tagName.toLowerCase()) {
      case 'defs':
        break;
      case 'g':
        hideNodes(child, exceptions);
        break;
      default:
        if(typeof("child.id") == "undefined" || typeof(exceptions[child.id]) == "undefined") {
          if(child.style) {
            child.style.setProperty('display', 'none', null);
          }
        } else {
          var className = "active";
          if(child.getAttribute("class") != null && child.getAttribute("class") != "") {
            className = child.getAttribute("class") + " " + className;
          }
          child.setAttribute("class", className);
        }
      }
    }
  }
}

hideExceptSelected();
