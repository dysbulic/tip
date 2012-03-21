var doc = { width: 300, height: 300 };
var divisions = 10;
var ns = document.documentElement.getAttribute("xmlns");

function drawGrid() {
  for(var row = 1; row < divisions; row++) {
    var line = document.createElementNS(ns, "line");
    line.setAttribute("x1", row * doc.width / divisions);
    line.setAttribute("x2", row * doc.width / divisions);
    line.setAttribute("y1", doc.height / divisions);
    line.setAttribute("y2", doc.height - doc.height / divisions);
    document.documentElement.appendChild(line);
  }
  for(var col = 1; col < divisions; col++) {
    var line = document.createElementNS(ns, "line");
    line.setAttribute("x1", doc.width / divisions);
    line.setAttribute("x2", doc.width - doc.width / divisions);
    line.setAttribute("y1", col * doc.height / divisions);
    line.setAttribute("y2", col * doc.height / divisions);
    document.documentElement.appendChild(line);
  }
}

function drawBoard(pieces, boardElements) {
  for(var col = 0; col < boardElements.length; col++) {
    for(var row = 0; row < boardElements[col].length; row++) {
      if(typeof(pieces[col][row]) != "undefined") {
        placePiece(pieces[col][row].toLowerCase(), row, col);
      }
      if(typeof(boardElements[col][row]) != "undefined") {
        var text = document.createElementNS(ns, "text");
        var yOffset = row * doc.height / divisions + 2 * doc.height / divisions / 3;
        
        if(typeof(pieces[col][row]) != "undefined") {
          text.setAttribute("class", pieces[col][row].toLowerCase());
        }
        
        text.setAttribute("x", col * doc.width / divisions + doc.width / divisions / 2);
        text.setAttribute("y", yOffset);
        text.appendChild(document.createTextNode(boardElements[col][row]));
        document.documentElement.appendChild(text);
      }
    }
  }
}

var boardElements = new Array(divisions);
var pieces = new Array(divisions);
for(var i = 0; i < boardElements.length; i++) {
  boardElements[i] = new Array(divisions);
  pieces[i] = new Array(divisions);
}
for(var i = 1; i < boardElements.length - 1; i++) {
  boardElements[0][i] = boardElements[divisions - 1][i] = i;
  boardElements[i][0] = boardElements[i][divisions - 1] =
    String.fromCharCode(String.charCodeAt("A") + i - 1);
}

function placePiece(color, row, col) {
  var piece = document.createElementNS(ns, "circle");
  piece.setAttribute("cx", col * doc.width / divisions + doc.width / divisions / 2);
  piece.setAttribute("cy", row * doc.height / divisions + doc.height / divisions / 2);
  piece.setAttribute("r", Math.min(doc.width, doc.height) / divisions / 3);
  piece.setAttribute("class", color);
  document.documentElement.appendChild(piece);
}

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
}

for(key in params) {
  cells = String(params[key]).split(",");
  for(var i = 0; i < cells.length; i++) {
    var match = /(([a-h])(-([a-h]))?)(([1-8])(-([1-8]))?)/.exec(cells[i]);
    var colStart = String.charCodeAt(match[2].toLowerCase()) - String.charCodeAt("a") + 1;
    var colEnd = (typeof(match[4]) != "undefined") ? String.charCodeAt(match[4].toLowerCase()) - String.charCodeAt("a") + 1 : colStart;
    var rowStart = match[6];
    var rowEnd = (typeof(match[8]) != "undefined") ? match[8] : rowStart;
    for(col = colStart; col <= colEnd; col++) {
      for(row = rowStart; row <= rowEnd; row++) {
        if(key.toLowerCase() == "white" || key.toLowerCase() == "black") {
          pieces[col][row] = key.toUpperCase();
        } else {
          boardElements[col][row] = key;
        }
      }
    }
  }
}

drawGrid();
drawBoard(pieces, boardElements);
