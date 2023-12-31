var upArrow = "&uarr;"
var downArrow = "&darr;"
//var noArrow = "&bull;"
var noArrow = "&uarr;&darr;"

if(typeof(Node) == "undefined") {
  Node = new Object();
}

if(typeof(Node.ELEMENT_NODE) == "undefined") {
  Node.ELEMENT_NODE = 1;
}

function addEventListener(element, event, listener) {
  if(typeof(element.addEventListener) != "undefined") {
    element.addEventListener(event, listener, false);
  } else if(typeof(element.attachEvent) != "undefined") {
    element.attachEvent("on" + event, listener);
  }
}

/**
 * Sets up sorting on a particular table. Extracts the table info
 * from the table and makes the headers links to toggle sorting.
 */
function allowSorting(name) {
  var table = new Object();
  table.element = document.getElementById(name);
  
  if(!table.element) {
    alert("Element " + name + " not found");
    return;
  }
  
  if(table.element.nodeName.toLowerCase() != "table") {
    alert("Element " + name + " should be a table");
    return;
  }

  var headers = table.element.getElementsByTagName("thead");
  
  if(headers.length != 1) {
    alert("Table, " + name + ", needs a single <thead> header section");
    return;
  }
  
  var headerRows = headers[0].getElementsByTagName("tr");
  
  if(headerRows.length != 1) {
    alert("Can only handle one header row");
    return;
  }
  
  headers = headerRows[0].childNodes;
  
  for(var i = 0, colCount = 0; i < headers.length; i++) {
    // There are various text nodes that need to be skipped
    if(headers[i].nodeType == Node.ELEMENT_NODE) {
      var title = getData(headers[i].childNodes[0]);
      var link = document.createElement("a");
      link.setAttribute("class", "arrow-box");
      /*
      link.addEventListener("click", sortColumn, null);
      link.addEventListener("mouseout",
                            function(event) { window.status = ""; },
                            null);
      */
      addEventListener(link, "click", sortColumn);
      addEventListener(link, "mouseout",
                       function(event) { window.status = ""; });
      /*
      link.addEventListener("mouseover",
                            function(event) { window.status = "Sort on "; event.stopPropogation(); },
                            null);
      */

      link.table = table;
      link.innerHTML = noArrow;
      link.colIndex = colCount++;
      headers[i].appendChild(link);
    }
  }

  var bodies = table.element.getElementsByTagName("tbody");
  
  if(bodies.length != 1) {
    alert("Table needs a single <tbody> body section");
    return;
  }
  
  table.body = bodies[0];
  var rows = table.body.getElementsByTagName("tr");
  table.rows = new Array();
  
  for(var i = 0; i < rows.length; i++) {
    var row = new Object();
    row.element = rows[i];
    row.data = new Array();
    var children = row.element.getElementsByTagName("td");
    for(var j = 0; j < children.length; j++) {
      row.data.push(getData(children[j]));
    }
    table.rows.push(row);
  }
}

function getData(element) {
  var data = "";
  if(typeof(element) != 'undefined') {
    if(element.nodeType == Node.ELEMENT_NODE) {
      for(var index = 0; index < element.childNodes.length; index++) {
        var elm = getData(element.childNodes[index]);
        if(typeof(elm) != 'undefined' && elm != "undefined") {
          data += new String(elm);
        }
      }
    } else if(element.nodeType == Node.TEXT_NODE ||
              element.nodeType == Node.CDATA_SECTION_NODE &&
              typeof(element.data) != 'undefined') {
      data = element.data;
    } else if(typeof(element.childNodes[index]) != "undefined") {
      alert("Unknown Node Type: " + element.childNodes[index].nodeName);
    } else {
      // IE doesn't implement these interfaces
    }
    if(typeof(data) != 'undefined') {
      data = data.replace(/^[ \t\r\n]+/, "");
      data = data.replace(/^[ \t\r\n]+$/, "");
      var num = new Number(data);
      if(!isNaN(num)) {
        data = num.valueOf();
      }
    }
  }
  return (data == "" ? undefined : data);
}

var sorting = false;
function sortColumn(event) {
  if(sorting) {
    return false;
  } else {
    sorting = true;
  }

  var newLink = (typeof(event.target) != "undefined" ? event.target :
                 (typeof(event.srcElement) != "undefined" ?
                  event.srcElement : undefined));
  if(typeof(newLink) != "undefined") {
    var table = newLink.table;

    var descending = true;
    

    // Check if we sorted on the same column last time
    if(newLink == table.currentLink) {
      descending = !table.descendingSort;
    } else if(typeof(table.currentLink) != 'undefined') {
      table.currentLink.innerHTML = noArrow;
      table.currentLink.setAttribute("id", null);
    }
    newLink.setAttribute("id", "active-arrow");
    
    if(typeof(newLink.sortedColumn) == 'undefined') {
      var rows = table.rows;
      newLink.sortedColumn = new Array();
      for(var index = rows.length - 1; index >= 0; index--) {
        newLink.sortedColumn.push(rows[index]);
      }
      var sortFunction =
        function(a, b) {
          if(a.data[newLink.colIndex] == b.data[newLink.colIndex]) {
            return 0;
          } else if(typeof(b.data[newLink.colIndex]) == "undefined" || a.data[newLink.colIndex] > b.data[newLink.colIndex]) {
            return -1;
          } else {
            return 1;
          }
        };
      sortFunction.index = newLink.colIndex;
      newLink.sortedColumn.sort(sortFunction);
    }
    for(var index = table.body.childNodes.length - 1; index >= 0; index--) {
      table.body.removeChild(table.body.lastChild);
    }
    for(var index = (descending ? newLink.sortedColumn.length - 1 : 0);
        index < newLink.sortedColumn.length && index >= 0;
        index += (descending ? -1 : 1)) {
      table.body.appendChild(newLink.sortedColumn[index].element);
    }
    newLink.innerHTML = descending ? downArrow : upArrow;
    table.currentLink = newLink;
    table.descendingSort = descending;
    
    sorting = false;
  }
}
