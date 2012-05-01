var defaultCount = 10;

function makeTable(containerId, colCount, rowCount) {
  var container = document.getElementById(containerId);
  if(!container) {
    throw "Could not find element: " + containerId;
  }
  if(typeof(colCount) == "undefined") colCount = defaultCount;
  if(typeof(rowCount) == "undefined") rowCount = defaultCount;
  var table = new Array();
  table.element = document.createElement("table");
  container.appendChild(table.element);
  for(var rowIndex = 0; rowIndex < rowCount; rowIndex++) {
    var cols = new Array();
    cols.element = document.createElement("tr");
    for(var colIndex = 0; colIndex < colCount; colIndex++) {
      cols.push(document.createElement("td"));
      cols.element.appendChild(cols[cols.length - 1]);
      addListener(cols[cols.length - 1], "click", cellClicked);
    }
    container.lastChild.appendChild(cols.element);
    table.push(cols);
  }
  return table;
}

function bounce(table) {
  function BounceController(table) {
    this.table = table;
    this.bounds = { "x":table[0].element.offsetWidth,
                    "y":table.element.offsetHeight };
    this.position = { "x":Math.random() * this.bounds.x,
                      "y":Math.random() * this.bounds.y };
    var randomize = function() {
      with(arguments.callee.controller) {
        arguments.callee.controller.velocity =
          { "x":Math.random() * .001 * bounds.x,
            "y":Math.random() * .001 * bounds.y };
        if(typeof(arguments.callee.controller.color) != "undefined") {
          var hue = arguments.callee.controller.color["hue"];
        }
        arguments.callee.controller.color =
          { "hue":(hue ? hue : Math.random() * 360),
            "hueDelta":Math.random() * 10,
            "saturation":.75 + .25 * Math.random(),
            "brightness":.5 * Math.random() };
      }
    }
    randomize.controller = this;
    randomize();
    addListener(table.element, "click", randomize);
    this.axes = {"x":0, "y":1};
    this.stepper = function() {
      arguments.callee.controller.step.call(arguments.callee.controller);
    }
    this.stepper.controller = this;
    table.element.style.position = "relative";
    //table.element.style.display = "inline-table";
    this.dot = document.createElement("div");
    table.element.parentNode.style.position = "relative";
    table.element.parentNode.appendChild(this.dot);
    this.dot.style.position = "absolute";
    try { // this fails in moz 1.7 when loading as xhtml
      this.dot.innerHTML = "&bull;";
    } catch(e) {
      this.dot.appendChild(document.createTextNode("•"));
    }
    this.border = (table.element.offsetWidth - table[0].element.offsetWidth) / 2;
    this.step = function() {
      //alert("Stepping: <" + this.position.x + ", " + this.position.y + ">");
      var timeout = 10;
      /* Frictionless particle bouncing with complete elasticity
       * Both the position and velocity can be altered
       */
      for(axis in this.axes) {
        this.position[axis] += this.velocity[axis] * timeout;
        if(this.position[axis] < 0) {
          this.position[axis] *= -1;
          this.velocity[axis] *= -1;
        }
        if(this.position[axis] > this.bounds[axis]) {
          this.position[axis] = (this.bounds[axis] -
                                 (this.position[axis] % this.bounds[axis]));
          this.velocity[axis] *= -1;
        }
      }
      this.dot.style.left = this.border + this.position.x - 6 + "px";
      this.dot.style.top = this.position.y - 9 + "px";
      var nextCell = cellForPoint(this.table, this.position);
      if(nextCell != this.currentCell) {
        //if(this.currentCell) this.currentCell.className = "";
        this.color.hue += this.color.hueDelta;
        nextCell.style.backgroundColor = getCSSColor(this.color.hue,
                                                     this.color.brightness,
                                                     this.color.saturation);
        this.currentCell = nextCell;
      }
      setTimeout(this.stepper, timeout);
    }
    this.step();
  }
  new BounceController(table);
}

function touch(table) {
  function TouchController(table) {
    this.table = table;
    table.element.parentNode.style.position = "relative";
    this.bounds = { "x":table[0].element.offsetWidth,
                    "y":table.element.offsetHeight };
    this.points = [{ "x":Math.random() * this.bounds.x,
                     "y":Math.random() * this.bounds.y },
                   { "x":Math.random() * this.bounds.x,
                     "y":Math.random() * this.bounds.y }];
    this.border = (table.element.offsetWidth - table[0].element.offsetWidth) / 2;
    this.pointdivs = new Array();
    this.minspacing = 5;
    this.axes = {"x":0, "y":1};

    function randomize(event) {
      minIndex = 0;
      if(event) {
        minIndex = 1;
      }
      with(arguments.callee.controller) {
        for(var i = minIndex; i < points.length; i++) {
          for(axis in axes) {
            points[i][axis] = Math.random() * bounds[axis];
          }
        }
        for(axis in axes) {
          if(points[0][axis] < points[1][axis]) {
            temp = points[0][axis];
            points[0][axis] = points[1][axis];
            points[1][axis] = temp;
          }
        }
        redraw();
      }
    }
    randomize.controller = this;
    addListener(table.element, "click", randomize);

    this.redraw = function() {
      var distance = Math.sqrt(Math.pow(this.points[0].x - this.points[1].x, 2) + 
                               Math.pow(this.points[0].y - this.points[1].y, 2));
      var numpoints = Math.max(2, Math.round(distance / this.minspacing));
      for(var i = this.pointdivs.length; i < numpoints; i++) {
        this.pointdivs[i] = document.createElement("div");
        this.table.element.parentNode.appendChild(this.pointdivs[i]);
        this.pointdivs[i].style.position = "absolute";
        try { // this fails in moz 1.7 when loading as xhtml
          this.pointdivs[i].innerHTML = "&bull;";
        } catch(e) {
          this.pointdivs[i].appendChild(document.createTextNode("•"));
        }
      }
      delta = { "x" : this.points[1].x - this.points[0].x,
                "y" : this.points[1].y - this.points[0].y };
      for(var i = 0; i < numpoints; i++) {
        this.pointdivs[i].style.display = "inline";
        percent = i / (numpoints - 1);
        this.pointdivs[i].style.left =
          this.border + this.points[0].x + delta['x'] * percent - 6 + "px";
        this.pointdivs[i].style.top =
          this.points[0].y + delta['y'] * percent - 9 + "px";
      }
      for(var i = numpoints; i < this.pointdivs.length; i++) {
        this.pointdivs[i].style.display = "none";
      }
    }
  }
  controller = new TouchController(table);
  controller.redraw();
}

function cellForPoint(table, position) {
  /* Assume uniform sized cells */
  var col = Math.floor(position.x / table[0][0].offsetWidth);
  var row = Math.floor(position.y / table[0][0].offsetHeight);
  //alert("indicies: <" + position.x + "," + position.y + "> -> [" + col + "," + row + "]");
  return table[row][col];
}

function cellClicked(event) {
  //alert(relativePosition(event));
}

/* Position within a table of a mouseclick event
 */
function relativePosition(event) {
  var element = getSource(event);
  var x = (event.clientX -
           (element.parentNode.parentNode.offsetLeft     // table
            + (element.parentNode.parentNode.offsetWidth // table
               - element.parentNode.offsetWidth) / 2))   // tr
  var y = (event.clientY -
           element.parentNode.parentNode.offsetTop); // table
  return {"x":x, "y":y};
}
