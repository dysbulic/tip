/**************************************
DeckOfCards.cpp  Brett Holcomb  2006-02-06
Question 1 on Vicious Cycle-Engineering Test
 
A standard deck of playing cards consists of 52 cards, defined as 4 suits of 13 card types 
(Ace, 2-King).  Design code to manage and store a deck, identify card suit and type, and implement a 
routine to shuffle the deck.
**************************************/
#include <iostream>
using namespace std;


// data type to represent the 4 suits of cards
enum suits_t {clubs, diamonds, hearts, spades};

/** a card has a suit and a rank 1-13, where 1=ace, 2-10=face value, and 11-13=jack, queen, king respectively **/
class Card {
  public:
  suits_t suit;
  int rank;
  
  // prints the card's suit and rank to console
  void printCard() {    
    string suitString;  // string to display the suit of the card
    if (suit == clubs)
      suitString = "clubs";
    else if (suit == diamonds)
      suitString = "diamonds";
    else if (suit == hearts)
      suitString = "hearts";
    else
      suitString = "spades";
  
    // show card's rank and suit
    if (rank >= 2 && rank <= 10)  // a number card
      cout << rank << " of " << suitString;
    else {                        // a face card
      if (rank == 1)
        cout << "ace of " << suitString;
      if (rank == 11)
        cout << "jack of " << suitString;
      if (rank == 12)
        cout << "queen of " << suitString;
      else
        cout << "king of " << suitString;
    }
  }
}; 

/** a Deck contains 52 cards **/   
class Deck {
  public:    
    Card cardsInDeck [52];
  
  // populates the Deck with cards IN ORDER
  void populateDeck(){
    for (int suit_i=0; suit_i <= 3; suit_i++) {
      for (int rank_i=1; rank_i <= 13; rank_i++) {
        Card tempCard;
        tempCard.suit = suits_t(suit_i);
        tempCard.rank = rank_i;
        cardsInDeck[suit_i*13 + rank_i - 1] = tempCard;
      }
    }
  }
  
  // shuffles the cards in the deck
  void shuffle() {
    srand ( time(NULL) );
    random_shuffle(cardsInDeck, cardsInDeck+52); 
  } 
  
  // print every card in deck to console
  void printDeck() {
    for (int i=0; i<52; i++) {
      cardsInDeck[i].printCard();
      cout << "\n";
    }
  }
};

// demo code
int main () {
  Deck myDeck;
  myDeck.populateDeck();
  myDeck.printDeck();
  
  cout << "\n\nShuffle up and show em'!!\n\n"; 
  myDeck.shuffle();
  myDeck.printDeck();  
  
  return 0;
}

