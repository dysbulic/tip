/**
 * Function takes a html element and an array of lines. Each line
 * is either 2 or four points. If it is 2 it is drawn as a line, 4
 * as a bezier curve.
 */
function draw_lines(canvas, lines, dots) {
  mindistance = 5;
  for(var i = 0; i < lines.length; i++) {
    dots = draw_line(canvas, lines[i][0], lines[i][1], mindistance, dots);
  }
  return dots;
}
 
function draw_line(canvas, startPoint, endPoint, minspacing, dots) {
  if(!dots) dots = new Array();
  if(typeof(dots.activeindex) == "undefined") dots.activeindex = 0;

  var distance = Math.sqrt(Math.pow(startPoint[0] - endPoint[0], 2) + 
                           Math.pow(startPoint[1] - endPoint[1], 2));
  var numpoints = Math.max(2, Math.round(distance / minspacing));
  for(var i = dots.length; i < dots.activeindex + numpoints; i++) {
    dots[i] = document.createElement("div");
    canvas.appendChild(dots[i]);
    dots[i].style.position = "absolute";
    dots[i].appendChild(document.createTextNode("•"));
  }
  delta = { "x" : endPoint[0] - startPoint[0],
            "y" : endPoint[1] - startPoint[1] };
  for(var i = 0; i < numpoints; i++) {
    percent = i / (numpoints - 1);
    dots[dots.activeindex + i].style.display = "inherit";
    dots[dots.activeindex + i].style.left =
      startPoint[0] + delta['x'] * percent - 6 + "px";
    dots[dots.activeindex + i].style.top =
          startPoint[1] + delta['y'] * percent - 9 + "px";
  }
  if(dots[dots.activeindex]) {
    dots[dots.activeindex].setAttribute("class", "corner");
    dots.activeindex += numpoints - 1;
    dots[dots.activeindex].setAttribute("class", "corner");
  }
  for(var i = dots.activeindex; i < dots.length; i++) {
    dots[i].style.display = "none";
  }
  return dots;
}

function translate_curve(curve, offset) {
  for(i = 0; i < curve.length; i++) {
    for(j = 0; j < curve[i].length; j++) {
      curve[i][j][0] += offset['x'];
      curve[i][j][1] += offset['y'];
    }
  }
}

function scale_curve(curve, scale) {
  if(!scale['y']) scale['y'] = scale['x'];
  if(!scale['x']) scale['x'] = scale['y'];
  for(i = 0; i < curve.length; i++) {
    for(j = 0; j < curve[i].length; j++) {
      curve[i][j][0] *= scale['x'];
      curve[i][j][1] *= scale['y'];
    }
  }
}

/**
 * Rotates the coordinate system for the curve around
 *  the specfied point the specified number of radians
 */
function rotate_curve(curve, angle, point) {
  if(!point) {
    point = {x:0,y:0};
  }
  for(i = 0; i < curve.length; i++) {
    for(j = 0; j < curve[i].length; j++) {
      d = Math.sqrt(Math.pow(curve[i][j][0] - point.x, 2) +
                    Math.pow(curve[i][j][1] - point.y, 2));
      if(d != 0) {
        if(curve[i][j][0] == point.x) {
          a = 0;
        } else {
          a = Math.atan((curve[i][j][1] - point.y) / (curve[i][j][0] - point.x));
        }
        if(curve[i][j][0] < point.x) {
          a += Math.PI;
        } else if(curve[i][j][1] < point.y) {
          a += 2 * Math.PI;
        }
        a += angle;
        curve[i][j][0] = point.x + d * Math.cos(a);
        curve[i][j][1] = point.y + d * Math.sin(a);
      }
    }
  }
}

/**
 * Make a set of curves into lines
 */
function make_lines(curve, steps) {
  var out = new Array();
  for(var i = 0; i < curve.length; i++) {
    if(curve[i].length == 2) {
      out.push(curve[i]);
    } else if(curve[i].length == 4) {
      currentPoint = curve[i][0];
      if(!steps) steps = 10;
      controls = new Array(new Object(),new Object(),new Object(),new Object());
      for(axis = 0; axis <= 1; axis++) {
        controls[0][axis] = (-curve[i][0][axis] + 3 * curve[i][1][axis]
                             - 3 * curve[i][2][axis] + curve[i][3][axis]);
        controls[1][axis] = (3 * curve[i][0][axis] - 6 * curve[i][1][axis]
                             + 3 * curve[i][2][axis]);
        controls[2][axis] = -3 * curve[i][0][axis] + 3 * curve[i][1][axis];
        controls[3][axis] = curve[i][0][axis];
      }
      for(j = 1; j <= steps; j++) {
        t = j / steps;
        nextPoint = new Object();
        for(axis = 0; axis <= 1; axis++) {
          nextPoint[axis] = (((controls[0][axis] * t) + controls[1][axis]) * t
                             + controls[2][axis]) * t + controls[3][axis];
        }
        out.push(new Array(currentPoint, nextPoint));
        currentPoint = nextPoint;
      }
    } else {
      alert("Line with wrong number of points: " + curve[i].length);
    }
  }
  return out;
}

function parseSVGPath(path) {
  path = path.split(/( +|,|[A-Z]|z)/);
  var current = {x:0, y:0};
  var lastMove = {x:0, y:0};
  var counts = {c:4, l:2, m:1, z:0};
  var expecting = 0;
  var point = new Array();
  var curve = new Array();
  var out = new Array();
  var action = "";
  for(var i = 0; i < path.length; i++) {
    if(path[i].match(/[a-zA-Z]/)) {
      if(expecting > 0 && expecting != counts[action.toLowerCase()]) {
        alert("Too few points in: \"" + action + "\"; dropping action." +
              " Still expecting: " + expecting);
      }
      action = path[i];
      expecting = counts[action.toLowerCase()];
      if(action == "z")
        out.push(new Array(new Array(current.x,current.y),
                           new Array(lastMove.x,lastMove.y)));
    } else if(path[i].match(/^[0-9]+\.?[0-9]*$/)) {
      point.push(new Number(path[i]).valueOf());
    
      if(point.length == 2) {
        expecting--;
        if(expecting < 0) {
          alert("Too many points in: \"" + action + "\"; dropping point: "+
                "<" + point[0] + ", " + point[1] + ">");
        } else if(expecting == 0 && action == "M") {
              lastMove.x = current.x = point[0];
              lastMove.y = current.y = point[1];
              point.length = 0;
        } else {
          if(curve.length == 0) {
            expecting--;
            curve.push(new Array(current.x,current.y));
          }
          curve.push(point);
          if(expecting == 0) {
            out.push(curve);
            current.x = curve[curve.length - 1][0];
            current.y = curve[curve.length - 1][1];
            curve = new Array();
            expecting = counts[action.toLowerCase()];
          }
        }
        point = new Array();
      }
    } else if(path[i] == "" || path[i].match(/( +|,)/)) {
    } else {
      alert("Didn't recognize element: \"" + path[i] + "\"");
    }
  }
  return out;
}
