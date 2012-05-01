RED = 0;
GREEN = 1;
PURPLE = 2;

SOLID = 0;
SHADED = 1;
HOLLOW = 2;

SQUIGGLE = 0;
OVAL = 1;
DIAMOND = 2;

function generateCards() {
  var cards = new Array();
  for(var count = 0; count < 3; count++) {
    for(var colorIndex = 0; colorIndex < 3; colorIndex++) {
      for(var shadeIndex = 0; shadeIndex < 3; shadeIndex++) {
        for(var shapeIndex = 0; shapeIndex < 3; shapeIndex++) {
          var card = generateCard(count, colorIndex, shadeIndex, shapeIndex);
          card.style.setProperty("display", "none", null);
          document.getElementsByTagName("svg").item(0).appendChild(card);
          cards.push(card);
        }
      }
    }
  }
  return cards;
} 

function generateCard(count, colorIndex, shadeIndex, shapeIndex) {
  //var card = document.createElement("g");
  var card = document.createElementNS("http://www.w3.org/2000/svg", "g");
  var desc = new Object();
  
  desc.count = (count == 0 ? "one" : (count == 1 ? "two" : "three"));
  desc.color = (colorIndex == RED ? "red" : (colorIndex == GREEN ? "green" : "purple"));;
  desc.shade = (shadeIndex == SOLID ? "solid" : (shadeIndex == SHADED ? "shaded" : "hollow"));;
  desc.shape = (shapeIndex == OVAL ? "oval" : (shapeIndex == DIAMOND ? "diamond" : "squiggle"));;
  
  card.setAttribute("id", count * 27 + colorIndex * 9 + shadeIndex * 3 + shapeIndex);

  var cardClass = (desc.count + " " + desc.shade + " "
                   + desc.color + " " + desc.shape + " card");
  card.setAttribute("class", cardClass);

  var cardBack = document.createElementNS("http://www.w3.org/2000/svg", "rect");
  cardBack.setAttribute("width", 28);
  cardBack.setAttribute("height", 15);
  cardBack.setAttribute("rx", 3);
  card.appendChild(cardBack);
  
  function addShape(offset) {
    var shape = document.createElementNS("http://www.w3.org/2000/svg", "use");
    shape.setAttributeNS("http://www.w3.org/1999/xlink",
                         "href",
                         (shapeIndex == SQUIGGLE ? "#squiggle" :
                          (shapeIndex == OVAL ? "#oval" : "#diamond")));
    shape.setAttribute("xlink:href",
                         (shapeIndex == SQUIGGLE ? "#squiggle" :
                          (shapeIndex == OVAL ? "#oval" : "#diamond")));
    shape.setAttribute("x", offset);
    card.appendChild(shape);
    //shape.parentNode = card;
  }

  if(count + 1 == 1 || count + 1 == 3) {
    addShape(14);
  } else {
    addShape(10);
    addShape(18);
  }
  if(count + 1 == 3) {
    addShape(6.5);
    addShape(21.5);
  }
  return card;
}

var props = new Array("count", "color", "shape", "shade");
function checkSet(cards) {
  if(cards.length != 3) {
    return false;
  }
  var message = "";
  var cardProps = new Array();
  for(i = 0; i < cards.length; i++) {
    cardProps.push(cards[i].getAttribute("class").replace(/ card.*/, "").split(/ +/));
  }
  for(propIndex = 0; propIndex < props.length; propIndex++) {
    var prop = props[propIndex];
    if((cardProps[0][propIndex] ==  cardProps[1][propIndex] &&
        cardProps[1][propIndex] !=  cardProps[2][propIndex]) || 
       (cardProps[0][propIndex] !=  cardProps[1][propIndex] &&
        cardProps[1][propIndex] ==  cardProps[2][propIndex]) ||
       (cardProps[0][propIndex] ==  cardProps[2][propIndex] &&
        cardProps[0][propIndex] !=  cardProps[1][propIndex])) {
      var propValueCount = new Array();
      for(i = 0; i < cardProps.length; i++) {
        if(typeof(propValueCount[cardProps[i][propIndex]]) == "undefined") {
          propValueCount[cardProps[i][propIndex]] = 1;
        } else {
          propValueCount[cardProps[i][propIndex]]++;
        }
      }
      if(message != "") message += "\n";
      message += "Bad " + prop + ":";
      for(value in propValueCount) {
        message += ("\n    • " + propValueCount[value] + " "
                    + value + (propValueCount[value] != 1 ? "s" : ""));
      }
    }
  }
  if(message == "") {
    return true;
  } else {
    alert(message);
    return false;
  }
}

function random(n) {
  return Math.floor(n * (Math.random() % 1));
}

function deal(n) {
  var j, k, q = new Array(n);
  for(j = 0; j < n; j++) {
    k = random(j + 1);
    q[j] = q[k];
    q[k] = j;
  }
  return q;
}
