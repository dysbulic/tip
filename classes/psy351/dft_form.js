var weights = { a : "abortion", d : "death penalty" };
var options = { b : "Bush", g : "Gore", n : "Nader" };

// Amount to scale so that graph fits
var scale = .001;

var weight_names = get_keys(weights);
var opt_names = get_keys(options);

$(document).ready(function () {
  $("input").bind("keydown", only_numbers);
  $(".utility, .valence").attr("disabled", "true");
  $(".weight").bind("", only_numbers);
  add_results_table();
  load_vars();
  set_random_state();
  recompute();
  load_vars();
  clear_cache();
});

function ofc_ready() {
  if(typeof(get_params()['autorun']) != "undefined")
    iterate();
}

// Text boxes only accept numbers as input
function only_numbers(event) {
  return (event.which == 8 // backspace
          || event.which == 13 // return
          || event.which == 190 // period
          || event.which == 46 // delete
          || event.which == 9 // tab
          || event.which == 109 // minus sign
          || (event.which >= 37 && event.which <= 40) // arrow keys
          || event.which == 0
          || (event.which >= 96 && event.which <= 105) // keypad digits
          || (event.which >= 48 && event.which <= 57)); // digits
}

Array.prototype.max = function(array){
  return Math.max.apply(Math, array);
}; 

function get_params() {
  var url;
  if(document.getURL) { url = document.getURL(); }
  else if(window.location) { url = window.location.href; }
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
      params[key] = value;
    }
  }
  return params;
}

function load_vars() {
  var params = get_params();
  for(param in params) {
    if(params[param] != null && params[param] != "") {
      setvar(param, params[param]);
    }
  }
}

function get_keys(obj) {
  var keys = new Array();
  var index = 0;
  for(var key in obj) { keys[index++] = key; }
  return keys;
} 

function iterate() {
  var loop_max = getvar("count");
  var dataset = new Array(opt_names.length);
  for(var i = 0; i < opt_names.length; i++) {
    dataset[i] = new Array(10000);
  }
  for(var loop_index = 0; loop_index < loop_max && !recompute(dataset, loop_index); loop_index++) {
  }
  set_preferences(get_preferences(), true);
  add_results(dataset);
}

function recompute(dataset, loop_index) {
  // In javascript, slice is necessary to make a copy of the array
  var weights = get_weights().slice();
  //set_weights(weights, typeof(dataset) == "undefined");

  // At each iteration, one option is chosen and processed at that
  // level. Over many iterations this produces a probabalistic
  // distribution.
  //
  // Hardcode two for now
  if(typeof(dataset) != "undefined") {
    weights[0] = (Math.random() < weights[0]) ? 0 : 1;
    weights[1] = 1 - weights[0];
  }

  var events = get_events();

  var utilities = get_utilities(weights, events);
  set_utilities(utilities, typeof(dataset) == "undefined");

  var valences = get_valences(utilities);
  set_valences(valences, typeof(dataset) == "undefined");
  
  var inter = get_interferences();
  var prefs = get_preferences();

  var next_prefs = new Array(prefs.length);
  for(var i = 0; i < prefs.length; i++) {
    next_prefs[i] = 0;
    for(var j = 0; j < prefs.length; j++) {
      next_prefs[i] += inter[j][i] * prefs[j];
    }
    next_prefs[i] += valences[i];
    next_prefs[i] = prefs[i] + next_prefs[i] * scale;
  }
  
  set_preferences(next_prefs, typeof(dataset) == "undefined");
  if(typeof(dataset) != "undefined") {
    for(var i = 0; i < opt_names.length; i++) {
      dataset[i][loop_index] = next_prefs[i];
    }
  }

  var threshold = get_threshold();
  var max_pref = Math.max.apply(Math, next_prefs);
  var min_pref = Math.min.apply(Math, next_prefs);
  var done = max_pref > threshold || min_pref < 0;
  if(done) {
    //alert(next_prefs.indexOf(max_pref) + " : " + opt_names[next_prefs.indexOf(max_pref)] + " : " + options[opt_names[next_prefs.indexOf(max_pref)]]);
  }
  // ToDo: set svg var

  return done;
}

function set_random_state() {
  var events = get_events();
  for(var i = 0; i < weight_names.length; i++) {
    for(var j = 0; j < opt_names.length; j++) {
      if(events[i][j] == undefined) {
        events[i][j] = Math.round(Math.random() * 10) - 4;
      }
    }
  }
  set_events(events);

  var interferences = get_interferences();
  for(var i = 0; i < opt_names.length; i++) {
    for(var j = i; j < opt_names.length; j++) {
      if(interferences[i][j] == undefined) {
        interferences[i][j] = Math.round(Math.random() * 10) - 5;
        interferences[j][i] = interferences[i][j];
      }
    }
  }
  set_interferences(interferences);

  if(get_threshold() == undefined) {
    set_threshold(1);
  }
}

function getvar(name) {
  var val = $("[name=" + name + "]").val();
  //alert("Getting: [name=" + name + "] = " + val);
  if(typeof(val) != "undefined" && val != "") {
    val = parseFloat(val);
  }
  return val;
}

function setvar(name, value) {
  //alert("Setting: [name=" + name + "] = " + value);
  $("[name=" + name + "]").val(value);
}

function getvars(prefix, names) {
  var values = new Array(names.length);
  for(var i = 0; i < names.length; i++) {
    values[i] = getvar(prefix + names[i]);
  }
  return values;
}

function setvars(prefix, names, values) {
  for(var i = 0; i < names.length; i++) {
    setvar(prefix + names[i], values[i]);
  }
}

var weights_cache;
function get_weights() {
  if(typeof(weights_cache) == "undefined") {
    weights_cache = getvars("w_", weight_names);
  }
  return weights_cache;
}

function set_weights(values, updateDisplay) {
  weights_cache = values;
  if(updateDisplay) {
    setvars("w_", weight_names, values);
  }
}

var events_cache;
function get_events() {
  if(typeof(events_cache) == "undefined") {
    events_cache = new Array(weight_names.length);
    for(var i = 0; i < weight_names.length; i++) {
      var ev = getvars(weight_names[i] + "_", opt_names);
      events_cache[i] = ev;
    }
  }
  return events_cache;
}

function set_events(values, updateDisplay) {
  events_cache = values;
  if(updateDisplay) {
    for(var i = 0; i < weight_names.length; i++) {
      setvars(weight_names[i] + "_", opt_names, events_cache[i]);
    }
  }
}

function get_utilities(weights, events) {
  var utilities = new Array(opt_names.length);
  for(var i = 0; i < opt_names.length; i++) {
    utilities[i] = 0;
    for(var j = 0; j < weight_names.length; j++) {
      utilities[i] += weights[j] * events[j][i];
    }
  }
  return utilities;
}

function set_utilities(values, updateDisplay) {
  if(updateDisplay) {
    setvars("u_", opt_names, values);
  }
}

function get_valences(utilities) {
  var total_utilitity = 0;
  for(var i = 0; i < utilities.length; i++) {
    total_utilitity += utilities[i];
  }
  
  var valences = new Array(utilities.length);
  for(var i = 0; i < utilities.length; i++) {
    valences[i] = utilities[i] + ((total_utilitity - utilities[i]) / utilities.length - 1);
  }
  return valences;
}

function set_valences(values, updateDisplay) {
  if(updateDisplay) {
    setvars("v_", opt_names, values);
  }
}

var threshold_cache;
function get_threshold() {
  if(typeof(threshold_cache) == "undefined") {
    threshold = getvar("t");
  }
  return threshold_cache;
}

function set_threshold(val, updateDisplay) {
  threshold_cache = val;
  if(updateDisplay) {
    setvar("t", val);
  }
}

var preferences_cache;
function get_preferences() {
  if(typeof(preferences_cache) == "undefined") {
    preferences_cache = getvars("p_", opt_names);
  }
  return preferences_cache;
}

function set_preferences(values, updateDisplay) {
  preferences_cache = values;
  if(updateDisplay) {
    setvars("p_", opt_names, values);
  }
}

var interferences_cache;
function get_interferences() {
  if(typeof(interferences_cache) == "undefined") {
    interferences_cache = new Array(opt_names.length);

    // hardcode at two, n-dimensional arrays are ugly in javascript
    for(var i = 0; i < opt_names.length; i++) {
      interferences_cache[i] = new Array(opt_names.length);
      for(var j = 0; j < opt_names.length; j++) {
        interferences_cache[i][j] = getvar("i_" + opt_names[i] + opt_names[j]);
      }
    }
    for(var i = 0; i < opt_names.length; i++) {
      for(var j = 0; j < opt_names.length; j++) {
        if(interferences_cache[i][j] == undefined) {
          interferences_cache[i][j] = interferences_cache[j][i];
        }
      }
    }
  }
  return interferences_cache;
}

function set_interferences(interferences, updateDisplay) {
  interferences_cache = interferences;
  if(updateDisplay) {
    // hardcode at two, n-dimensional arrays are ugly in javascript
    for(var i = 0; i < opt_names.length; i++) {
      for(var j = 0; j < opt_names.length; j++) {
        setvar("i_" + opt_names[i] + opt_names[j], interferences[i][j]);
      }
    }
  }
}

function clear_cache() {
  weights_cache = undefined;
  events_cache = undefined;
  threshold_cache = undefined;
  preferences_cache = undefined;
  interferences_cache = undefined;
}

function add_results_table() {
  var row = $("<tr/>");
  for(var i = 0; i < opt_names.length; i++) {
    row.append($("<th/>").append(options[opt_names[i]]));
  }
  $("#tableholder").append($("<table id='results'/>").append(row));
}

var chart_data = {
  "title": {
    "text":  "Preferences Over Time",
    "style": "{font-size: 20px; color:#0000ff; font-family: Verdana; text-align: center;}"
  },
  "y_legend": {
    "text": "Preference Level",
    "style": "{color: #736AFF; font-size: 12px;}"
  },
  "x_axis": {
    "stroke":      1,
    "tick_height": 10,
    "colour":      "#d000d0",
    "grid_colour": "#00ff00",
  },
  "y_axis": {
    "stroke":      4,
    "tick_length": 3,
    "colour":      "#d000d0",
    "grid_colour": "#00ff00",
    "offset":      0,
    "max":         1
  }
};

function add_results(dataset) {
  var table = $("#results");
  for(var index = 0; typeof(dataset[0][index]) != "undefined"; index++) {
    var row = $("<tr/>");
    for(var i = 0; i < opt_names.length; i++) {
      row.append($("<th/>").append(dataset[i][index]));
      chart_data.elements[i].values[chart_data.elements[i].values.length] = dataset[i][index];
    }
    table.append(row);
  }
  $("#chart").each(function() { this.load(JSON.stringify(chart_data)) });
}

function findSWF(movieName) {
  if(navigator.appName.indexOf("Microsoft")!= -1) {
    return window[movieName];
  } else {
    return document[movieName];
  }
}

// Called by chart to load data
function open_flash_chart_data() {
  chart_data.elements = new Array(opt_names.length);
  colors = ["#9933CC", "#3399CC", "#33CC99"];
  for(var i = 0; i < opt_names.length; i++) {
    chart_data.elements[i] = { "type":      "line",
                               "alpha":     0.5,
                               "colour":    colors[i],
                               "text":      options[opt_names[i]],
                               "font-size": 10,
                               "values" :   new Array()
    };
  }
  return JSON.stringify(chart_data);
}
