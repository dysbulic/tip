/***************************************************
*                                                  *
*   TextWrap || <flow:text>                        *
*                                                  *
*   A class for naive text-wrapping with SVG 1.0   *
*                                                  *
***************************************************/

/**
 *
 * SVG
 *
 **/
 
function SVG () {
}

SVG.ns = 'http://www.w3.org/2000/svg';

/**
 *
 * TextWrap
 *
 **/

function TextWrap (id, node) {
	this._id = id;
	this._node = node;
	this._string = null;
	this._x = null;
	this._y = null;
	this._width = null;
	this._font = null;
	this._size = null;
	this._align = null;
	this._quality = null;
	this._interval = null;
	this._svg = null;
	this._lines = null;
	this._initialized = false;
	this._construct();
}

TextWrap.ns = 'http://xmlns.graougraou.com/svg/text/';

TextWrap._instances = new Array();

TextWrap._init = function () {
	var elements = document.documentElement.getElementsByTagNameNS(this.ns, 'wrap');
	for (var i=0; i<elements.length; i++) {
		this._instances.push( new TextWrap(i, elements.item(i)) );
	}
}

/**
 *
 * Processing methods
 *
 **/

TextWrap.prototype._construct = function () {
	this._build();
	this._svg.setAttribute('style', this._node.getAttribute('style'));
	var style = this._svg.style;
	this._node.normalize();
	
	this.setString( this._node.firstChild.data );
	this.setX( parseInt(this._node.getAttribute('x')) );
	this.setY( parseInt(this._node.getAttribute('y')) );
	this.setWidth( parseInt(this._node.getAttribute('width')) );
	this.setTextAlign( style.getPropertyValue('text-align') );
	this.setFontFamily( style.getPropertyValue('font-family') );
	this.setFontSize( style.getPropertyValue('font-size') );
	this.setTextRendering( style.getPropertyValue('text-rendering') );
	this.setLineInterval( style.getPropertyValue('line-interval') );

	this._splitString();
	this._layout();
	this._initialized = true;
}

TextWrap.prototype._build = function () {
	var element = document.createElementNS(SVG.ns, 'text');
	var node = this._node;
	var nextElement = null;
	while (node.nextSibling) {
		if (node.nextSibling.nodeType == 1) {
			nextElement = node.nextSibling;
			break;
		} else {
			node = node.nextSibling;
		}
	}
	if (nextElement) {
		var test = this._node.parentNode.insertBefore(element, nextElement);
	} else {
		this._node.parentNode.appendChild(element);
	}
	element.appendChild(document.createTextNode(''));
	this._svg = element;
}

TextWrap.prototype._splitString = function () {
	this._hide();
	this._clear();
	var words = this._string.split(' ');
	var lines = new Array();
	var line = new Array();
	var length = 0;
	var prevLength = 0;	
	while (words.length) {
		var word = words[0];
		this._svg.firstChild.data = line.join(' ') + ' ' + word;
		length = this._svg.getComputedTextLength();
		if (length > this._width) {
			if (!words.length) {
				line.push(words[0]);
			}
			lines.push( new Line(prevLength, line) );
			line = new Array();
		} else {
			line.push(words.shift());
		}
		prevLength = length;
		if (words.length == 0) {
			lines.push( new Line(0, line) );
		}
	}
	this._lines = lines;
}

TextWrap.prototype._layout = function () {
	this._clear();
	var lines = (new Array(0)).concat(this._lines);
	var anchor = 'start';
	if (this._align == 'center') {
		anchor = 'middle';	
	} else if (this._align == 'right') {
		anchor = 'end';
	}
	for (var i=0; i<lines.length; i++) {
		var x = 0;		
		line = lines[i]; 
		this._svg.appendChild( document.createTextNode(' ') );
		var tspan = document.createElementNS(SVG.ns, 'tspan');
		tspan.appendChild( document.createTextNode(line._words.join(' ')) ); 
		if (this._align == 'justify') {
			var space = (this._width - line._width) / (line._words.length - 1);
			space = (i != lines.length - 1) ? space : 0;
			tspan.style.setProperty('word-spacing', space + 'px', null);
		} else if (this._align == 'center') {
			anchor = 'middle';
			x = this._width / 2;
		} else if (this._align == 'right') {
			anchor = 'end';
			x = this._width;
		}
		tspan.setAttribute('x', x);
		tspan.setAttribute('dy', i ? this._interval : '1em');
		this._svg.appendChild(tspan);
	}	
	this._svg.style.setProperty('text-anchor', anchor, null);
	this._show();
}

/**
 *
 * Utility methods
 *
 **/

TextWrap.prototype._hide = function () {
	this._svg.style.setProperty('opacity', '0', null);
}

TextWrap.prototype._show = function () {
	this._svg.style.setProperty('opacity', '1', null);
}

TextWrap.prototype._clear = function () {
	while (this._svg.hasChildNodes()) {
		this._svg.removeChild(this._svg.firstChild);
	}
	this._svg.appendChild(document.createTextNode(''));
}

/**
 *
 * GET / SET
 *
 * Getters and setters for CSS properties and XML attributes,
 * will be updated when mutation events will be implemented
 *
 **/

TextWrap.prototype.getX = function () {
	return this._x;
}

TextWrap.prototype.setX = function (x) {
	if (x != this._x) {
		this._x = x;
		this._svg.setAttribute('transform', 'translate(' + this._x + ' ' + this._y + ')');
	} 
}

TextWrap.prototype.getY = function () {
	return this._y;
}

TextWrap.prototype.setY = function (y) {
	if (y != this._y) {
		this._y = y;
		this._svg.setAttribute('transform', 'translate(' + this._x + ' ' + this._y + ')');
	} 
}

TextWrap.prototype.getWidth = function () {
	return this._width;
}

TextWrap.prototype.setWidth = function (width) {
	if (width != this._width) {
		this._width = width;
		if (this._initialized) {
			this._splitString();
			this._layout();
		}
	} 
}

TextWrap.prototype.getTextAlign = function () {
	return this._align;
}

TextWrap.prototype.setTextAlign = function (align) {
	if (align != this._align) {
		this._align = align;
		if (this._initialized) {
			this._layout();
		}
	} 
}

TextWrap.prototype.getString = function () {
	return this._string;
}

TextWrap.prototype.setString = function (string) {
	if (string != this._string) {
		this._string = string;
		if (this._initialized) {
			this._splitString();
			this._layout();
		}
	} 
}

TextWrap.prototype.getFontFamily = function () {
	return this._font;
}

TextWrap.prototype.setFontFamily = function (font) {
	if (font != this._font) {
		this._font = font;
		this._svg.style.setProperty('font-family', this._font, null);
		if (this._initialized) {
			this._splitString();
			this._layout();
		}
	} 
}

TextWrap.prototype.getFontSize = function () {
	return this._size;
}

TextWrap.prototype.setFontSize = function (size) {
	if (size != this._size) {
		this._size = size;
		this._svg.style.setProperty('font-size', this._size, null);
		if (this._initialized) {
			this._splitString();
			this._layout();
		}
	} 
}

TextWrap.prototype.getTextRendering = function () {
	return this._quality;
}

TextWrap.prototype.setTextRendering = function (quality) {
	if (quality != this._quality) {
		this._quality = quality;
		this._svg.style.setProperty('text-rendering', this._quality, null);
		if (this._initialized) {
			this._splitString();
			this._layout();
		}
	} 
}

TextWrap.prototype.getLineInterval = function () {
	return this._interval;
}

TextWrap.prototype.setLineInterval = function (interval) {
	if (interval != this._interval) {
		this._interval = interval;
		if (this._initialized) {
			var element = this._svg.firstChild;
			var count = 0;
			while (element) {
				if (element.nodeName == 'tspan') {
					if (count) {
						element.setAttribute('dy', this._interval);
					}
					count++;
					if (count == this._lines.length) {
						break;
					}
				}
				element = element.nextSibling;
			}
		}
	}
}

/*****
 *
 * Line
 *
 *****/
 
function Line (width, words) {
	this._width = width;
	this._words = words;
}
