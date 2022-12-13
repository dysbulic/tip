function setCounter(id, number) {
  for(var i = 5; i >= 1; i--) {
    var num = Math.floor(number / Math.pow(10, i - 1));
    document.getElementById(id + i).contentDocument.defaultView.setNumber(num);
    number -= num * Math.pow(10, i - 1);
  }
}

var activeIndex = undefined;

var readyStates = ["Uninitialised", "Loading", "Loaded", "Interactive", "Completed"];

function getNewArtists(count) {
  if(typeof(count) == "undefined") count = 10;
  if(count == 0) return;
  var callback = function(request) {
    document.request = request;
    this.status.appendChild(document.createElement("div"));
    this.status.lastChild.appendChild(document.createTextNode("Status: " + readyStates[request.readyState] + ": " +
                                                              (request.readyState <= 1 ? this.url :
                                                               request.status + " (" + request.statusText + ")")));
    if(request.readyState == 4 && request.status == 200) {
      var artistIterator = document.evaluate("artistlist/artist", request.responseXML, null, XPathResult.ORDERED_NODE_ITERATOR_TYPE, null);
      while((artist = artistIterator.iterateNext()) != null) {
        var artistName = document.evaluate("name/text()", artist, null, XPathResult.STRING_TYPE, null).stringValue;
        var breakIterator = document.evaluate("breakdown/artist", artist, null, XPathResult.ORDERED_NODE_ITERATOR_TYPE, null);
        var breakdown = new Array();
        while((compArtist = breakIterator.iterateNext()) != null) {
          var artist = { name: document.evaluate("name/text()", compArtist, null, XPathResult.STRING_TYPE, null).stringValue,
                         count: document.evaluate("@count", compArtist, null, XPathResult.NUMBER_TYPE, null).numberValue };
          breakdown.push(artist);
        }
        addArtist(artistName, breakdown, false);
      }
      setCounter("count", request.responseXML.firstChild.getAttribute("inquestion"));
      setCounter("total", request.responseXML.firstChild.getAttribute("total"));
      if(activeIndex == undefined) {
        setActiveIndex(0);
      }
    }
  };
  callback.url = "ws/compartist/unidentified";
  callback.status = document.getElementById("status");
  loadXMLDocument(callback.url, callback);
}
addListener(this, "load", function() { getNewArtists(10); }, true);

function keyListener(event) {
  switch(event.keyCode) {
  case 39: setActiveComposite(true); forward(); break;
  case 37: setActiveComposite(false); forward(); break;
  case 40: setActiveComposite(undefined); killEvent(event); break;
  case 38: backward(); setActiveComposite(undefined); break;
  default: //alert(event.keyCode);
  }
}
addListener(document, "keydown", keyListener, false);

function setActiveComposite(isComposite) {
  var artist = artistEntries[activeIndex];
  artist.isComposite = isComposite;
  artist.content.className = artist.content.className.replace(/ +(maybe)?(comp|notcomp)/, "");
  if(isComposite != undefined) {
    artist.content.className += " " + (isComposite ? "comp" : "notcomp");
    artist.content.lastChild.innerHTML = (isComposite ? "&#x2190;&#x2192;" : "&#x2192;&#x2190;");
  } else {
    artist.status.data = "";
    artist.content.lastChild.lastChild.data = "?";
  }
}

function CountDown(element) {
  this.count = function() {
    if(typeof(this.element.isComposite) != "undefined") {
      var count = parseInt(this.element.status.data) - 1;
      this.element.status.data = count;
      if(count > 0) this.callback();
      else submitElement(element);
    }
  }
  this.callback = function() {
    var countdown = arguments.callee.container;
    setTimeout(function() { countdown.count.call(countdown); }, 1000);
  }
  this.callback.container = this;
  this.element = element;
  this.callback();
}

function submitElement(element) {
}

function forward() {
  var currentArtist = artistEntries[activeIndex];
  currentArtist.status.data = "5";
  new CountDown(currentArtist);
  window.scrollBy(0, currentArtist.content.offsetHeight);
  setActiveIndex(activeIndex + 1);
}

function backward() {
  var currentArtist = artistEntries[activeIndex];
  currentArtist.status.data = "";
  window.scrollBy(0, -currentArtist.content.offsetHeight);
  setActiveIndex(activeIndex - 1);
}

function cancelCommit() {
}

function setActiveIndex(index) {
  if(index == activeIndex || index < 0 || index >= artistEntries.length) return;
  if(activeIndex != undefined) {
    artistEntries[activeIndex].className = artistEntries[activeIndex].className.replace(/ +active/, "");
  }
  activeIndex = index;
  artistEntries[activeIndex].className += " active";
}

var artistEntries = new Array();
function addArtist(name, breakdown, isComposite) {
  artistEntries.push(new ArtistEntry(name, breakdown, isComposite));
  if(typeof(activeIndex) == "undefined") setActiveIndex(0);
}

function ArtistEntry(name, breakdown, isComposite) {
  var artistXML = "<div xmlns='http://www.w3.org/1999/xhtml' class='artist " + (isComposite ? "maybecomp" : "maybenot") + "'>";
  artistXML += "<div class='activeindicator'></div>";
  artistXML += "<div class='info'>";
  artistXML += "<div class='wholename'>" + name.replace("&", "&amp;") + "</div>";
  artistXML += "<ul class='breakdown'>";
  for(var i = 0; i < breakdown.length; i++) {
    artistXML += "<li><span class='count'>" + breakdown[i].count + "</span> " + breakdown[i].name + "</li>";
  }
  artistXML += "</ul>";
  artistXML += "</div>";
  artistXML += "<div class='status'>?</div>";
  artistXML += "</div>";

  this.parent = document.getElementById("artistlist");
  var newArtist = (new DOMParser()).parseFromString(artistXML, "text/xml");
  this.content = document.importNode(newArtist.documentElement, true);
  this.parent.appendChild(this.content);

  if(this.content.firstChild.firstChild == null) {
    this.content.firstChild.appendChild(document.createTextNode(""));
  }
  this.status = this.content.firstChild.firstChild;
}
