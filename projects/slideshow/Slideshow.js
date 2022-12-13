/**
 * Class used to hold the info about images in a custom layout
 */
function ImageInfo(image, styleParam) {
  this.image = image;
  if(typeof(styleParam) != "undefined" && styleParam != null) {
    this.style = new Array();
    styleParam = styleParam.replace(/^\s+/, "");
    styleParam = styleParam.replace(/\s+$/, "");
    var styleElements = styleParam.split(/\s*;\s*/);
    for(var i = 0; i < styleElements.length; i++) {
      var styleValues = styleElements[i].split(/\s*:\s*/);
      this.style[styleValues[0]] = styleValues[1];
    }
  }
  this.startTime = undefined;
  this.endTime = undefined;
}
ImageInfo.prototype = new Object;
ImageInfo.prototype.toString = function() {
  return "ImageInfo: " + this.image;
}

function DocumentInfo() {
  this.element = document.createElement("div");
  this.timings = new Array();
}

function DisplayEvent(element, startTime, endTime) {
  this.element = element;
  this.startTime = startTime;
  this.endTime = endTime;
  this.active = false;
}
DisplayEvent.prototype = new Object;

function EventsArray() {
  Array.apply(this, arguments);
}
EventsArray.prototype = new Array();

/**
 * Last event starting at of before 'time'
 */
// Array Subclassing is not working in IE6
//EventsArray.prototype.indexOfLastEventAt = function(time) {
var indexOfLastEventAt = function(time) {
  var index = undefined;
  if(this.length > 0) {
    /* My binary version of this search was buggy so this is simple linear */
    index = 0;
    while(index < this.length && this[index].startTime <= time) {
      index++;
    }
    /* The loop will overshoot by 1, so if it is 0, it didn't find any */
    if(index == 0) {
      index = undefined;
    } else {
      index--;
    }
  }
  return index;
}

function Slideshow() {
  this.configured = false; // if the slideshow is ready to start
  this.loaded = false;     // if the data files have been loaded
  // this.defaultDisplayTime = 1300; /* number of milliseconds to leave an item displayed */
  this.events = new EventsArray();
  this.events = new Array();
  this.events.indexOfLastEventAt = indexOfLastEventAt;
  this.presentationTime = 0; /* running time for the presentation */
  this.startTime = 0; /* time the show was started */
  this.lastSeekTime = undefined;
  this.playing = false;
  this.activeEvents = new Array(); /* currently visible events */
  this.stopIndex = undefined; // Index in the events array of the latest active event
}
Slideshow.prototype = new Object;

// IE lacks getters and setters completely, so this can't be pretty
Slideshow.prototype.getCurrentTime = function () {
  return new Date().getTime() - this.startTime;
}

Slideshow.prototype.start = function() {
  this.playing = true;
  this.startTime = new Date().getTime() - this.lastSeekTime;
}

Slideshow.prototype.stop = function() {
  this.playing = false;
}

Slideshow.prototype.reset = function() {
  this.lastSeekTime = 0;
  this.playing = false;
  while(this.activeEvents.length > 0) {
    this.activeEvents.pop().active = false;
  }
  for(var i = 0; i < this.events.length; i++) {
    uiInterface.hideElement(this.events[i].element);
  }
}

Slideshow.prototype.seekToTime = function(time) {
  var index = this.stopIndex = this.events.indexOfLastEventAt(time);
  for(var i = this.activeEvents.length - 1; i >= 0; i--) {
    if(this.activeEvents[i].startTime > time
       || this.activeEvents[i].endTime <= time) {
      uiInterface.hideElement(this.activeEvents[i].element);
      this.activeEvents[i].active = false;
      this.activeEvents.splice(i, 1);
    }
  }
  if(typeof(index) != "undefined") {
    for(; index >= 0; index--) {
      if(this.events[index].endTime > time && !this.events[index].active) {
        this.events[index].active = true;
        uiInterface.showElement(this.events[index].element);
        this.activeEvents.push(this.events[index]);
      }
    }
  }
  this.lastSeekTime = time;
}

Slideshow.prototype.load = function(xmlDocument,   // DOM configureation
                                    uiInterface) { // object with UI interface functions
  this.uiInterface = uiInterface;
  this.backgroundMusic =
    xmlDocument.documentElement.getAttribute("backgroundMusic");

  var finishLayout = function(event) {
    this.ui.layout(this.slides, this.stopPoints);
  }
  finishLayout.stopPoints = this.extractStopPoints(xmlDocument);
  finishLayout.slides = this.extractSlides(xmlDocument);
  finishLayout.ui = this;
  this.loaded = true;
  addListener(uiInterface, "load", finishLayout, false);
  finishLayout.call(finishLayout); // the event could already have fired
}

/**
 * Does the final layout on the slides. This cannot be done
 * until all the html is loaded
 */
Slideshow.prototype.layout = function(slides, stopPoints) {
  if(!this.configured && uiInterface.loaded && !this.finishingLayout) {
    this.finishingLayout = true;
    this.timeSlides(slides, stopPoints);
    for(var i = 0; i < slides.length; i++) {
      var slideEvents = uiInterface.layoutSlide(slides[i]);
      //this.events = this.events.concat(slideEvents); // Adding a mystery element
      while(slideEvents.length > 0) {
        this.events.push(slideEvents.pop());
      }
    }
    this.events.sort(function (a, b) {
      if(a.startTime == b.startTime) {
        return 0;
      } else if(a.startTime > b.startTime) {
        return 1;
      } else {
        return -1;
      }
    });
    for(i = 0; i < this.events.length; i++) {
      this.presentationTime = Math.max(this.presentationTime,
                                       this.events[i].endTime);
    }
    this.configured = true;
    this.finishingLayout = undefined;

    var event = createEvent("Events");
    event.initEvent("configure", true, true); //true for can bubble, true for cancelable
    this.dispatchEvent(event);
  }
}

/**
 * Sets the display times on the slides. There are several possibilites:
 * A slide with no timing specification:
 *  The slide and first element get the current stopPoint and each element
 *   after gets the next stopPoint the end time is the next unused stopPoint
 * The startTime can be overridden
 */
Slideshow.prototype.timeSlides = function(slides, stopPoints) {
  var currentStopIndex = -1;

  var setSlideStartTime = function(element, startTime) {
    if(typeof(startTime) == "undefined") {
      startTime = element.startTime;
    }
    if(typeof(startTime) != "undefined") {
      if(startTime == "none") {
        // start time will be handled by the loader
      } else if(startTime.indexOf("+") >= 0) { // relative offset
        var offset =
          parseInt(startTime.substring(startTime.indexOf("+") + 1));
        // The first slide cannot have a relative offset
        if(currentStopIndex < 0) {
          currentStopIndex = 0;
        }
        try {
          element.startTime = stopPoints[currentStopIndex] + offset;
        } catch(e) {
          alert("Error Setting Start Time: " + e.message + ":" + element.nodeName);
        }
      } else {
        element.startTime = parseInt(element.startTime);
      }
    } else {
      if(currentStopIndex >= stopPoints.length) {
        alert("Too few stop points: " + stopPoints.length);
      }
      element.startTime = stopPoints[++currentStopIndex];
    }
  }
  
  for(var slideIndex = 0; slideIndex < slides.length; slideIndex++) {
    var slide = slides[slideIndex];
    setSlideStartTime(slide);
    for(elementIndex = 0; elementIndex < slide.length; elementIndex++) {
      var element = slide[elementIndex];
      /* The first element should display as the slide opens
       *  unless it has an explicit offset specified
       */
      if(elementIndex == 0 && typeof(element.startTime) == "undefined") {
        setSlideStartTime(element, "+0");
      } else {
        setSlideStartTime(element, element.startTime);
      }
      if(slide.type == "html") {
        var timings = element.timings;
        for(var i = 0; i < timings.length; i++) {
          setSlideStartTime(timings[i], timings[i].startTime);
        }
      }
    }
    var endTime = stopPoints[currentStopIndex + 1];
    if(typeof(slide.duration) != "undefined") {
      endTime = slide.startTime + parseInt(slide.duration);
    }
    slide.endTime = endTime;
    for(elementIndex = 0; elementIndex < slide.length; elementIndex++) {
      slide[elementIndex].endTime = endTime;
    }
    if(slide.type == "html") {
      var timings = element.timings;
      for(var i = 0; i < timings.length; i++) {
        if(!timings[i].element)
          alert("No timing on: " + timings[i].element);
        timings[i].endTime = endTime;
      }
    }
  }
}

/**
 * Takes a document and returns is list of an stop points that are present
 */
Slideshow.prototype.extractStopPoints = function(xmlDocument) {
  var stopPointLists = xmlDocument.getElementsByTagName("stopPointList");
  var stopPoints = new Array();
  for(var listIndex = 0; listIndex < stopPointLists.length; listIndex++) {
    var list = stopPointLists.item(listIndex);
    for(var childIndex = 0; childIndex < list.childNodes.length; childIndex++) {
      if(list.childNodes.item(childIndex).nodeType == Node.TEXT_NODE) {
        var points = list.childNodes.item(childIndex).data.split(/\s+/);
        for(var pointIndex = 0; pointIndex < points.length; pointIndex++) {
          if(points[pointIndex] != "") {
            stopPoints.push(parseInt(points[pointIndex]));
          }
        }
      } else if(list.childNodes.item(childIndex).nodeType == Node.COMMENT_NODE) {
      } else {
        alert("Unexpected child of stopPointsList: " +
              list.childNodes.item(childIndex).nodeType);
      }
    }
  }
  return stopPoints;
}

/**
 * Extracts basic information about the slides
 */
Slideshow.prototype.extractSlides = function(xmlDocument) {
  var slideElements = xmlDocument.getElementsByTagName("slide");
  var slideProps = ["startTime", "duration"];
  var slides = new Array();
  for(var slideIndex = 0; slideIndex < slideElements.length; slideIndex++) {
    var slide = slideElements.item(slideIndex);
    var objects = new Array();
    for(var propIndex = 0; propIndex < slideProps.length; propIndex++) {
      if(slide.getAttribute(slideProps[propIndex]) != null) {
        objects[slideProps[propIndex]] = slide.getAttribute(slideProps[propIndex]);
      }
    }
    for(var childIndex = 0; childIndex < slide.childNodes.length; childIndex++) {
      var child = slide.childNodes.item(childIndex);
      if(child.nodeType == Node.ELEMENT_NODE) {
        switch(child.nodeName) {
        case "document":
          var info = new DocumentInfo();
          for(var i = 0; i < child.childNodes.length; i++) {
            var elm = child.childNodes.item(i);
            if(elm.nodeType == Node.ELEMENT_NODE) {
              info.timings.push
                ({id : elm.getAttribute("targetId"),
                  startTime : elm.getAttribute("startTime"),
                  duration : elm.getAttribute("duration"),
                  animation : elm.getAttribute("introAnimation")});
            }
          }
          this.uiInterface.loadHTML(child.getAttribute("src"), info);
          objects.push(info);
          objects.type = "html";
          break;
        case "image":
          objects.push(new ImageInfo
                       (this.uiInterface.loadImage(child.getAttribute("src")),
                        child.getAttribute("style")));
          for(var propIndex = 0; propIndex < slideProps.length; propIndex++) {
            if(child.getAttribute(slideProps[propIndex]) != null) {
              objects[objects.length - 1][slideProps[propIndex]] =
                child.getAttribute(slideProps[propIndex]);
            }
          }
          break;
        case "loader":
          var func = "";
          for(var nodeIndex = 0; nodeIndex < child.childNodes.length; nodeIndex++) {
            if(child.childNodes.item(nodeIndex).nodeType == Node.TEXT_NODE ||
               child.childNodes.item(nodeIndex).nodeType == Node.CDATA_SECTION_NODE) {
              func += child.childNodes.item(nodeIndex).data;
            }
          }
          if(func != "") {
            eval("objects.loader = " + func + ";");
          }
          break;
        default:
          alert("Unknown slide element: " + child.nodeName);
        }
      }
    }
    slides.push(objects);
  }
  return slides;
}

Slideshow.prototype.addEventListener = function(event, listener, bubble) {
  if(event == "configure") {
    if(typeof(this.configureListeners) == "undefined") {
      this.configureListeners = new Array();
    }
    this.configureListeners.push(listener);
  }
}

Slideshow.prototype.dispatchEvent = function(event) {
  if(event.type == "configure" && 
     typeof(this.configureListeners) != "undefined") {
    for(var i = 0; i < this.configureListeners.length; i++) {
      this.configureListeners[i].call(this.configureListeners[i], event);
    }
  }
}

Slideshow.prototype.addEvent = function(element, startTime, endTime) {
  if(startTime >= endTime) {
    alert("Event ends at " + endTime +" <= when it starts " + startTime);
  }
  this.events.push(new DisplayEvent(element, startTime, endTime));
  this.presentationTime = Math.max(presentationTime, endTime);
  this.uiInterface = hideElement(element);
}
