function BoxControl(display) {
  this.saturation = .9;
  this.brightness = 1;
  this.changingBoxes = new Array();
  //var changingBoxes = new Array();
  this.doneBoxes = new Array();
  this.freeBoxes = new Array();
  this.display = display;
  
  this.makebox = function(x, y, size) {
    var hue = Math.random() * 360;
    if(!size) size = maxsize * .5 + Math.random() * maxsize * .5;
    var box;
    if(this.freeBoxes.length > 0) {
      box = this.freeBoxes.pop();
    } else {
      box = document.createElement("div");
      this.display.appendChild(box);
    }
    var props = {width:size + "px",height:size + "px",position:"absolute",
                 left:x + "px",top:y + "px",
                 "background-color":getCSSColor(hue, this.saturation, this.brightness),
                 border:"2px solid " + getCSSColor(hue, this.saturation + .1, this.brightness)}
    for(prop in props) {
      setStyleProperty(box, prop, props[prop]);
    }
    setOpacity(box, 0);
    box.finalOpacity = .5 + (Math.random() * .5 - .25);
    box.steps = 6 + Math.random() * 10;
    box.appearing = true;
    this.changingBoxes.push(box);
  }

  this.checkboxes = function() {
    with(arguments.callee.controller) {
      for(var i = 0; i < changingBoxes.length; i++) {
        var box = changingBoxes[i];
        setOpacity(box, box.currentOpacity +
                   (box.appearing ? 1 : -1) * box.finalOpacity / box.steps);
      }
      for(i = changingBoxes.length - 1; i >= 0; i--) {
        if(changingBoxes[i].currentOpacity <= 0) {
          freeBoxes.push(changingBoxes.splice(i, 1)[0]);
        } else if(changingBoxes[i].currentOpacity > changingBoxes[i].finalOpacity) {
          doneBoxes.push(changingBoxes.splice(i, 1)[0]);
        }
      }
    }
  }
  this.checkboxes.controller = this;

  this.removeBox = function(index) {
    if(!index) index = Math.random() * this.doneBoxes.length;
    if(index < this.doneBoxes.length) {
      var box = this.doneBoxes.splice(index, 1)[0];
      box.appearing = false;
      this.changingBoxes.push(box);
    }
  }

  var refreshTimeout = 100;
  setInterval(this.checkboxes, refreshTimeout);
}

function setOpacity(element, opacity) {
  var props = {opacity:opacity,"-moz-opacity":opacity,
               filter:"alpha(opacity=" + opacity + ")"};
  for(prop in props) {
    setStyleProperty(element, prop, props[prop]);
  }
  element.currentOpacity = opacity;
}
