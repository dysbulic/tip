function get_link(relation) {
  var xhtmlns = "http://www.w3.org/1999/xhtml";
  var link_elms = document.getElementsByTagNameNS(xhtmlns, "link");
  
  for(var index = 0; index < link_elms.length; index++) {
    if(link_elms.item(index).getAttributeNS(xhtmlns, "rel") == relation) {
      return link_elms.item(index).getAttributeNS(xhtmlns, "href");
    }
  }
  alert("Could not find \"" + relation + "\" link in document");
}

var handlers = new Array();

function load_file(source, handler) {
  if(typeof(document.createDOMParser) == 'function') {
    var parser = document.createDOMParser
      (DOMImplementationLS.MODE_SYNCHRONOUS, null);
    handler(parser.parseURI(source));
  } else if(typeof(getURL) == "function") {
    /*
    var callback =
      function (status) {
        this.handler = handler;
        this.source = source;
        if(status.success) {
          this.handler(parseXML(status.content));
        } else {
          alert("Error loading: " + this.source);
        }
      };
    */
    eval("var callback = " +
         "  function (status) {" +
         "    if(status.success) {" +
         "      handlers[" + handlers.length + "](parseXML(status.content));" +
         "    } else {" +
         "      alert('Error loading: " + source + "');" +
         "    }" +
         "  }");
    handlers.push(handler);
    getURL(source, callback);
  }
}
