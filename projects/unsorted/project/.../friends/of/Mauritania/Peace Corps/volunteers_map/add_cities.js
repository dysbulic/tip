document.rootElement.addEventListener("mousemove", show_coordinate, false);

/* Add a text area to display the coordinates
 */

var viewbox = get_viewbox();

/* Add the background for the coordinates display
 */
var coords_back = document.createElement("rect");
coords_back.setAttribute("id", "coords-back");
coords_back.setAttribute("x", .05 * viewbox.width);
coords_back.setAttribute("y", .9 * viewbox.height);
coords_back.setAttribute("width", .2 * viewbox.width);
coords_back.setAttribute("height", "2.25em");
document.rootElement.appendChild(coords_back);

/* Add text for the coordinates
 */
var coord_text = document.createElement("text");
coord_text.setAttribute("id", "coords");
coord_text.setAttribute("x", coords_back.getAttribute("x"));
coord_text.setAttribute("y", coords_back.getAttribute("y"));
coord_text.setAttribute("width", coords_back.getAttribute("width"));

document.rootElement.appendChild(coord_text);

var lat_text = document.createTextNode("");
coord_text.appendChild(document.createElement("tspan"));
coord_text.lastChild.appendChild(lat_text);
coord_text.lastChild.setAttribute
  ("x", parseFloat(coord_text.getAttribute("x")) + coord_text.getAttribute("width") / 2);
coord_text.lastChild.setAttribute("dy", "1em");

var lon_text = document.createTextNode("");
coord_text.appendChild(document.createElement("tspan"));
coord_text.lastChild.appendChild(lon_text);
coord_text.lastChild.setAttribute
  ("x", parseFloat(coord_text.getAttribute("x")) + coord_text.getAttribute("width") / 2);
coord_text.lastChild.setAttribute("dy", "1em");

var cities = new Array();
var geospace = document.rootElement.createSVGRect();

//load_file(get_link("geodata"), load_cities);
getURL(get_link("geodata"),
  function (status) {
    if(status.success) { load_cities(parseXML(status.content)) }
    else { alert("Could not load geodata"); }});

function load_cities(country_doc) {
  var bounds = { latitude: new Array(), longitude: new Array() };
  var bounds_elm = country_doc.getElementsByTagName("bounds").item(0);
  for(var bnd_index = 0; bnd_index < bounds_elm.childNodes.length; bnd_index++) {
    if(bounds_elm.childNodes.item(bnd_index).nodeType == 1) { // Node.ELEMENT_NODE not defined
      var position = bounds_elm.childNodes.item(bnd_index);
      var coord = Coordinate.fromXML(position);
      bounds[position.nodeName].push(coord);
    }
  }

  if(bounds.latitude.length != 2 || bounds.longitude.length != 2) {
    alert("Improper bounds in country definition");
  } else {
    geospace.x = Math.min(bounds.longitude[0].getNormalForm(),
                          bounds.longitude[1].getNormalForm());
    geospace.width = Math.max(bounds.longitude[0].getNormalForm(),
                              bounds.longitude[1].getNormalForm()) - geospace.x;
    geospace.y = Math.min(bounds.latitude[0].getNormalForm(),
                          bounds.latitude[1].getNormalForm());
    geospace.height = Math.max(bounds.latitude[0].getNormalForm(),
                               bounds.latitude[1].getNormalForm()) - geospace.y;
  }

  var city_group = document.createElement("g");
  city_group.setAttribute("id", "cities");
  document.rootElement.appendChild(city_group);

  var cities_menu = null;
  if(contextMenu) {
    contextMenu.firstChild.appendChild(contextMenu.createElement("separator"));
    cities_menu = contextMenu.createElement("menu");
    cities_menu.appendChild(contextMenu.createElement("header"));
    cities_menu.lastChild.appendChild(contextMenu.createTextNode("Cities"));
    contextMenu.lastChild.appendChild(cities_menu);
  }

  var city_elms = country_doc.getElementsByTagName("city");
  for(var cty_index = 0; cty_index < city_elms.length; cty_index++) {
    var name = city_elms.item(cty_index).getAttribute("name");
    cities[name] = new Array();
    cities[name].name = name;
    cities[name].element = document.createElement("circle");
    cities[name].element.setAttribute("id", name);
    cities[name].element.setAttribute("r", ".5%");
    city_group.appendChild(cities[name].element);

    var city_elm = city_elms.item(cty_index).childNodes;
    for(var prp_index = 0; prp_index < city_elm.length; prp_index++) {
      if(city_elm.item(prp_index).nodeType == 1) { // Node.ELEMENT_NODE not defined
        switch(city_elm.item(prp_index).nodeName) {
        case "longitude":
          cities[name].longitude = Coordinate.fromXML(city_elm.item(prp_index));
          break;
        case "latitude":
          cities[name].latitude = Coordinate.fromXML(city_elm.item(prp_index));
          break;
        }
      }
    }
  }
  place_cities();

  /* I want the elements in alphabetic order in the menu. Getting
   *  that order out of the associative indicies isn't possible,
   *  so put them in an indexed array and sort them.
   */
  var city_names = new Array();
  for(var city in cities) {
    city_names.push(city);
  }
  city_names.sort();
  for(var city_index = 0; city_index < city_names.length; city_index++) {
    var name = city_names[city_index];
    cities[name].element.addEventListener("mouseover", city_over, false);
    cities[name].element.addEventListener("mouseout", city_out, false);
    cities[name].element.addEventListener("click", city_click, false);
    if(cities_menu != null) {
      cities_menu.appendChild(contextMenu.createElement("item"));
      cities_menu.lastChild.setAttribute("onmouseover", "show_city(\"" + name + "\")");
      cities_menu.lastChild.setAttribute("onactivate", "show_city(\"" + name + "\")");
      cities_menu.lastChild.appendChild(contextMenu.createTextNode(name));
    }
  }
  return cities;
}

function place_cities() {
  var lost_count = 0;
  for(var name in cities) {
    if(typeof(cities[name].longitude) == "undefined" ||
       typeof(cities[name].latitude) == "undefined") {
      cities[name].element.setAttribute("cx", 0);
      cities[name].element.setAttribute("cy", ++lost_count + "em");
    } else {
      var pos = cities[name].longitude.getUserPoint(geospace, viewbox);
      cities[name].element.setAttribute("cx", pos);
      pos = cities[name].latitude.getUserPoint(geospace, viewbox);
      cities[name].element.setAttribute("cy", pos);
    }
  }
}
  
function show_coordinate(event) {
  var point = get_user_point(event);
  point.x *= geospace.width / viewbox.width;
  point.y *= geospace.height / viewbox.height;
  lon_text.data = Coordinate.denormalize("longitude", geospace.x + point.x).toString();
  lat_text.data =
    Coordinate.denormalize("latitude", geospace.y + geospace.height - point.y).toString();
}

//document.rootElement.addEventListener("keydown", move_bounds, false);
focus();

function move_bounds(event) {
  /* This is to allow moving the bounds so they can be more easliy
   *  positioned.
   * The arrow keys are used. The default is the x/y coordinates.
   *  When shift is held it changes the width/height. Meta makes
   *  it jump by a factor of 10.
   */
  var delta = event.metaKey ? .25 : .05;
  if(event.keyCode == 38 || event.keyCode == 39) {
    delta *= -1;
  }
  switch(event.keyCode) {
  case 37: // left arrow
  case 39: // right arrow
    if(!event.shiftKey) {
      geospace.x += delta;
    } else {
      geospace.width += delta;
    }
    break;
  case 38: // up arrow
  case 40: // down arrow
    if(!event.shiftKey) {
      geospace.y += delta;
    } else {
      geospace.height += delta;
    }
    break;
  }
  if(event.keyCode == 80) { // p key
    alert("Lon bounds: = " + geospace.x + ", " + (geospace.x + geospace.width) + "\n" +
          "Lat bounds: = " + geospace.y + ", " + (geospace.y + geospace.height));
  }
  place_cities();
  focus();
}
