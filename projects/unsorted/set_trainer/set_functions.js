var cards = generateCards();
var successes = 0;
var misses = 0;

// Size of the normal game space
var columns = 3;
var rows = 4;

// Size of the maximum game space
var maxColumns = columns + 1;
var maxRows = rows;

// Time to show a found set in milliseconds
demoTimeout = 1000

// Whether sets should be removed or just shown when the user can't
// find them
removeSets = true;

// Number of cards to deselect when the user makes an error
numDeselect = 1;

// Flag set when entire game being played automatically
autoplay = false;

var counter;
function setCounter(value) {
  if(!counter) counter = document.getElementById("counter");
  counter.childNodes.item(0).data = 
    successes + "/" + (successes + misses);
}
      
function incrementSuccessCounter() {
  successes++;
  setCounter();
}
      
function incrementMissCounter() {
  misses++;
  setCounter();
}
      
var cardLayout = new Array(maxColumns);
for(col = 0; col < cardLayout.length; col++) {
  cardLayout[col] = new Array();
}

var listenersAdded = false;

function addListeners() {
  var doc = document.rootElement ? document.rootElement : this;
  doc.addEventListener("keypress", keypress, false);
  for(i = 0; i < cards.length; i++) {
    cards[i].addEventListener("click", click, false);
  }
  listenersAdded = true;
}

var dealOrder;
var positions = new Object();
function initialDeal() {
  if(!listenersAdded) { // Listeners can't be added in Squiggle until document is loaded
    addListeners();
  }
  setCounter();
  dealOrder = deal(81);
  for(index = 0; index < rows * columns; index++) {
    placeCard(dealOrder.pop(), index % columns,
              Math.floor(index / (rows - 1)));
  }
}
      
/* Reveal and position a card; indicies are from 0
 */
function placeCard(id, col, row) {
  cardLayout[col][row] = cards[id];
  //positions[cards[id]] = {"x":x, "y":y}; // Firefox can't index on an object properly
  positions[cards[id].getAttribute("class")] = {"col":col, "row":row};
  cards[id].style.setProperty("display", "inherit", null);
  if(col < maxColumns && row < maxRows) {
    var transform = "translate(" + (col * 29) + "," + (row * 16) + ")";
    cards[id].setAttribute("transform", transform);
  } else {
    cards[id].setAttribute("transform",
                           "translate(" + (col * 29 + 10) + "," + 0 + ")");
  }
}

if(!String.prototype.endsWith) {
  String.prototype.endsWith = function(suffix) {
    var startPos = this.length - suffix.length;
    if (startPos < 0) { return false; }
    return (this.lastIndexOf(suffix, startPos) == startPos);
  };
}

/* Remove a card from the board and redeal if necessary.
 */
function removeCards(cards) {
 CARD:
  while(cards.length > 0) {
    var card = cards.pop();
    if(typeof(card) == "undefined") {
      continue;
    }
    card.setAttribute("class", card.getAttribute("class").replace(/ selected/, ""));
    card.style.setProperty("display", "none", null);
    var position = positions[card.getAttribute("class")];
    cardLayout[position.col][position.row] = undefined;
    // First check if there are any cards in the extra columns and move them in if possible
    // If the card was in an extra column, do nothing for it
    if(position.col < columns && position.row < rows) {
      for(var col = maxColumns - 1; col >= columns; col--) {
        for(var row = maxRows - 1; row >= 0; row--) {
          if(typeof(cardLayout[col]) != "undefined" && typeof(cardLayout[col][row]) != "undefined"
             && !cardLayout[col][row].getAttribute("class").endsWith("selected")) {
            placeCard(cardLayout[col][row].getAttribute("id"), position.col, position.row);
            cardLayout[col][row] = undefined;
            continue CARD; // Position filled by an existing card; next card
          }
        }
      }
      // Otherwise, deal in a new card
      if(dealOrder.length > 0) {
        placeCard(dealOrder.pop(), position.col, position.row);
      }
    }
  }
  if(cards.length > 0) { // This should not happen
    alert("Unremoved cards: " + cards);
  }
}

/* Respond to the users being unable to find a set
 */
function cannotFindSet() {
  var set = findSet();
  if(typeof(set) != "undefined") { // if a set was found by the computer
    if(removeSets) {
      while(selectedCards.length > 0) {
        deselectCard(selectedCards[0]);
      }
      while(set.length > 0) {
        selectCard(set.pop());
      }
      setTimeout("checkCardStack(false)", demoTimeout);
    } else {
      var setMessage = "Set:";
      for(i = 0; i < 3; i++) {
        setMessage += ("\n<" + positions[set[i].getAttribute("class")].col + "," +
                       positions[set[i].getAttribute("class")].row + "> = " +
                       set[i].getAttribute("class"));
      }
      alert(setMessage);
    }
  } else { // if no set currently exists
    incrementSuccessCounter();
    var cardsPlaced = 0;
    // Up to the maximum number of columns, lay out a new column at a time
    for(col = columns; col < maxColumns && dealOrder.length > 0 && cardsPlaced == 0; col++) {
      for(row = 0; row < maxRows && dealOrder.length > 0; row++) {
        if(typeof(cardLayout[col][row]) == "undefined") {
          placeCard(dealOrder.pop(), col, row);
          cardsPlaced++;
        }
      }
    }
    // If using the maxColumns didn't work, start placing cards one at a time
    // It should never be necessary for this to happen more than once
    for(col = maxColumns; dealOrder.length > 0 && cardsPlaced == 0; col++) {
      for(row = 0; row < maxRows && cardsPlaced == 0 && dealOrder.length > 0; row++) {
        if(typeof(cardLayout[col][row]) == "undefined") {
          placeCard(dealOrder.pop(), col, row);
          cardsPlaced++;
        }
      }
    }
    if(cardsPlaced == 0 && dealOrder.length == 0) { // There are no cards left to play
      for(var col = 0; col < cardLayout.length; col++) {
        removeCards(cardLayout[col]);
      }
      initialDeal();
    }
    if(autoplay) {
      cannotFindSet();
    }
  }
}

function keypress(event) {
  var charCode = event.getCharCode ? event.getCharCode() : event.charCode;
  if(String.fromCharCode(charCode).toLowerCase() == "h") {
    cannotFindSet();
  } else if(String.fromCharCode(charCode).toLowerCase() == "a") {
    autoplay = !autoplay;
    cannotFindSet();
  } else {
    //alert(String.fromCharCode(event.getCharCode()));
  }
  if(window.focus) window.focus();
}

var selectedCards = new Array();
function click(event) {
  elm = event.target;
  if(typeof(elm.correspondingUseElement) != "undefined") {
    elm = elm.correspondingUseElement;
  }
  var absurdDepth = 100;
  while(elm.nodeName != "g" && elm.parentNode && 
        typeof(elm.parentNode.nodeName) != "undefined" &&
        elm.parentNode.nodeName != "svg" && --absurdDepth > 0) {
    elm = elm.parentNode;
  }
  if(elm.nodeName != "g") {
    // possibly clicked a path in a viewer that doesn't put
    // 'use'd methods in the tree
  } else {
    if(!elm.getAttribute("class").match(/selected/)) {
      selectCard(elm);
      if(selectedCards.length == 3) {
        // Adobe's viewer needs a second to refresh
        setTimeout("checkCardStack(true)", 200);
      }
    } else {
      deselectCard(elm);
    }
  }
}

function selectCard(card) {
  card.setAttribute("class", card.getAttribute("class") + " selected");
  selectedCards.push(card);
}

function deselectCard(card) {
  for(index = 0; index < selectedCards.length && selectedCards[index] != card; index++);
  if(index < selectedCards.length) {
    selectedCards.splice(index, 1);
    card.setAttribute("class", card.getAttribute("class").replace(/ selected/, ""));
  }
}

function checkCardStack(natural) {
  var goodSet = checkSet(selectedCards);
  if(goodSet) {
    removeCards(selectedCards);
    if(natural) {
      incrementSuccessCounter();
    } else {
      incrementMissCounter();
    }
    if(autoplay) {
      cannotFindSet();
    }
  } else {
    for(i = 0; i < numDeselect && selectedCards.length > 0; i++) {
      deselectCard(selectedCards[selectedCards.length - 1]);
    }
  }
}

/* Find a set if present on the board and return the cards, otherwise return undefined
 */
function findSet() {
  var max = cardLayout.length * cardLayout[0].length;
  for(start = 0; start < max; start++) {
    startI = start % cardLayout.length;
    startJ = Math.floor(start / cardLayout.length);
    if(typeof(cardLayout[startI][startJ]) != "undefined") {
      for(second = start + 1; second < max; second++) {
        secondI = second % cardLayout.length;
        secondJ = Math.floor(second / cardLayout.length);
        /*
          alert("Checking: " + cardLayout.length + " <" + startI + ", " + startJ + "> / <" +
          secondI + ", " + secondJ + ">");
        */
        if(typeof(cardLayout[secondI][secondJ]) != "undefined") {
          var ids = [cardLayout[startI][startJ].getAttribute("id"),
                     cardLayout[secondI][secondJ].getAttribute("id")]
            /* f({0,1}) = 2  sum({0,1}) = 1
             * f({0,2}) = 1  sum({0,2}) = 2
             * f({1,2}) = 0  sum({1,2}) = 3
             * f({0,0}) = 0  sum({0,0}) = 0
             * f({1,1}) = 1  sum({1,1}) = 2
             * f({2,2}) = 2  sum({2,2}) = 4
             * therefore f(x) = (3 - (sum(x) mod 3)) mod 3
             */
          var sumShape = ids[0] % 3 + ids[1] % 3;
          var sumShade = (Math.floor(ids[0] % 9 / 3)
                          + Math.floor(ids[1] % 9 / 3));
          var sumColor = (Math.floor(ids[0] % 27 / 9)
                          + Math.floor(ids[1] % 27 / 9));
          var sumCount = (Math.floor(ids[0] / 27)
                          + Math.floor(ids[1] / 27));
          var shape = (3 - sumShape % 3) % 3;
          var shade = (3 - sumShade % 3) % 3;
          var color = (3 - sumColor % 3) % 3;
          var count = (3 - sumCount % 3) % 3;
          id = shape + 3 * shade + 9 * color + 27 * count;
          if(id >= 81) {
            alert(id + " = " + sumCount + "#" + count + ":"
                  + sumColor + "#" + color + ":"
                  + sumShade + "#" + shade + ":"
                  + sumShape + "#" + shape);
          }
          if(cards[id].style.getPropertyValue("display") != "none") {
            return new Array(cardLayout[startI][startJ],
                             cardLayout[secondI][secondJ],
                             cards[id]);
          }
        }
      }
    }
  }
  return undefined;
}
