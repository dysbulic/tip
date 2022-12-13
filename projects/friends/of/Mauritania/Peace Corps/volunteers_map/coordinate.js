/**
 * Javascript class for representing coordinates in a 2D
 *  geospaitial rectangle. The x-axis is E/W and the
 *  y-axis is N/S.
 */
function Coordinate(direction, degrees, minutes, seconds) {
  switch(direction) {
  case "latitude": this.direction = "N";  break;
  case "longitude": this.direction = "E"; break;
  default:          this.direction = direction;
  }
  // Convert to seconds and back to catch strange quantities
  //  in each position, like 43.5 54.2' 4"
  degrees = ((degrees ? parseFloat(degrees) : 0) * 60 * 60 +
             (minutes ? parseFloat(minutes) : 0) * 60 +
             (seconds ? parseFloat(seconds) : 0));
  this.seconds = degrees % 60 + degrees - parseInt(degrees);
  degrees = parseInt(degrees / 60);
  this.minutes = degrees % 60;
  this.degrees = parseInt(degrees / 60);
}

Coordinate.prototype.getNormalForm = function() {
  /**
   * Coordinates are coming in direction/degreee/minute/second
   *  form. For example N 37,23,43.2 where the bounds are:
   *   direction = {E, W}       | direction = {N, S}
   *   degree % 360 = degree    | 0 <= degree <= 90
   *   60 minute = 1 degree
   *   60 second = 1 minute
   * To make comparisons easier it would be good for everything
   *  to be in a consistent form, so I this function takes a
   *  coordinate and either makes it into N (latitude) or E
   *  (longitude) and changes the figure to decimal degrees.
   */
  var degrees = this.degrees + this.minutes / 60 + this.seconds / (60 * 60);

  switch(this.direction) {
  case 'S':  case 's':
    degrees = -degrees;
  case 'N':  case 'n':
    break;
  case 'W':  case 'w':
    degrees = -degrees;
  case 'E':  case 'e':
    break;
  default:
    // Unknown direction
  }
  return degrees;
}

Coordinate.denormalize = function (axis, degrees) {
  var coordinate = new Coordinate();

  if(degrees < 0) {
    degrees = -degrees;
    switch(axis) {
    case "longitude": coordinate.direction = "W"; break;
    default:          coordinate.direction = "S"; break;
    }
  } else {
    switch(axis) {
    case "longitude": coordinate.direction = "E"; break;
    default:          coordinate.direction = "N"; break;
    }
  }
  coordinate.degrees = Math.floor(degrees);
  degrees = (degrees - coordinate.degrees) * 60;
  coordinate.minutes = Math.floor(degrees);
  degrees = (degrees - coordinate.minutes) * 60;
  coordinate.seconds = Math.round(degrees * 100) / 100;

  return coordinate;
}

Coordinate.prototype.getUserPoint = function (geospace, viewbox) {
  switch(this.direction) {
  case "E": case "W":
    return ((this.getNormalForm() - geospace.x) *
            viewbox.width / geospace.width);
  case "N": case "S":
    // The increasing direction is flipped between the two spaces
    return ((geospace.y + geospace.height - this.getNormalForm()) *
            viewbox.height / geospace.height);
  }
}

Coordinate.prototype.toString = function() {
  return (this.direction + this.degrees + "\u00B0 " +
          this.minutes + "\' " + Math.round(this.seconds) + "\"");
}

Coordinate.fromXML = function(element) {
  return new Coordinate((element.hasAttribute("direction") ?
                         element.getAttribute("direction") :
                         element.nodeName),
                        element.getAttribute("degree"),
                        element.getAttribute("minute"),
                        element.getAttribute("second"));
}
