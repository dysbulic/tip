var display = new Object();
display.line_height = 2;
display.margin = new Object();         // Margin is on the outside
display.margin.x = viewbox.width * .01;
display.margin.y = viewbox.height * .035;
display.padding = new Object();        // Padding is on the inside
display.padding.x = display.margin.x;
display.padding.y = display.padding.x;
display.width = viewbox.width * .3;
display.roundness = ".5%";

/* Can't figure out how to get all events */
document.rootElement.addEventListener
  ("click", function() { set_city_changing(true); }, false);

var event_catcher = document.createElement("rect");
event_catcher.setAttribute("id", "catcher");
event_catcher.setAttribute("x", viewbox.x);
event_catcher.setAttribute("y", viewbox.y);
event_catcher.setAttribute("width", viewbox.width);
event_catcher.setAttribute("height", viewbox.height);
// Changing from one id to another does not work in ASV 3/6
// This also doesn't work; setting to null does not allow css to override
//event_catcher.style.setProperty("fill", "rgb(255, 66, 66)", null);
event_catcher.addEventListener("click", function() { set_city_changing(true); }, false);
document.rootElement.insertBefore(event_catcher, document.rootElement.firstChild);

/* The only XML loader for Adobe's SVG viewer is non-blocking
 * and this is a problem because to create the displays the
 * volunteer names need to be sorted and this can only be done
 * after all of them are loaded. So workarounds have to be
 * used to track when the last thread returns...
 */
var vol_xml_thread_count = 0;

// The loading of volunteers needs to wait on this if we want to
//  verify that all the volunteers have cities
//load_file(get_link("volunteers"), load_volunteers);
var vol_baseurl;
// Hanging fixed
//if(confirm("Load Volunteer Data?\nIf you have not logged in this will hang the browser.")) {
  var vol_href = get_link("volunteers");
  vol_baseurl = vol_href.replace(/^(.*\/)[^\/]*$/, "$1");
  if(vol_href == vol_baseurl) {
    vol_baseurl = "";
  }
  vol_xml_thread_count++; 

  getURL(get_link("volunteers"),
         function (status) {
    if(status.success) { load_volunteers(parseXML(status.content)); }
    else { add_display(); alert("Could not load volunteer data"); }});
//}

function Volunteer() {}

Volunteer.prototype.generateId = function() {
  if(typeof(this.id) == 'undefined') {
    this.id = this.getName().toLowerCase();
    this.id = this.id.replace(/ /, "_");
  }
  return this.id;
}

Volunteer.prototype.getName = function() {
  return this.first_name + " " + this.last_name;
}

var volunteers = new Array();
volunteers.sectors = new Array();
volunteers.sector_names = new Array();

function load_volunteers(data) {
  /* Start by processing any includes */
  var inc_elms = data.getElementsByTagNameNS("http://www.w3.org/2001/XInclude",
                                             "include");
  for(var incIndex = 0; incIndex < inc_elms.length; incIndex++) {
    if(inc_elms.item(incIndex).hasAttribute("href")) {
      var href = inc_elms.item(incIndex).getAttribute("href");
      if(!href.match(/^(http|file|ftp):\/\//)) {
        href = vol_baseurl + href;
      }
      if(typeof(href) != 'undefined') {
        vol_xml_thread_count++; 
        getURL(href,
               function (status) {
          if(status.success) { load_volunteers(parseXML(status.content)); }
          else { add_display(); /* alert("Could not load data"); */ }});
      }
    }
  }

  var vol_elms = data.getElementsByTagName("volunteer");
  for(var volIndex = 0; volIndex < vol_elms.length; volIndex++) {
    if(!vol_elms.item(volIndex).hasAttribute("status")) {
      var volunteer = new Volunteer();
      
      volunteer.city =
        vol_elms.item(volIndex).getElementsByTagName("site").item(0)
        .firstChild.data;

      var name = vol_elms.item(volIndex).getElementsByTagNameNS
        ("urn:oasis:names:tc:ciq:xsdschema:xNL:2.0", "PersonName").item(0);
      volunteer.first_name = name
        .getElementsByTagNameNS("urn:oasis:names:tc:ciq:xsdschema:xNL:2.0", "FirstName")
        .item(0).firstChild.data;
      volunteer.last_name = name
        .getElementsByTagNameNS("urn:oasis:names:tc:ciq:xsdschema:xNL:2.0", "LastName")
        .item(0).firstChild.data;

      volunteer.sector_abbr = vol_elms.item(volIndex).getAttribute("sector");

      switch(volunteer.sector_abbr) {
      case "ict":
        volunteer.sector = "Information Technology";
        break;
      case "sed":
        volunteer.sector = "Small Enterprise Development";
        break;
      case "agfo":
        volunteer.sector = "Agroforestry";
        break;
      case "ed":
        volunteer.sector = "English Education";
        break;
      case "ee":
        volunteer.sector = "Environmental Education";
        break;
      case "ch":
        volunteer.sector = "Community Health";
        break;
      default:
        volunteer.sector = "Unknown: " + volunteer.sector_abbr;
      }

      birthdays = vol_elms.item(volIndex).getElementsByTagNameNS
        ("urn:oasis:names:tc:ciq:xsdschema:xCIL:2.0", "BirthDate");
      if(birthdays.length > 0) {
        if((date_elms = new RegExp("^([0-9]*)-([0-9]*)-([0-9]*)")
            .exec(birthdays.item(0).childNodes.item(0).childNodes.item(0).data)) != null) {
          volunteer.birthday = new Date(date_elms[1], date_elms[2] - 1, date_elms[3]);
          today = new Date();
          age = today.getYear() - volunteer.birthday.getYear();
          if(today.getMonth() < volunteer.birthday.getMonth()
             || (today.getMonth() == volunteer.birthday.getMonth()
                 && today.getDay() < volunteer.birthday.getDay())) {
            age--;
          }
          date_string = volunteer.birthday.toLocaleDateString().replace(/^[^ ]* /, "");
          volunteer.birthday = date_string + " (" + age + ")";
        } else {
          alert("Invalid Date: " + birthdays.item(0).getAttribute("date"));
        }
      }

      degrees = vol_elms.item(volIndex).getElementsByTagName("degree");
      if(degrees.length > 0) {
        volunteer.education = new Array();
        for(var index = 0; index < degrees.length; index++) {
          volunteer.education.push(degrees.item(index).getAttribute("rank") +
                                   " in " + degrees.item(index).getAttribute("subject"));
        }
      }

      homes = vol_elms.item(volIndex).getElementsByTagName("home");
      if(homes.length > 0 && homes.item(0).firstChild != null) {
        volunteer.home = homes.item(0).firstChild.data;
      }

      emails = vol_elms.item(volIndex).getElementsByTagNameNS
        ("urn:oasis:names:tc:ciq:xsdschema:xCIL:2.0", "EmailAddress");
      if(emails.length > 0) {
        volunteer.email = new Array();
        for(var index = 0; index < emails.length; index++) {
          if(emails.item(index).firstChild != null) {
            volunteer.email.push(emails.item(index).firstChild.data);
          }
        }
      }

      photos = vol_elms.item(volIndex).getElementsByTagNameNS
        ("http://pcvs.org/2004/05/staffML", "photo");
      if(photos.length > 0) {
        volunteer.photo = photos.item(0).firstChild.data;
      }

      urls = vol_elms.item(volIndex).getElementsByTagName("url");
      if(urls.length > 0) {
        volunteer.urls = new Array();
        for(var index = 0; index < urls.length; index++) {
          volunteer.urls[urls.item(index).getAttribute("type")] =
            urls.item(index).getAttribute("href");
        }
      }
      
      volunteer.display = vol_display(volunteer);
      
      var id = volunteer.generateId();
      if(typeof(volunteers[id]) != 'undefined') {
        alert("Volunteer " + id + " already defined");
      }
      volunteers[id] = volunteer;
      if(typeof(volunteers.sectors[volunteer.sector_abbr]) == 'undefined') {
        volunteers.sectors[volunteer.sector_abbr] = new Array();
        volunteers.sector_names.push(volunteer.sector_abbr);
      }
      volunteers.sectors[volunteer.sector_abbr].push(volunteer);

      if(typeof(cities[volunteer.city]) == 'undefined') {
        alert("Location of " + volunteer.city + " for " +
              volunteer.getName() + " unknown");
      } else {
        cities[volunteer.city].push(volunteer);
      }
    }
  }
  add_display();
}

function add_display() {
  /* If this is the last thread returning then put the names in order
   * and create the display. I'm pretty sure this could be open to a
   * race condition, but I don't know how to avoid it easily...
   */
  //lon_text.data += ":" + vol_xml_thread_count;
  if(--vol_xml_thread_count == 0) {
    for(var city in cities) {
      cities[city].sort(function compare(a, b) {
        if (a.last_name < b.last_name)
          return -1;
        else
          return 1;
      });
      cities[city].display = city_display(cities[city]);
    }
    //lon_text.data += ":D";

    if(contextMenu) {
      volunteers_menu = contextMenu.createElement("menu");
      volunteers_menu.appendChild(contextMenu.createElement("header"));
      volunteers_menu.lastChild.appendChild(contextMenu.createTextNode("Volunteers"));
      volunteers_menu.appendChild(contextMenu.createElement("header"));
      contextMenu.lastChild.appendChild(volunteers_menu);

      volunteers.sector_names.sort();
      for(var nameIndex = 0; nameIndex < volunteers.sector_names.length; nameIndex++) {
        sector_menu = contextMenu.createElement("menu");
        sector_menu.appendChild(contextMenu.createElement("header"));
        sector_menu.lastChild.appendChild
          (contextMenu.createTextNode(volunteers.sector_names[nameIndex]));
        volunteers_menu.appendChild(sector_menu);
           
        sector = volunteers.sectors[volunteers.sector_names[nameIndex]];
        sector.sort(function compare(a, b) {
          if(a.last_name < b.last_name)
            return -1;
          else
            return 1;
        });
        
        for(volIndex = 0; volIndex < sector.length; volIndex++) {
          sector_menu.appendChild(contextMenu.createElement("item"));
          sector_menu.lastChild.setAttribute
            ("onactivate", "select_volunteer(\"" + sector[volIndex].generateId() + "\")");
          sector_menu.lastChild.appendChild
            (contextMenu.createTextNode(sector[volIndex].getName()));
        }
      }
    }
  }
}

function city_display(city) {
  city_info = document.createElement("g");
  city_info.setAttribute
    ("transform", "translate(" + display.margin.x + "," + display.margin.y + ")");

  city_border = document.createElement("rect");
  city_border.setAttribute("class", "city-display display-box");
  city_border.setAttribute("width", display.width + 2 * display.padding.x);
  city_border.setAttribute("height", display.line_height + city.length + "em");
  city_border.setAttribute("rx", display.roundness);
  city_info.appendChild(city_border);
  
  city_text = document.createElement("text");
  city_text.setAttribute("class", "city-names names-box");
  city_text.setAttribute("x", parseFloat(city_border.getAttribute("width")) / 2);
  city_info.appendChild(city_text);

  city_text.appendChild(document.createElement("tspan"));
  city_text.firstChild.setAttribute("dy", "1em");
  city_text.firstChild.appendChild(document.createTextNode(city.name));

  for(var volIndex = 0; volIndex < city.length; volIndex++) {
    name_box = document.createElement("tspan");
    name_box.setAttribute("dy", volIndex == 0 ? "1.2em" : "1em");
    name_box.setAttribute("x", city_text.getAttribute("x"));
    name_box.appendChild(document.createTextNode(city[volIndex].getName()));

    name_box.addEventListener("mouseover", name_over, false);
    name_box.addEventListener("mouseout", name_out, false);
    name_box.addEventListener("click", name_click, false);

    city_text.appendChild(name_box);

    /* I do not understand it at all, but the objects associated with
     *  the name_box are being removed about 20 seconds after the
     *  page loads. Things will run fine and then the object will start
     *  coming up undefined. So, I am going to have to index them
     *  in a separate structure.
     */
    //name_box.volunteer = city[volIndex];
    name_box.setAttribute("id", city[volIndex].generateId());
    city[volIndex].name_box = name_box;
  }
  return city_info;
}

/* Create a display to show info about a volunteer
 */
function vol_display(vol) {
  vol_info = document.createElement("g");
  var x_pos = 2 * display.margin.x + 2 * display.padding.x + display.width;
  vol_info.setAttribute
    ("transform", 
     "translate(" + x_pos + "," + display.margin.y + ")");

  vol_box = document.createElement("rect");
  vol_box.setAttribute("class", "display-box vol-display");
  vol_box.setAttribute("width", viewbox.width - (display.margin.x + x_pos));
  vol_box.setAttribute("height", viewbox.height * .2); // This determines photo height
  
  vol_box.setAttribute("rx", display.roundness);
  vol_info.appendChild(vol_box);
  
  if(typeof(vol.photo) == 'undefined') {
    photo_box = undefined;
  } else {
    photo_box = document.createElement("rect");
    photo_box.setAttribute("id", "photo-display");
    photo_box.setAttribute("class", "display-box");
    photo_box.setAttribute("x", display.padding.x);
    photo_box.setAttribute("y", display.padding.y);
    photo_box.setAttribute("width", parseFloat(vol_box.getAttribute("height")) * .7);
    photo_box.setAttribute
      ("height", parseFloat(vol_box.getAttribute("height")) - 2 * display.padding.y);

    photo_box.setAttribute("rx", display.roundness);
    vol_info.appendChild(photo_box);

    vol_info.appendChild(document.createElement("image"));
    lastChild = vol_info.childNodes.item(vol_info.childNodes.length - 1);
    lastChild.setAttributeNS
      ("http://www.w3.org/1999/xlink", "href", vol.photo);
    lastChild.setAttribute
      ("x", parseFloat(photo_box.getAttribute("x")) + display.padding.x);
    lastChild.setAttribute
      ("y", parseFloat(photo_box.getAttribute("y")) + display.padding.y);
    lastChild.setAttribute
      ("width", parseFloat(photo_box.getAttribute("width")) - 2 * display.padding.x);
    lastChild.setAttribute
      ("height", parseFloat(photo_box.getAttribute("height")) - 2 * display.padding.y);
    lastChild.setAttribute("preserveAspectRatio", "xMidYMid");
  }

  vol_text_box = document.createElement("text");
  vol_text_box.setAttribute("class", "info-box");
  vol_text_box.setAttribute
    ("x", ((typeof(photo_box) != 'undefined' ? parseFloat(photo_box.getAttribute("x")) : 0) +
           (typeof(photo_box) != 'undefined' ? parseFloat(photo_box.getAttribute("width")) : 0) +
           viewbox.width * .01));
  vol_text_box.setAttribute
    ("y", typeof(photo_box) != 'undefined' ? photo_box.getAttribute("y") : display.padding.y);
  vol_info.appendChild(vol_text_box);

  var line_count = 0;

  var qualities = new Array("Sector", "Birthday", "Education", "Degree", "Email");
  var bold_lines = 0;
  var text_lines = 0;

  for(var qualityIndex = 0; qualityIndex < qualities.length; qualityIndex++) {
    var qualityName = qualities[qualityIndex].toLowerCase();
    if(typeof(vol[qualityName]) != 'undefined') {
      quality = vol[qualityName];
      vol_text_box.appendChild(document.createElement("tspan"));
      /* This seems wrong, but:
         alert(vol_text_box.childNodes.item(vol_text_box.childNodes.length - 1) ==
               vol_text_box.childNodes.lasChild);
       * is false...
       */
      lastChild = vol_text_box.childNodes.item(vol_text_box.childNodes.length - 1);
      lastChild.setAttribute("class", "vol-header");
      lastChild.setAttribute("x", vol_text_box.getAttribute("x"));
      lastChild.setAttribute("dy", "1em");
      lastChild.appendChild(document.createTextNode(qualities[qualityIndex] + ":"));
      bold_lines++;

      var data = typeof(quality) == 'object' ? quality : new Array(quality);
      for(var dataIndex = 0;  dataIndex < data.length; dataIndex++) {
        vol_text_box.appendChild(document.createElement("tspan"));
        lastChild = vol_text_box.childNodes.item(vol_text_box.childNodes.length - 1);
        lastChild.setAttribute("class", "vol-datum");
        lastChild.setAttribute("x", (parseFloat(vol_text_box.getAttribute("x")) +
                                     viewbox.width * .03));
        lastChild.setAttribute("dy", "1em");

        if(qualityName == 'email') {
          link = document.createElement("a");
          link.setAttributeNS("http://www.w3.org/1999/xlink",
                              "href", "mailto:" + data[dataIndex]);
          lastChild.appendChild(link);
          lastChild = link;
        }
        lastChild.appendChild(document.createTextNode(data[dataIndex]));
        text_lines++;
      }
    }
  }

  if(typeof(vol.urls) != 'undefined') {
    vol_text_box.appendChild(document.createElement("tspan"));
    lastChild = vol_text_box.childNodes.item(vol_text_box.childNodes.length - 1);
    lastChild.setAttribute("class", "vol-header");
    lastChild.setAttribute("x", vol_text_box.getAttribute("x"));
    lastChild.setAttribute("dy", "1em");
    lastChild.appendChild(document.createTextNode("Links:"));
    bold_lines++;

    vol_text_box.appendChild(document.createElement("tspan"));
    lastChild = vol_text_box.childNodes.item(vol_text_box.childNodes.length - 1);
    lastChild.setAttribute("class", "vol-datum");
    lastChild.setAttribute("x", (parseFloat(vol_text_box.getAttribute("x")) +
                                 viewbox.width * .03));
    lastChild.setAttribute("dy", "1em");
    var count = 0;
    for(type in vol.urls) {
      link = document.createElement("a");
      link.setAttributeNS("http://www.w3.org/1999/xlink",
                          "href", vol.urls[type]);
      if(count++ > 0) {
        lastChild.appendChild(document.createTextNode(", "));
      }
      lastChild.appendChild(link);
      link.appendChild(document.createTextNode(type));
    }
    text_lines++;
  }

  /* The border box needs to change in size depending on the contents. This
   * is a little tricky because there are bold elements which are taller
   * and for ones with a photo that sets a minimum height.
   */
  vol_box_height = vol_box.getAttribute("height");
  vol_box_text_height = (bold_lines * viewbox.height * .031 +
                         text_lines * viewbox.height * .026 +
                         2 * display.padding.y);
  if(typeof(photo_box) != 'undefined') {
    vol_box_height = Math.max(vol_box_height, vol_box_text_height);
  } else {
    vol_box_height = vol_box_text_height;
  }
  vol_box.setAttribute("height", vol_box_height);
    
  return vol_info;
}

// The active volunteer is the currently selected volunteer
var active_vol = undefined;
var vol_changing = true;

function name_over(event) {
  if(vol_changing) {
    show_volunteer(event.target.id);
  }
}

function name_out(event) {
  if(vol_changing) {
    hide_volunteer(event.target.id);
  }
}

function name_click(event) {
  select_volunteer(event.target.id);
}

function select_volunteer(name) {
  /* The first time a volunteer is selected the display locks
   *  to them; the second it is unlocked
   */
  // If someone else was selected before, free them
  if(!vol_changing && active_vol == name) {
    show_volunteer(name);
    vol_changing = true;
  } else {
    show_volunteer(name);
    volunteers[name].name_box.style.setProperty("fill", "green", null);
    vol_changing = false;
  }
}

function show_volunteer(name) {
  var volunteer = volunteers[name];
  volunteer.name_box.style.setProperty("fill", "red", null);
  if(active_vol != name) {
    if(typeof(active_vol) != 'undefined') {
      hide_volunteer(active_vol);
    }
    show_city(volunteer.city);
    active_vol = name;
    document.rootElement.appendChild(volunteer.display);
  }
}

function hide_volunteer(name) {
  var volunteer = volunteers[name];
  volunteer.name_box.style.setProperty("fill", "black", null);
  if(active_vol != name) {
    alert("Unexpected: Active display not the same as volunteer display");
    hide_volunteer(active_vol);
  }
  document.rootElement.removeChild(volunteer.display);
  active_vol = undefined;
  vol_changing = true;
}

var city_changing = true;
var active_city = undefined;

function city_over(event) {
  if(city_changing) {
    show_city(event.target.id);
  }
}

function city_out(event) {
  if(city_changing) {
    //hide_city(event.target.id);
    //active_city = undefined;
  }
}

function city_click(event) {
  set_city_changing(false);
  event.stopPropagation();
}

/* Target used to pinpoint cities
 */
var target_node = document.createElement("use");
target_node.setAttributeNS("http://www.w3.org/1999/xlink",
                           "href", "#target");
target_node.setAttribute("display", "none");
document.rootElement.appendChild(target_node);

function show_city(name) {
  if(name != active_city && typeof(cities[name].display) != 'undefined') {
    if(typeof(active_city) != 'undefined') {
      hide_city(active_city);
    }
    active_city = name;
    city = cities[name];
    document.rootElement.appendChild(city.display);
    
    if(typeof(target_node) != 'undefined') {
      if(target_node.getAttribute("display") == "none") {
        target_node.setAttribute("display", "inline");
      }
      target_node.setAttribute("x", city.element.getAttribute("cx"));
      target_node.setAttribute("y", city.element.getAttribute("cy"));
    }
  }
}

function hide_city(name) {
  if(typeof(name) != 'undefined' && typeof(cities[name].display) != 'undefined') {
    document.rootElement.removeChild(cities[name].display);
    if(typeof(active_city) != 'undefined' && name != active_city) {
      alert("Hid " + name + " when " + active_city + " was active");
      hide_city(active_name);
    }
    active_city = undefined;
    if(typeof(active_vol) != 'undefined') {
      hide_volunteer(active_vol);
    }
  }
}

contextMenu.firstChild.appendChild(contextMenu.createElement("separator"));
var city_change_item = contextMenu.createElement("item");
city_change_item.appendChild(contextMenu.createTextNode("Mouse City Selection"));
city_change_item.setAttribute("onactivate", "toggle_city_change()");
contextMenu.firstChild.appendChild(city_change_item);
set_city_changing(true);

function toggle_city_change() {
  set_city_changing(!city_changing);
}

function set_city_changing(changing) {
  this.city_changing = changing;
  city_change_item.setAttribute("checked", changing ? "yes" : "no");
}
 
