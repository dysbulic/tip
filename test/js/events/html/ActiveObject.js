var ActiveObject = function() {
  var properties = {};
  var order = [];
  var listeners = {};
  var idx;

  this.trigger = function(eventName) {
    if(typeof(listeners[eventName]) != "undefined") {
      var args = [];
      for(var i = 1; i < arguments.length; i++) {
        args.push(arguments[i]);
      }
      for(var [idx, listener] in Iterator(listeners[eventName])) {
        //var env = { __: { source: this, idx: idx } };
        if(listener.apply(this, args) === false) break;
      }
    }
  };

  this.bind = function(eventName, callback) {
    if(typeof(listeners[eventName]) == "undefined") listeners[eventName] = [];
    listeners[eventName].push(callback);
  };

  this.each = function(callback) {
    for(var [idx, prop] in Iterator(order)) {
      callback(prop, properties[prop], idx);
    }
  };

  this.add = function(propName, defaultValue) {
    if(typeof(this[propName]) != "undefined") throw "Error: Overwriting an existing property: " + propName;
    this.__defineGetter__(propName,
                          function() { return properties[propName]; });
    this.__defineSetter__(propName,
                          function(val) {
                            if(typeof(val) == "undefined") val = defaultValue;
                            if(properties[propName] != val) {
                              this.trigger('change', propName, properties[propName], val);
                              properties[propName] = val;
                            }
                          });
  };

  this.__defineGetter__("size", function() { return order.size; });
}
