function setupFade(element, property, fadeStep, fadeTimeout,
                   fadeInListener, fadeOutListener) {
  if(typeof(element) == "undefined" || element == null) {
    return;
  }
  element.fadeProperty = typeof(property) == "undefined" ?
    "background-color" : property;
  element.fadeStep = typeof(fadeStep) == "undefined" ?
    .1 : fadeStep;
  element.fadeTimeout =typeof(fadeTimeout) == "undefined" ?
    25 : fadeTimeout;
  if(typeof(fadeInListener) == "undefined") {
    fadeInListener = fadeIn;
  }
  if(typeof(fadeOutListener) == "undefined") {
    fadeOutListener = fadeOut;
  }

  var id = element.getAttribute("id");
  var rule;
  for(var i = document.styleSheets.length - 1; i >= 0 && typeof(rule) == "undefined"; i--) {
    if(!document.styleSheets[i].disabled) {
      try {
        var rules = (typeof(document.styleSheets[i].cssRules) != "undefined" ?
                     document.styleSheets[i].cssRules :
                     (typeof(document.styleSheets[i].rules) != "undefined" ?
                      document.styleSheets[i].rules :
                      undefined));
        for(var j = rules.length - 1; j >= 0 && typeof(rule) == "undefined"; j--) {
          var pattern = new RegExp("#" + id + ":hover");
          if(rules[j].selectorText.match(pattern)) {
            rule = rules[j];
          }
        }
      } catch(exception) {
        // System stylesheets can't be accessed even though they are liste
        // here in Linux Mozilla
        //alert("Exception: " + (exception.description == null ? exception.message : exception.description));
      }
    }
  }

  function toCamelCase(property) {
    var outputStringList = property.split('-');
    if(outputStringList.length == 1) {
      return outputStringList[0];
    }
    var camelCase = (property.indexOf("-") == 0 ? 
                     outputStringList[0].charAt(0).toUpperCase() + outputStringList[0].substring(1) :
                     outputStringList[0]);
    for(var i = 1; i < outputStringList.length; i++) {
      var part = outputStringList[i];
      camelCase += part.charAt(0).toUpperCase() + part.substring(1);
    }
    return camelCase;
  }

  if(typeof(rule) != "undefined") {
    var startColor = (typeof(document.defaultView) != "undefined" ?
                      document.defaultView.getComputedStyle(element, null).getPropertyValue(property) :
                      (typeof(element.currentStyle) != "undefined" ?
                       element.currentStyle[toCamelCase(property)] :
                       undefined));

    if(typeof(startColor) != "undefined") {
      setStartFadeColor(element, startColor);
      setEndFadeColor(element, (typeof(rule.style.getPropertyValue) != "undefined" ?
                                rule.style.getPropertyValue(property) :
                                rule.style[property]));
      //rule.style.setProperty(property, "", null);
      addListener(element, "mouseover", fadeInListener);
      addListener(element, "mouseout", fadeOutListener);
      //element.addEventListener("mouseover", fadeInListener, false);
      //element.addEventListener("mouseout", fadeOutListener, false);
    }
  }
}

function setStartFadeColor(element, color) {
  var endColors;
  if(typeof(element.startColors) != "undefined" &&
     typeof(element.colorDeltas) != "undefined") {
    endColors = new Array(element.startColors.length);
    for(var i = 0; i < endColors.length; i++) {
      endColors[i] = element.startColors[i] + element.colorDeltas[i];
    }
  }

  element.startColors = color.replace(/rgb\((.*)\)/, "$1").split(/\s*,\s*/);
  for(var i = 0; i < element.startColors.length; i++) {
    element.startColors[i] = new Number(element.startColors[i]).valueOf();
  }

  if(typeof(endColors) != "undefined") {
    element.colorDeltas = new Array(element.startColors.length);
    for(var i = 0; i < endColors[i]; i++) {
      element.colorDeltas[i] = endColors[i] - element.startColors[i];
    }
    fade(element, typeof(element.hovered) == "undefined" ? false : element.hovered);
  }
}


function setEndFadeColor(element, color) {
  if(typeof(element.startColors) != "undefined") {
    var endColors = color.replace(/rgb\((.*)\)/, "$1").split(/\s*,\s*/);
    if(typeof(element.colorDeltas) == "undefined") {
      element.colorDeltas = new Array(endColors.length);
    }
    for(var i = 0; i < endColors.length; i++) {
      element.colorDeltas[i] = new Number(endColors[i]).valueOf() - element.startColors[i];
    }
    fade(element, typeof(element.hovered) == "undefined" ? false : element.hovered);
  }
}

function fadeIn(event) {
  var target = getSource(event);
  target.hovered = true;
  fade(target, true);
}

function fadeOut(event) {
  var target = getSource(event);
  target.hovered = false;
  fade(target, false);
}

function fade(element, fadingIn) {
  if(typeof(element.hovered) == "undefined" || element.hovered == fadingIn) {
    if(typeof(element.startColors) == "undefined") {
      setupFade(element);
    }
    if(typeof(element.currentColors) == "undefined") {
      element.currentColors = new Array(element.startColors.length);
    }
    if(typeof(element.fadePercent) == "undefined") {
      element.fadePercent = 0;
    }
    for(var i = 0; i < element.startColors.length; i++) {
      element.currentColors[i] = Math.round(element.startColors[i] + element.fadePercent * element.colorDeltas[i]);
    }
    var currentColor = ("rgb(" + element.currentColors[0] + ", " + element.currentColors[1] +
                        ", " + element.currentColors[2] + ")");
    if(typeof(element.style.setProperty) != "undefined") {
      element.style.setProperty(element.fadeProperty, currentColor, null);
    } else {
      element.style[element.fadeProperty] = currentColor;
    }
    if((fadingIn && element.fadePercent < 1) || (!fadingIn && element.fadePercent > 0)) {
      if(fadingIn) {
        element.fadePercent = Math.min(element.fadePercent + element.fadeStep, 1);
      } else {
        element.fadePercent = Math.max(element.fadePercent - element.fadeStep, 0);
      }
      setTimeout(function() { fade(element, fadingIn) }, element.fadeTimeout);
    }
  }
}
