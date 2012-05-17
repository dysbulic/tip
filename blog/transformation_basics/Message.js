function Message(type, content) {
    this.__defineGetter__('type', function() { return type });
    this.__defineSetter__('type', function(val) { type = val });
    this.__defineGetter__('content', function() { return content });
    this.__defineSetter__('content', function(val) { content = val });
}
